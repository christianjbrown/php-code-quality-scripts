# CLAUDE.md

Guidance for working in this repository. It is small, opinionated, and self-dogfooding — its own
PHP is held to the very standard it ships, so new code should be indistinguishable from what's here.

## What this is

A distributable Composer package that **provides** code-style tooling for other PHP projects — it
is not an application or a library of PHP classes. It ships:

- a **PHP_CodeSniffer standard** (`config/standard.xml`, name `ChristianBrown`), built on the
  `escapestudios/symfony2-coding-standard` `Symfony` ruleset plus PSR1/PSR2 and a curated set of
  Generic/PEAR sniffs, enforcing tab-width 4, cyclomatic complexity 8 (abs 10), nesting level 5
  (abs 10), short array syntax, one object structure per file, etc.;
- two **PHP CS Fixer rule sets** — `config/Risky.php` (non-backward-compatible rules for new files:
  `declare_strict_types`, `final_class`, `strict_*`, the `:risky` migration sets) and
  `config/Safe.php` (backward-compatible rules for existing/legacy files: `declare_strict_types`
  disabled, no `final_class`);
- four **bash wrapper scripts** in `src/` that are the package's Composer `bin`.

Consumers `require --dev christianjbrown/php-code-quality-scripts` and call the wrappers (they
install to the consumer's `bin/`). `php-api-client-lib` is the reference consumer.

## Layout

- **`config/`** — the product. `standard.xml` (phpcs), `Risky.php` / `Safe.php` (php-cs-fixer). The
  two PHP files are intentionally namespace-less config scripts that `return` a `PhpCsFixer\Config`;
  each carries a `// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace` header for that
  reason. Rulesets can be regenerated at https://mlocati.github.io/php-cs-fixer-configurator/.
- **`src/`** — the four bash wrappers, declared as `bin` in `composer.json`:
  - `php-cs` — phpcs with the `ChristianBrown` standard.
  - `php-cs-diff` — phpcs on git-changed + untracked files only.
  - `php-cs-fix` — php-cs-fixer, defaults to the **Risky** ruleset.
  - `php-cs-fix-diff` — php-cs-fixer on the git diff: **Risky** on new/untracked files, **Safe** on
    existing (modified/renamed) files.
- **`tests/`** — PHPUnit smoke tests that `include` the fixer configs and assert the returned
  `Config` object's flags and a spot-check of rule keys. Namespaced under
  `ChristianBrown\CodeQualityScripts\Tests\` (`autoload-dev`).

Each wrapper resolves its underlying tool binary across three candidate locations — installed as a
dependency (`../../../bin`), the consumer's `vendor/bin` (`../../../vendor/bin`), or standalone
(local `bin/`) — and every default is overridable by env var: `PHP_CS`, `PHP_CS_STANDARD`,
`PHP_CS_FIXER`, `PHP_CS_FIX_CONFIG`, `PHP_CS_FIX_CONFIG_SAFE`, `PHP_CS_FIX_CONFIG_RISKY`.

## Commands

Tools install into `bin/` (Composer `bin-dir`), not `vendor/bin/`. Both `bin/` and `vendor/` are
gitignored and Composer-installed, so run `composer install` first — its `post-install-cmd` runs
`setup-standards`, which registers the phpcs standard so `./src/php-cs` works.

| Task | Command |
| --- | --- |
| Run tests + coverage (opens HTML report) | `composer test` |
| Run tests, no coverage | `php -d memory_limit=-1 ./bin/phpunit --no-coverage` |
| Static analysis (PHPStan level max) | `composer stan` |
| Check code style (dogfoods the standard on `config` + `tests`) | `composer check-style` |
| Auto-fix code style | `composer fix-style` |
| Check / fix style on git diff only | `composer check-style-diff` / `composer fix-style-diff` |

Run order before finishing: `composer fix-style` → `composer check-style` → `composer stan` →
`composer test`. CI (`.github/workflows/ci.yml`) runs the same gates — style → PHPStan →
PHPUnit-with-coverage — on push/PR to `main`, on PHP 8.5. All dependencies are public, so no
Composer auth is needed.

## Conventions (follow all of these)

- The config PHP files carry `declare(strict_types=1);` and the namespace-less `phpcs:disable`
  header described above — keep both.
- **The repo lints itself.** `composer check-style` runs the `ChristianBrown` standard over
  `config` and `tests`, and CI fails on any violation, so any PHP you add must already conform. When
  editing a rule set, run `composer fix-style` then `composer check-style` and confirm both are
  clean with no deprecation warnings.
- Tests are `final`, namespaced, and assert statically with `self::assertSame` / `self::assert*`.
  They cover the config files by `include`-ing them, which executes every line. Because the config
  files are bare `return`-a-`Config` scripts (no class/function to name as a coverage target),
  `phpunit.xml` deliberately does **not** set `requireCoverageMetadata` /
  `beStrictAboutCoverageMetadata` — those would force `#[CoversNothing]` and discard the config-file
  line coverage. It keeps `failOnRisky`, `failOnWarning`, and path coverage; the suite reports 100%
  line coverage of `config/`.
- Keep the **Risky vs Safe** split meaningful: a rule belongs in `Safe.php` only if it is
  backward-compatible and safe to run unattended on legacy code; anything that can change behavior
  (strict types/comparisons, finalization, migrations) is Risky-only.

## Adding or changing a rule

1. Edit `config/standard.xml` (phpcs) or `config/Risky.php` / `config/Safe.php` (php-cs-fixer).
2. If it's a fixer rule, extend the assertions in `tests/FixRiskyConfigTest.php` /
   `tests/FixSafeConfigTest.php` to spot-check the new key.
3. Run `composer fix-style` → `composer check-style` → `composer stan` → `composer test` and confirm
   all are green with no deprecation warnings.
