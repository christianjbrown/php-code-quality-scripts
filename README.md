# :wrench: PHP Coding Standards Fixer Rule Sets 

PHP code can be ..messy, luckily, there's lots of tools to fix code automagically, including `php-cs-fixer` by `friendsofphp`.

That tool allows you to define rule sets of your own, building on top of existing rule sets and rules. This repository holds some common `php-cs-fixer` rule sets I prefer to use. They're in their own repository, so that I can pull them in to multiple projects via composer.

There are also two wrapper scripts:

* `php-cs-fix` is a very simple wrapper around PHP-CS-Fixer's own binary, to simplify the command line and load the right config. If a directory is provided, it will process the entire directory, ignoring any Git status.

* `php-cs-fix-diff` runs PHP-CS-Fixer on files changed according to Git. It will loop through
   - all modified (`M`) and renamed (`R`) files, and runs the non-risky rule sets
   - all added (`A`), copied (`C`) files or untracked files (`?`), and runs the risky rule sets

## :heavy_check_mark: Prerequisites

- Bash/Z-Shell
- Git
- PHP 8.2
- Composer
  

## :building_construction: Installation



### As part of your composer-enabled PHP project

In the project you wish to use these `php-cs-fixer` rules, require this library

```shell
composer require --dev christianjbrown/php-cs-fixer-rule-sets dev-main
```



#### Adding a composer script

Consider adding it as a composer script

```json
{
    ...
    "scripts": {
        "fix-risky": [
            "./vendor/bin/php-cs-fixer fix ./src --config=vendor/christianjbrown/php-cs-fixer-rule-sets/rule-sets/risky.php"
        ]
    },
    ...
}

```

or simplify with `php-cs-fix`

```json
{
     ...
    "scripts": {
         "fix": [
            "./vendor/bin/php-cs-fix ./src"
        ]
    },
    ...
}

```

so that you can just run `composer fix`.

Consider the same for `php-cs-fix-diff`

```json
{
     ...
    "scripts": {
         "fix-diff": [
            "./vendor/bin/php-cs-fix-diff"
        ]
    },
    ...
}

```

so that you can just run `composer fix-diff`.



### Standalone installation

* `git clone` his repository to a directory of choice on your local machine.
* change to the install directory and run `composer update`
* See **Setting up global commands** below



### Setting up global commands

If you want to use `php-cs-fixer` and the wrapper shell scripts `php-cs-fix` and `php-cs-fix-diff`  anywhere 

* Edit your `~/.bash_profile` or `~/.zshrc` and update or set the `PATH` variable to include the `bin` and `vendor/bin` directories within the directory you cloned this repository to. e.g.: `export PATH="[this-directory]/bin:[this-directory]/vendor/bin:$PATH"`. 
* Optionally you may also want to set the following with `export` 
  * `PHP_CS_FIX_CONFIG` - the default rule set for `php-cs-fix`, if you don't set this, it will default to `risky.php`
  * `PHP_CS_FIX_CONFIG_SAFE`   - the default rule set for `php-cs-fix-diff` to run on existing files, if you don't set this, it will currently default to `risky.php` (despite being called "safe")
  * `PHP_CS_FIX_CONFIG_RISKY`  - the default rule set for `php-cs-fix-diff` to run on new and untracked files, if you don't set this, it will default to `risky.php`
* Run `source ~/.bash_profile` or `source ~/.zshrc` to reload it.



## :computer: Usage



### Using `php-cs-fixer` directly

See `php-cs-fixer`'s own documentation, but to use these rule sets when fixing files, it's:

```shell
php-cs-fixer fix [files] --config vendor/christianjbrown/php-cs-fixer-rule-sets/[rule-set].php
```

where
* `files` is a list of files or directories to fix.
* `rule-set` is the name of the rule set to use available in the `vendor/christianjbrown/php-cs-fixer-rule-sets` directory.

#### Example

```shell
./vendor/bin/php-cs-fixer fix ./messy-code --config vendor/christianjbrown/php-cs-fixer-rule-sets/risky.php
```



### Using `php-cs-fix`

```shell
php-cs-fix [<filename or directory>]
```

where

* `filename or directory` , the filename or directory of files you want to fix, defaults to the current directory.

:warning: This will default to the **risky** rule set. See **Setting up global commands** on how to override this.

:bulb: If you don't add this command to your `PATH`, you'll need to run it from the `bin` directory in this repository, or if including this in a composer-enabled PHP project, from the `vendor/bin` directory of your project.



### Using `php-cs-fix-diff`


```shell
php-cs-fix-diff [since-ref]
```

where

* `since-ref` , is the remote commit reference to compare to, defaults to `HEAD`.

:warning: This will currently default to the **risky** rule set for both new and existing files. See **Setting up global commands** on how to override this.

:bulb: If you don't add this command to your `PATH`, you'll need to run it from the `bin` directory in this repository, or if including this in a composer-enabled PHP project, from the `vendor/bin` directory of your project.





## Rule sets

The rule sets can be found in the `rule-sets` directory.

They can be generated by a handy user interface provided at https://mlocati.github.io/php-cs-fixer-configurator/

### :warning: Risky

Rule set: `risky.php`

A set of risky non-backward compatible rules based on various PSR, Symfony, Doctrine and PER standards, with a few more sprinkled in for extra goodness. If you use this, you will want to have very good test coverage, but at the end you will have some very neat code.

### :construction_worker: Safe

Rule set: `safe.php`

This is currently removed, as I only use PHP personally, and not in a company environment where I'd have to be more careful. Saves me maintaining something I no longer need.







