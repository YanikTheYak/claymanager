# Clay Manager package for Laravel 4.3

## Installation

```bash
"cyberduck/claymanager": "dev-master"
```

After adding the key, run composer update from the command line to install the package:

```bash
composer update
```

Add the service provider to the `providers` array in your `app/config/app.php` file.

```php
'Clay\Manager\ManagerServiceProvider'
```

Add the alias to the `aliases` array

```php
'Manager' => 'Clay\Manager\Facades\Manager',
```
