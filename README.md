# PHP Coding Standards Fixer Rule Sets 

PHP code can be messy, luckily, there's lots of tools to fix code automagically, including `php-cs-fixer` by `friendsofphp`.

That tool allows you to define rule sets of your own, building on top of existing rule sets and rules. This repository holds some common `php-cs-fixer` rule sets I prefer to use. They're in their own repository, so that I can pull them in to multiple projects via composer.

I also provide `phpcsfix` shell wrapper script to run the long `php-cs-fixer` command without having to remember all the parameters, and `phpcsfix-diff` to run `phpcsfix` on all files changed according to Git.

## Installation

In the project you wish to use these `php-cs-fixer` rules, require` php-cs-fixer` and this library.

```shell
composer require --dev friendsofphp/php-cs-fixer
composer require --dev christianjbrown/php-cs-fixer-rule-sets
```

Optionally, if you want to use the wrapper shell scripts `phpcsfix` and `phpcsfix-diff` edit your `~/.bash_profile` or `~/.zshrc` and update or set the `PATH` variable to include the `bin` directory within the directory you cloned this repository to. e.g.: `export PATH="/path/to/install/bin:$PATH"`. Run `source ~/.bash_profile` or `source ~/.zshrc` to reload it.

## Usage


### Using `php-cs-fixer` directly

See `php-cs-fixer`'s own documentation, but to use these rule sets when fixing files, it's something like -

```shell
./vendor/bin/php-cs-fixer fix [files] --config vendor/christianjbrown/php-cs-fixer-rule-sets/[rule-set].php
```

where
* `files` is a list of files or directories to fix.
* `rule-set` is the name of the rule set to use available in the `vendor/christianjbrown/php-cs-fixer-rule-sets` directory.

### Using `phpcsfix`

```shell
phpcsfix [files]
```

(If you don't add these to your PATH, you'll need to run them from the `bin` directory in this repository, or if including this in a project, from the `vendor/christianjbrown/php-cs-fixer-rule-sets/bin` directory)

### Using `phpcsfix-diff`


```shell
phpcsfix-diff
```

(If you don't add these to your PATH, you'll need to run them from the `bin` directory in this repository, or if including this in a project, from the `vendor/christianjbrown/php-cs-fixer-rule-sets/bin` directory)


### Example

```shell
./vendor/bin/php-cs-fixer fix ./messy-code --config vendor/christianjbrown/php-cs-fixer-rule-sets/risky.php
```



### Adding a composer script

Consider adding it as a composer script

```json
{
    "scripts": {
        "fix": [
            "./vendor/bin/php-cs-fixer fix ./src --config=vendor/christianjbrown/php-cs-fixer-rule-sets/risky.php"
        ]
    },
    "require-dev": {
        "php": "~8.2",
        "christianjbrown/php-cs-fixer-rule-sets": "dev-main",
        "friendsofphp/php-cs-fixer": "dev-master"
    }
}

```

so that you can just run `composer fix`



## Rule sets



### Risky

Rule set: `risky` / `risky.php`

A set of risky non-backward compatible rules based on various PSR, Symfony, Doctrine and PER standards, with a few more sprinkled in for extra goodness. If you use this, you will want to have very good test coverage, but at the end you will have some very neat code.



### Other

I've deleted non-risky rule sets, as I only use PHP personally, and not in a company environment where I'd have to be more careful. Save me maintaining something I no longer need.



