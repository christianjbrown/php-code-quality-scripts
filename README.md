# :wrench: PHP Coding Standards Fixer Wrapper

PHP code can be ..messy, luckily, there's lots of tools to fix code automagically, including [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer). That tool allows you to define rule sets of your own, building on top of existing rule sets and rules.

**This repository / project provides:**

1. Some common **PHP CS Fixer rule sets** I prefer to use. 

2. Two helpful **wrapper scripts**:

   `php-cs-fix` is a very simple wrapper around PHP-CS-Fixer's own binary, to simplify the command line and load the right config.

   `php-cs-fix-diff` runs PHP CS Fixer on files changed according to Git. It will fix:

   - all modified (`M`) and renamed (`R`) files, and runs the non-risky rule sets
   - all added (`A`), copied (`C`) files or untracked files (`?`), and runs the risky rule sets

   

## :heavy_check_mark: Prerequisites

- Bash/Z-Shell*
- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.2
- [Composer](https://getcomposer.org/)

:bulb: If you're on macOS and have [HomeBrew](https://brew.sh/), PHP and Composer will install with `brew install composer`. If you run `php -v` and there are errors, you may also need to `brew install gd`

\* These scripts have only been tested on macOS, but will likely work in any Bash/Z-Shell environment.



## :building_construction: Installation



### As part of your composer-enabled PHP project

In the project you wish to use these `php-cs-fixer` rules, require this library

```shell
composer require --dev christianjbrown/php-cs-fix-erwrapper dev-main
```



#### Adding a composer script

Consider adding it as a composer script

```json
{
    ...
    "scripts": {
        "fix-risky": [
            "./bin/php-cs-fixer fix ./src --config=vendor/christianjbrown/php-cs-fixer-rule-sets/rule-sets/risky.php"
        ]
    },
    ...
}

```

using a rule set to suit you.

Alternatively, simplify with `php-cs-fix`

```json
{
     ...
    "scripts": {
         "fix": [
            "./bin/php-cs-fix ./src"
        ]
    },
    ...
}

```

either way, so that you can just run `composer fix`.

Consider the same for `php-cs-fix-diff`

```json
{
     ...
    "scripts": {
         "fix-diff": [
            "./bin/php-cs-fix-diff"
        ]
    },
    ...
}

```

so that you can just run `composer fix-diff`.



### Standalone installation

If you want to use these tools in a standalone way, not specific to a project:

* `git clone` his repository to a directory of choice on your local machine.
* change to the install directory and run `composer update`
* See **Setting up global commands** below



### Setting up global commands

If you want to use `php-cs-fixer` and the wrapper shell scripts `php-cs-fix` and `php-cs-fix-diff`  anywhere 

* Edit your `~/.bash_profile` or `~/.zshrc` and update or set the `PATH` variable to include the `bin` and `vendor/bin` directories within the directory you cloned this repository to. e.g.: `export PATH="[this-directory]/bin:$PATH"`.
* Optionally you may also want to set the following with `export` 
  * `PHP_CS_FIX_CONFIG` - the default rule set for `php-cs-fix`, if you don't set this, it will default to `risky.php`
  * `PHP_CS_FIX_CONFIG_SAFE`   - the default rule set for `php-cs-fix-diff` to run on existing files, if you don't set this, it will currently default to `risky.php` (despite being called "safe")
  * `PHP_CS_FIX_CONFIG_RISKY`  - the default rule set for `php-cs-fix-diff` to run on new and untracked files, if you don't set this, it will default to `risky.php`
* Run `source ~/.bash_profile` or `source ~/.zshrc` to reload it.



## :computer: Usage

### Using `php-cs-fixer` directly

See [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)'s own documentation, but to use these rule sets when fixing files, it's:

```shell
php-cs-fixer fix [files] --config vendor/christianjbrown/php-cs-fixer-rule-sets/[rule-set].php
```

where
* `files` is a list of files or directories to fix.
* `rule-set` is the name of the rule set to use available in the `vendor/christianjbrown/php-cs-fixer-rule-sets` directory.

#### Example

```shell
./bin/php-cs-fixer fix ./messy-code --config vendor/christianjbrown/php-cs-fixer-rule-sets/risky.php
```



### Using `php-cs-fix`

```shell
php-cs-fix [<filename or directory>]
```

where

* `filename or directory` , the filename or directory of files you want to fix, defaults to the current directory.

:warning: This will default to the **risky** rule set. See **Setting up global commands** on how to override this.

:bulb: If you don't add this command to your `PATH`, you'll need to run it from the `bin` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to`vendor/bin`).



### Using `php-cs-fix-diff`


```shell
php-cs-fix-diff [since-ref]
```

where

* `since-ref` , is the remote commit reference to compare to, defaults to `HEAD`.

:warning: This will currently default to the **risky** rule set for both new and existing files. See **Setting up global commands** on how to override this.

:bulb: If you don't add this command to your `PATH`, you'll need to run it from the `bin` directory in this repository, or if including this in a composer-enabled PHP project, the configured `bin-dir` (defaults to`vendor/bin`).



## Rule sets

The rule sets can be found in the `rule-sets` directory.

They can be generated by a handy user interface provided at https://mlocati.github.io/php-cs-fixer-configurator/

### :warning: Risky

Rule set: `risky.php`

A set of risky non-backward compatible rules based on various PSR, Symfony, Doctrine and PER standards, with a few more sprinkled in for extra goodness. If you use this, you will want to have very good test coverage, but at the end you will have some very neat code.

### :construction_worker: Safe

Rule set: `safe.php`

:x:  This is **currently removed**, as I only use PHP personally, and not in a company environment where I'd have to be more careful. Saves me maintaining something I no longer need.



## :family: Related

Also see [christianjbrown/phpcs-wrapper](https://github.com/christianjbrown/phpcs-wrapper) for a wrapper around [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).





