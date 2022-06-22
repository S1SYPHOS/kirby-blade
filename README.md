# Kirby Blade

[![Source](https://img.shields.io/badge/source-lukasleitsch/kirby--blade-blue?style=flat-square)](https://github.com/lukasleitsch/kirby-blade)
[![Download](https://img.shields.io/packagist/dt/leitsch/kirby-blade?style=flat-square)](https://github.com/lukasleitsch/kirby-blade)
[![Open Issues](https://img.shields.io/github/issues-raw/lukasleitsch/kirby-blade?style=flat-square)](https://github.com/lukasleitsch/kirby-blade)
[![Last Commit](https://img.shields.io/github/last-commit/lukasleitsch/kirby-blade?style=flat-square)](https://github.com/lukasleitsch/kirby-blade)
[![Release](https://img.shields.io/github/v/release/lukasleitsch/kirby-blade?style=flat-square)](https://github.com/lukasleitsch/kirby-blade)
[![License](https://img.shields.io/github/license/lukasleitsch/kirby-blade?style=flat-square)](https://github.com/lukasleitsch/kirby-blade)

Kirby Blade use Laravel `illuminate/view` 9.x package and compatible with Kirby 3.

This package enables [Laravel Blade](https://laravel.com/docs/9.x/blade) for your own Kirby applications.

## Installation

```ssh
composer require leitsch/kirby-blade
```

Laravel and Kirby both define the `e()` helper function, but they do vastly different things. In Kirby, `e()` is basically just a shortcut for `echo $condition ? $a : $b;`. In Laravel, this function escapes HTML characters in a string. To avoid problems for existing projects and third-party plugins, you should add the lines below to your `index.php` file before requiring `autoload.php`:

```php

define('KIRBY_HELPER_E', false);

/**
 * Smart version of echo with an if condition as first argument. This function
 * must be defined in `index.php` to avoid it being overridden by Laravel, because
 * of the order in which Composer laods the helper functions of Laravel and Kirby.
 *
 * @param mixed $condition
 * @param mixed $value The string to be echoed if the condition is true
 * @param mixed $alternative An alternative string which should be echoed when the condition is false
 */
function e($condition, $value, $alternative = null)
{
    echo $condition ? $value : $alternative;
}
```

If you are sure, that Kirby’s `e()` helper is never used in you project, you can simply Kirby’s function by adding a single line of code instead:

```php
define('KIRBY_HELPER_E', false);
```

## What is Blade?

According to Laravel Blade documentation is:

> Blade is the simple, yet powerful templating engine that is included with Laravel. Unlike some PHP templating engines, Blade does not restrict you from using plain PHP code in your templates. In fact, all Blade templates are compiled into plain PHP code and cached until they are modified, meaning Blade adds essentially zero overhead to your application. Blade template files use the .blade.php file extension.

## Usage

You can use the power of Blade like [Layouts](https://laravel.com/docs/9.x/blade#building-layouts), [Forms](https://laravel.com/docs/9.x/blade#forms), [Sub-Views](https://laravel.com/docs/9.x/blade#including-subviews), [Components](https://laravel.com/docs/9.x/blade#components), [Directives](https://laravel.com/docs/9.x/blade#blade-directives) and your custom if statements.

All the documentation about Laravel Blade is in the [official documentation](https://laravel.com/docs/9.x/blade).

## Options

The default values of the package are:

| Option                       | Default | Values | Description |
|:-----------------------------|:---|:---|:---|
| leitsch.blade.templates      | site/templates | (string) | Location of the templates |
| leitsch.blade.views          | site/cache/views | (string) | Location of the views cached |
| leitsch.blade.directives     | [] | (array) | Array with the custom directives |
| leitsch.blade.ifs            | [] | (array) | Array with the custom if statements |

All the values can be updated in the `config.php` file.

### Templates

Default templates folder is `site/templates` directory or wherever you define your `templates` directory, but you can change this easily:

```php
'leitsch.blade.templates' => '/theme/default/templates',
```

### Views

All the views generated are stored in `site/cache/views` directory or wherever you define your `cache` directory, but you can change this easily:

```php
'leitsch.blade.views' => '/site/storage/views',
```

### Directives

By default Kirby Blade comes with following directives:

```php
@asset($path)
@csrf()
@css($path)
@dump($variable)
@e($condition, $value, $alternative)
@get($key, $default)
@gist($url)
@go($url, $code)
@h($string, $keepTags)
@html($string, $keepTags)
@js($path)
@image($path, $attr) // @image('forrest.jpg', 'url')
@kirbytag($type, $value, $attr)
@kirbytags($text, $data)
@kirbytext($text, $data)
@kirbytextinline($text)
@kt($text)
@markdown($text)
@option($key, $default)
@page($key, $attr) // @page('blog', 'title')
@param($key, $fallback)
@site($attr) // @site(title')
@size($value)
@smartypants($text)
@snippet($name, $data)
@svg($file)
@t($key, $fallback)
@tc($key, $count)
@tt($key, $fallback, $replace, $locale)
@u($path, $options)
@url($path, $options)
@video($url, $options, $attr)
@vimeo($url, $options, $attr)
@widont($string)
@youtube($url, $options, $attr)
```

But you can create your own:

```php
'leitsch.blade.directives' => [
    'greeting' => function ($text)
    {
        return "<?php echo 'Hello: ' . $text ?>";
    },
],
```

Kirby Helpers Documentation:

https://getkirby.com/docs/reference/templates/helpers

### If Statements

Like directives, you can create your own if statements:

```php
'leitsch.blade.ifs' => [
    'logged' => function ()
    {
        return !!kirby()->user();
    },
],
```

After declaration you can use it like:

```php
@logged
    Welcome back {{ $kirby->user()->name() }}
@else
    Please Log In
@endlogged
```

## Credits
- [Kirby Blade](https://github.com/afbora/kirby-blade) by [@afbora](https://github.com/afbora)
- [Torch](https://github.com/mattstauffer/Torch) by [@mattstauffer](https://github.com/mattstauffer)
- [Kirby Blade Repository](https://github.com/beebmx/kirby-blade) by [@beebmx](https://github.com/beebmx)
