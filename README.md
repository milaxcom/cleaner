# Cleaner

[![Latest Stable Version](https://poser.pugx.org/misterpaladin/cleaner/v/stable)](https://packagist.org/packages/misterpaladin/cleaner) [![Total Downloads](https://poser.pugx.org/misterpaladin/cleaner/downloads)](https://packagist.org/packages/misterpaladin/cleaner) [![Latest Unstable Version](https://poser.pugx.org/misterpaladin/cleaner/v/unstable)](https://packagist.org/packages/misterpaladin/cleaner) [![License](https://poser.pugx.org/misterpaladin/cleaner/license)](https://packagist.org/packages/misterpaladin/cleaner)

Garbage cleaner package for Laravel

## Usage

Install package:

```bash
$ composer require misterpaladin/cleaner
```

Publish config file to your project:

```bash
$ php artisan vendor:publish --tag=cleaner
```

Add `MisterPaladin\Cleaner\CleanerServiceProvider` to your `config/app.php` providers array

### Configuration

`/config/cleaner.php` file contents:

```php
return [
    
    // Delete file after 3 days and 12 hours
    [
        'path' => 'path/to/file.ext',
        'expires' => [
            'days' => 3,
            'hours' => 12,
        ],
    ],
    
    // Delete directory after 30 minutes
    [
        'path' => 'path/to/directory',
        'expires' => [
            'minutes' => 10,
        ],
    ],
    
    // Delete directory contents after 1 week
    [
        'path' => 'path/to/directory/*',
        'expires' => [
            'weeks' => 1,
        ],
    ]
    
    // Define a path array
    [
        'path' => [
            'path/to/file.ext',
            'path/to/directory',
            'path/to/directory/*',
        ],
        'expires' => [
            'weeks' => 1,
        ],
    ]
    
];
```

The `expires` option may accept:
- seconds
- minutes
- hours
- days
- weeks
- months
- years

### Callbaks

```php
[
    'path' => 'path/to/file.ext',
    'expires' => [
        'days' => 3,
        'hours' => 12,
    ],
    'before' => function ($path) {
        // Execute before deleting the file
    },
    'after' => function ($path) {
        // Execute after deleting the file
    },
],
```

### Execution

Cleaner runs every minute (if you set it up: https://laravel.com/docs/5.4/scheduling#introduction)

Manual run: `php artisan cleaner:run`