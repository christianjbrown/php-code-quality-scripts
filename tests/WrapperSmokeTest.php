<?php

declare(strict_types=1);

namespace ChristianBrown\CodeQualityScripts\Tests;

use PHPUnit\Framework\TestCase;

use function escapeshellarg;
use function exec;
use function fclose;
use function file_put_contents;
use function getenv;
use function is_dir;
use function mb_rtrim;
use function mkdir;
use function proc_close;
use function proc_open;
use function rmdir;
use function scandir;
use function stream_get_contents;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

/**
 * Behavioural smoke tests for the four bash wrappers. They are the package's
 * value — everything else here is config — but being shell they cannot be
 * line-covered by PHPUnit, so these exercise them end-to-end via proc_open.
 * The emphasis is the hardening: paths with spaces must not word-split, the
 * diff wrappers must iterate NUL-delimited, and a syntax error must still abort.
 *
 * @internal
 *
 * @see ../src/php-cs
 * @see ../src/php-cs-diff
 */
final class WrapperSmokeTest extends TestCase
{
    private const ROOT = __DIR__.'/..';

    /**
     * @var array<int, string>
     */
    private array $tempDirs = [];

    protected function tearDown(): void
    {
        foreach ($this->tempDirs as $dir) {
            self::removeDir($dir);
        }
        $this->tempDirs = [];
    }

    public function testPhpCsDiffAbortsOnASyntaxError(): void
    {
        // A file that fails `php -l` must abort the whole run with a non-zero
        // exit — the process-substitution loop keeps `exit 1` in the parent shell.
        $repo = $this->makeGitRepo();
        file_put_contents($repo.'/broken.php', "<?php\n\$x = ;\n");

        [$exit] = $this->runWrapper('php-cs-diff', [], $repo);

        self::assertSame(1, $exit, 'a syntax error must abort the diff run with exit 1');
    }

    public function testPhpCsDiffProcessesAnUntrackedFileWithSpaces(): void
    {
        // The diff wrapper now reads `git ... -z` NUL-delimited, so an untracked
        // file whose name contains a space is still handed to the linter.
        $repo = $this->makeGitRepo();
        file_put_contents($repo.'/spaced untracked.php', "<?php\n\n\$x = 1;\necho \$x;\n");

        [, $stdout] = $this->runWrapper('php-cs-diff', [], $repo);

        self::assertStringContainsString('spaced untracked.php', $stdout, 'the NUL-delimited loop must reach the space-containing untracked file');
    }

    public function testPhpCsHandlesAFilenameWithSpaces(): void
    {
        // The pre-hardening `$FILES` expansion would split "spaced name.php" into
        // two nonexistent paths; the array form must pass it as a single file.
        $dir = $this->makeTempDir();
        $file = $dir.'/spaced name.php';
        file_put_contents($file, "<?php\n\n\$x=1;\n");

        [$exit, $stdout] = $this->runWrapper('php-cs', [$file]);

        self::assertNotSame(0, $exit);
        self::assertStringContainsString('spaced name.php', $stdout, 'the space-containing path must be linted as one file, not split');
        self::assertStringNotContainsString('No such file', $stdout);
    }

    public function testPhpCsReportsAStyleViolation(): void
    {
        $dir = $this->makeTempDir();
        $file = $dir.'/violation.php';
        file_put_contents($file, "<?php\n\n\$x=1;\n");

        [$exit, $stdout] = $this->runWrapper('php-cs', [$file]);

        self::assertNotSame(0, $exit, 'phpcs should exit non-zero when a file has a violation');
        self::assertStringContainsString('violation.php', $stdout);
        self::assertStringContainsString('ERROR', $stdout);
    }

    private function makeGitRepo(): string
    {
        $dir = $this->makeTempDir();
        exec('cd '.escapeshellarg($dir).' && git init -q && git config user.email t@t.t && git config user.name t && git commit -q --allow-empty -m baseline 2>&1');

        return $dir;
    }

    private function makeTempDir(): string
    {
        $dir = mb_rtrim(sys_get_temp_dir(), '/').'/cq-smoke-'.uniqid('', true);
        mkdir($dir, 0o777, true);
        $this->tempDirs[] = $dir;

        return $dir;
    }

    private static function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $entry) {
            if ('.' === $entry || '..' === $entry) {
                continue;
            }
            $path = $dir.'/'.$entry;
            is_dir($path) ? self::removeDir($path) : unlink($path);
        }
        rmdir($dir);
    }

    /**
     * @param string             $bin
     * @param array<int, string> $args
     * @param null|string        $cwd
     *
     * @return array{0: int, 1: string, 2: string} [exitCode, stdout, stderr]
     */
    private function runWrapper(string $bin, array $args = [], ?string $cwd = null): array
    {
        $command = [self::ROOT.'/src/'.$bin, ...$args];
        $descriptors = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $process = proc_open($command, $descriptors, $pipes, $cwd ?? self::ROOT, ['PATH' => getenv('PATH') ?: '/usr/bin:/bin']);
        self::assertIsResource($process);

        $stdout = (string) stream_get_contents($pipes[1]);
        $stderr = (string) stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), $stdout, $stderr];
    }
}
