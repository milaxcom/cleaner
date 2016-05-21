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

Open and edit `/config/cleaner.php`:

```php
return [
    [
        'path' => 'path/to/file.ext',
        'expires' => [
            'days' => 3,
            'hours' => 12,
        ],
    ],
    [
        'path' => 'path/to/directory',
        'expires' => [
            'minutes' => 10,
        ],
    ],
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
- months years