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

Example of `/config/cleaner.php` file:

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