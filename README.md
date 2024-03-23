# :wrench: PHP Code Quality Scripts

This project

* Installs [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
* A **PHP Code Sniffer standard** I prefer to use, building upon existing PSR, Symfony, Doctrine and PER standards.
* A set of **PHP CS Fixer rule sets** I prefer to use to clean up code to meet PSR, Symfony, Doctrine and PER standards. One riskier set for new files, and a safer set for existing files. 
* Wrapper shell scripts
  * `./src/php-cs` is a very simple wrapper around PHP Code Sniffer's own binary, to simplify the command line and load the right standard.
  * `./src/php-cs-diff` runs PHP Code Sniffer only on files changed according to Git.
  * `./src/php-cs-fix` is a very simple wrapper around PHP-CS-Fixer's own binary, to simplify the command line and load the right ruleset.
  * `./src/php-cs-fix-diff` runs PHP CS Fixer only on files changed according to Git. It runs more risky rules on new files vs existing.



## :heavy_check_mark: Prerequisites

- Bash/Z-Shell*
- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.2
- [Composer](https://getcomposer.org/)

:bulb: If you're on macOS and have [HomeBrew](https://brew.sh/), PHP and Composer will install with `brew install composer`. If you run `php -v` and there are errors, you may also need to `brew install gd`

\* These scripts have only been tested on MacOS, but will likely work in any Bash/Z-Shell environment.



## :building_construction: Installation



### As part of your composer-enabled project

In the project you wish to use the phpcs standard and phpcsfixer rules in your project, require this library

```shell
composer require --dev christianjbrown/php-code-quality-scripts
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
    }
}

```

Alternatively, you can use the original PHP Code Sniffer `phpcs` and PHP CS Fixer `php-cs-fixer` commands with the rules and standards provided:

```json
{
    "scripts": {
        "check-style": [
            "clear && ./bin/phpcs --standard=vendor/christianjbrown/php-code-quality-scripts/config/standard.xml ./src ./tests"
        ],
         "fix-style": [
            "clear && ./bin/php-cs-fixer fix --config=vendor/christianjbrown/php-code-quality-scripts/config/fix-risky.php ./src ./tests"
        ]
    }
}

```

In either case, you can run `composer check-style` or `composer fix-style` directly within your project.



### Standalone installation

If you want to use these tools in a standalone way, not specific to a project:

* `git clone` his repository to a directory of choice on your local machine.
* change to the install directory and run `composer update`
* See **Setting up global commands** below



### Setting up global commands

If you want to use the shell scripts anywhere

* Edit your `~/.bash_profile` or `~/.zshrc` and update or set the `PATH` variable to include the `./src` directory within the directory you cloned this repository to. e.g.: `export PATH="[this-directory]/src:$PATH"`.
* Optionally you may also want to set the following with `export`
  * `PHP_CS_STANDARD` - the default standard for `php-cs`, if you don't set this, it will default to `./config/standard.xml`
  * `PHP_CS_FIX_CONFIG` - the default rule set for `php-cs-fix`, if you don't set this, it will default to `./config/fix-risky.php`
  * `PHP_CS_FIX_CONFIG_SAFE` - the default rule set for `php-cs-fix-diff` to run on existing files, if you don't set this, it will currently default to `./config/fix-safe.php`
  * `PHP_CS_FIX_CONFIG_RISKY` - the default rule set for `php-cs-fix-diff` to run on new and untracked files, if you don't set this, it will default to `./config/fix-risky.php`
* Run `source ~/.bash_profile` or `source ~/.zshrc` to reload it.



## :computer: Usage


### Using `php-cs`

```shell
php-cs [<filename or directory>]
```

where

* `filename or directory` , the filename or directory of files you want to fix, defaults to the current directory.

* :bulb: If you don't add this command to your `PATH`, you'll need to run it from the `./src` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to `./vendor/bin`).



### Using `php-cs-diff`

```shell
php-cs-diff [since-ref]
```

where

* `since-ref` , is the remote commit reference to compare to, defaults to `HEAD`.

* :bulb: If you don't add this command to your `PATH`, you'll need to run it from the `./src` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to `./vendor/bin`).



### Using `php-cs-fix`

```shell
php-cs-fix [<filename or directory>]
```

where

* `filename or directory` , the filename or directory of files you want to fix, defaults to the current directory.

:warning: This will default to the **risky** rule set. See **Setting up global commands** on how to override this.

:bulb: If you don't add this command to your `PATH`, you'll need to run it from the `./src` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to `./vendor/bin`).



### Using `php-cs-fix-diff`


```shell
php-cs-fix-diff [since-ref]
```

where

* `since-ref` , is the remote commit reference to compare to, defaults to `HEAD`.

:warning: This will currently default to the **risky** rule set for new files, and **safe** rule set for existing files. See **Setting up global commands** on how to override this.

:bulb: If you don't add this command to your `PATH`, you'll need to run it from the `./src` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to `./vendor/bin`).




## PHP CodeSniffer Standard

The PHPCS standards can be found in `./config` directory.

The only standard in there right now is `./config/standard.xml` which is a set of rules based on various PSR, Symfony, Doctrine and PER standards, with a few more sprinkled in for extra goodness.



## PHP CS Fixer Rule sets

The rule sets can be found in the `./config` directory.

They can be generated by a handy user interface provided at https://mlocati.github.io/php-cs-fixer-configurator/

### :warning: Risky

Rule set: `fix-risky.php`

A set of risky non-backward compatible rules based on various PSR, Symfony, Doctrine and PER standards, with a few more sprinkled in for extra goodness. If you use this, you will want to have very good test coverage, but at the end you will have some very neat code.

### :construction_worker: Safe

Rule set: `fix-safe.php`

A set of safer backward-compatible rules based on various PSR, Symfony, Doctrine and PER standards, with a few more sprinkled in for extra goodness. This is better for running on existing legacy codebases which you may to be too risky to make too many changes to in one go.





