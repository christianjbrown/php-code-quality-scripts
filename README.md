# PHP Code Quality Scripts

[![CI](https://github.com/christianjbrown/code-quality-scripts-php/actions/workflows/ci.yml/badge.svg)](https://github.com/christianjbrown/code-quality-scripts-php/actions/workflows/ci.yml)

This project

* Installs [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
* A **PHP Code Sniffer standard** I prefer to use (on PHP_CodeSniffer 4), building upon existing PSR, PEAR, Squiz and Generic standards plus [slevomat/coding-standard](https://github.com/slevomat/coding-standard) sniffs (native type hints, unused/sorted uses, abstract-or-final).
* A set of **PHP CS Fixer rule sets** I prefer to use to clean up code to meet PSR, Symfony, PEAR and Generic standards. One riskier set for new files, and a safer set for existing files. 
* Wrapper shell scripts
  * `./src/php-cs` is a very simple wrapper around PHP Code Sniffer's own binary, to simplify the command line and load the right standard.
  * `./src/php-cs-diff` runs PHP Code Sniffer only on files changed according to Git.
  * `./src/php-cs-fix` is a very simple wrapper around PHP-CS-Fixer's own binary, to simplify the command line and load the right ruleset.
  * `./src/php-cs-fix-diff` runs PHP CS Fixer only on files changed according to Git. It runs more risky rules on new files vs existing.



## :heavy_check_mark: Prerequisites

- Bash/Z-Shell*
- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.5 or higher (8.x)
- [Composer](https://getcomposer.org/)

:bulb: If you're on MacOS and have [Homebrew](https://brew.sh/), PHP and Composer will install with `brew install composer`. 

:bulb: [Xdebug](https://xdebug.org/) is only needed if you want to work on this repository itself (it generates the test coverage report). Consumers of the package don't need it. See **Development** below.

\* These scripts have only been tested on MacOS, but will likely work in any Bash/Z-Shell environment.



## :building_construction: Installation



### As part of your composer-enabled project

In the project you wish to use the phpcs standard and phpcsfixer rules in your project, require this library

```shell
composer require --dev christianjbrown/code-quality-scripts
```

:bulb: This package pulls in [`dealerdirect/phpcodesniffer-composer-installer`](https://github.com/PHPCSStandards/composer-installer), which registers the bundled slevomat sniffs with PHP_CodeSniffer automatically. Composer will ask to trust that plugin the first time; allow it (or add it to `config.allow-plugins` in your `composer.json`):

```json
{
    "config": {
        "allow-plugins": {
            "dealerdirect/phpcodesniffer-composer-installer": true
        }
    }
}
```



#### Adding composer scripts

Consider using the rules and standards through composer scripts

```json
{
    "scripts": {
        "check-style": [
          "clear && ./bin/php-cs ./src ./tests"
        ],
        "check-style-diff": [
          "clear && ./bin/php-cs-diff"
        ],
        "fix-style": [
          "clear && ./bin/php-cs-fix ./src ./tests"
        ],
        "fix-style-diff": [
          "clear && ./bin/php-cs-fix-diff"
        ]
    },
    "config": {
        "bin-dir": "bin"
    }
}

```

Alternatively, you can use the original PHP Code Sniffer `phpcs` and PHP CS Fixer `php-cs-fixer` commands with the rules and standards provided:

```json
{
    "scripts": {
        "check-style": [
            "clear && ./bin/phpcs --standard=vendor/christianjbrown/code-quality-scripts/config/standard.xml ./src ./tests"
        ],
         "fix-style": [
            "clear && ./bin/php-cs-fixer fix --config=vendor/christianjbrown/code-quality-scripts/config/Risky.php ./src ./tests"
        ]
    },
    "config": {
        "bin-dir": "bin"
    }
}

```

In either case, you can run `composer check-style` or `composer fix-style` directly within your project.



### Standalone installation

If you want to use these tools in a standalone way, not specific to a project:

* `git clone` this repository to a directory of choice on your local machine.
* change to the install directory and run `composer update`
* See **Setting up global commands** below



### Setting up global commands

If you want to use the shell scripts anywhere

* Edit your `~/.bash_profile` or `~/.zshrc` and update or set the `PATH` variable to include the `./src` directory within the directory you cloned this repository to. e.g.: `export PATH="[this-directory]/src:$PATH"`.
* Optionally you may also want to set the following with `export`
  * `PHP_CS_STANDARD` - the default standard for `php-cs`, if you don't set this, it will default to `./config/standard.xml`
  * `PHP_CS_FIX_CONFIG` - the default rule set for `php-cs-fix`, if you don't set this, it will default to `./config/Risky.php`
  * `PHP_CS_FIX_CONFIG_SAFE` - the default rule set for `php-cs-fix-diff` to run on existing files, if you don't set this, it will currently default to `./config/Safe.php`
  * `PHP_CS_FIX_CONFIG_RISKY` - the default rule set for `php-cs-fix-diff` to run on new and untracked files, if you don't set this, it will default to `./config/Risky.php`
  * `PHP_CS` - the PHP Code Sniffer binary used by `php-cs` and `php-cs-diff`, if you don't set this, it is resolved automatically (installed dependency, the consumer's `vendor/bin`, or a local `bin/`)
  * `PHP_CS_FIXER` - the PHP CS Fixer binary used by `php-cs-fix` and `php-cs-fix-diff`, resolved the same way if you don't set it
* Run `source ~/.bash_profile` or `source ~/.zshrc` to reload it.



## :computer: Usage

:bulb: If you don't add these commands to your `PATH`, you'll need to run them from the `./src` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to `./vendor/bin`).


### Using `php-cs`

```shell
php-cs [<filename or directory>]
```

where

* `filename or directory` , the filename or directory of files you want to check, defaults to the current directory.



### Using `php-cs-diff`

```shell
php-cs-diff [since-ref]
```

where

* `since-ref` , is the git commit reference to compare against, defaults to `HEAD`.



### Using `php-cs-fix`

```shell
php-cs-fix [<filename or directory>]
```

where

* `filename or directory` , the filename or directory of files you want to fix, defaults to the current directory.

:warning: This will default to the **risky** rule set. See **Setting up global commands** on how to override this.



### Using `php-cs-fix-diff`


```shell
php-cs-fix-diff [since-ref]
```

where

* `since-ref` , is the git commit reference to compare against, defaults to `HEAD`.

:warning: This will currently default to the **risky** rule set for new files, and **safe** rule set for existing files. See **Setting up global commands** on how to override this.




## PHP CodeSniffer Standard

The PHPCS standards can be found in `./config` directory.

The only standard in there right now is `./config/standard.xml` (name `ChristianBrown`), a set of rules for **PHP_CodeSniffer 4** based on various PSR, PEAR, Squiz, Zend and Generic standards plus [slevomat/coding-standard](https://github.com/slevomat/coding-standard) sniffs, with a few more sprinkled in for extra goodness. (Earlier versions built on `escapestudios/symfony2-coding-standard`, which is PHP_CodeSniffer 3 only; its formatting rules are now handled by the PHP CS Fixer `@Symfony`/`@PhpCsFixer` rule sets and its lint rules by slevomat.)

`config/ChristianBrown/ruleset.xml` is a thin shim that re-exports `standard.xml` under a named directory, so once `config/` is on phpcs `installed_paths` the standard is discoverable as `--standard=ChristianBrown`.

The `php-cs-fix` / `php-cs-fix-diff` wrappers run **PHP CS Fixer and then `phpcbf`** (with this standard), because some slevomat violations (e.g. useless phpdoc `@param`/`@return` annotations) are auto-fixable only by `phpcbf`, not PHP CS Fixer.



## PHP CS Fixer Rule sets

The rule sets can be found in the `./config` directory.

They can be generated by a handy user interface provided at https://mlocati.github.io/php-cs-fixer-configurator/

### :warning: Risky

Rule set: `Risky.php`

A set of risky non-backward compatible rules based on various PSR, Symfony, PEAR and Generic standards, with a few more sprinkled in for extra goodness. If you use this, you will want to have very good test coverage, but at the end you will have some very neat code.

### :construction_worker: Safe

Rule set: `Safe.php`

A set of safer backward-compatible rules based on various PSR, Symfony, PEAR and Generic standards, with a few more sprinkled in for extra goodness. This is better for running on existing legacy codebases which you may to be too risky to make too many changes to in one go.




## PHPStan

The package also ships a shared **PHPStan** config at `config/phpstan.neon`, so the analysis policy
(the level, and a custom rule) lives here once instead of being duplicated in every project.

It sets:

* `level: max`
* a custom rule, **`RequireStaticPrivateMethodRule`** — a `private` method that never uses `$this` is
  stateless and should be `static`. Constructors/destructors, already-`static` methods, and PHPUnit
  `TestCase` classes are exempt.

### Consuming it

`phpstan/phpstan` is a dev dependency of your project (add it if you don't already have it):

```shell
composer require --dev phpstan/phpstan
```

Then `include` the shared config from your project's `phpstan.neon.dist` and add **only your project's
`paths`**:

```neon
includes:
    - vendor/christianjbrown/code-quality-scripts/config/phpstan.neon

parameters:
    paths:
        - src
        - tests
```

Add a `stan` composer script to run it (uses the `bin-dir` from **Adding composer scripts** above):

```json
{
    "scripts": {
        "stan": [
            "clear && ./bin/phpstan analyse"
        ]
    }
}
```

:bulb: **Why `paths` (and `tmpDir`) stay in your project and not here:** PHPStan resolves relative paths
against the directory of the file that *declares* them. `paths` written in this package's
`config/phpstan.neon` would resolve against `vendor/christianjbrown/code-quality-scripts/config/`,
not your project root — so they must live in your own `phpstan.neon.dist`. The level and rules, being
path-independent, are shared. Change the level or add a rule here once and every consumer picks it up on
`composer update`.

:bulb: Array parameters (e.g. `ignoreErrors`) set in **both** files are merged/appended by default; to
override the shared value instead, prefix the key in your own file with `!` (e.g. `ignoreErrors!:`).



## :hammer_and_wrench: Development

This section is for working on the package itself, not for consuming it.

The repository **dogfoods its own standard** — `composer check-style` runs the `ChristianBrown`
standard over `config` and `tests`, and CI fails on any violation, so any PHP added here must
already conform.

Requires PHP **8.5** and **Xdebug** (Xdebug is only needed to generate the test coverage report).
Both `bin/` and `vendor/` are gitignored and Composer-installed, so run `composer install` first —
its `post-install-cmd` runs `setup-standards`, which registers the phpcs standard so `./src/php-cs`
works.

| Task | Command |
| --- | --- |
| Run tests + coverage (opens HTML report) | `composer test` |
| Static analysis (PHPStan level max) | `composer stan` |
| Check code style | `composer check-style` |
| Auto-fix code style | `composer fix-style` |
| Check / fix style on git diff only | `composer check-style-diff` / `composer fix-style-diff` |

Recommended order before finishing a change: `composer fix-style` → `composer check-style` →
`composer stan` → `composer test`. CI (`.github/workflows/ci.yml`) runs the same gates on push/PR
to `main`.



## :page_facing_up: License

Released under the [MIT License](LICENSE).



