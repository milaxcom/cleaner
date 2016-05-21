# Cleaner

Garbage cleaner package for Laravel

## Usage

Install package:

```bash
$ composer require milax/cleaner
```

Publish config file to your project:

```bash
$ php artisan vendor:publish --tag=cleaner
```

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