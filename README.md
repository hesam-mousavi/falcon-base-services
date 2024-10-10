# Falcon Base Services

Many WordPress developers long for features like Eloquent, Blade, Service Container, and Service Provider to help them build powerful plugins. Falcon is here to change the game and bring these capabilities to your fingertips.

**Please note:** This plugin provides a series of services and is not intended to be used as a base for creating new plugins.

## Features
- **Powerful Service Container and Service Provider**
- **Query Builder**
- **Eloquent**
- **Template Engine (Blade, Twig)**
- **Logger (Monolog)**
- **Email (PHPMailer)**
- **Laravel Validation**
- **Request Handling**
- **Scheduler**
- **Environment Management**
- **Symfony Var-Dumper (dd, dump)**
- **Carbon**
- **Additional Helpers** to develop your plugin fast.

**Minimum PHP version: 8.2**

## Installation

1. **Create Directory:** In the `wp-content` folder, if the `mu-plugins` folder does not exist, create it. Place the `falcon-base-services` folder inside it.
2. **Create Loader File:** In the root of the `mu-plugins` folder, create a PHP file with a name of your choice and add the following code:
    ```php
    <?php
    require 'falcon-base-services/falcon-base-services.php';
    ```
   Note that the contents of the `mu-plugins` folder do not need to be activated in the WordPress admin and are executed before all other plugins. Also, WordPress does not scan the folders inside `mu-plugins` unless explicitly instructed.

3. **Install Dependencies:** Open the terminal in the `falcon-base-services` folder and run the following command:
    ```sh
    composer install
    ```
   If you haven't installed Composer, you can download and install it from [this link](https://getcomposer.org/).

The plugin is now ready to use. Let’s explore its features and how to use them.

## Maintenance Mode
If you need to put the site in maintenance mode, simply rename the `maintenance.example.php` file in the `storage` folder to `maintenance.php`. You can also edit the contents of the file as needed.

## Environment Variables (ENV)
You can set your variables in the `.env` file and use them anywhere in your code like this:
```php
$_ENV['item'];
```

## Service Container - Service Provider
The plugin uses a powerful service container with autowiring capabilities.
- **Singleton Services:** Register a singleton service using:
    ```php
    FALCON_CONTAINER->singleton(Test::class);
    // or
    FALCON_CONTAINER->singleton('test', Test::class);
    ```

- **Non-Singleton Services:** Register a non-singleton service using:
    ```php
    FALCON_CONTAINER->bind(Test::class);
    // or
    FALCON_CONTAINER->bind('test', Test::class);
    ```

- **Using Closures:** You can also use closures:
    ```php
    FALCON_CONTAINER->bind('test', function() { return Test::class; });
    ```

- **Using the Services:** Use the `get` method to retrieve the services:
    ```php
    FALCON_CONTAINER->get('test');
    FALCON_CONTAINER->get(Test::class);
    ```

- **Resolving Methods:** Resolve a method from a class using:
    ```php
    FALCON_CONTAINER->getMethod(LoginController::class, 'login');
    ```
  This will automatically resolve any dependencies required by both the class and the method.

To create a service provider, create a class in the `app/providers` folder and extend the `ServiceProvider` class. Use the `register` and `boot` methods as needed. Then, add the provider’s address in the `providers.php` file located in the `bootstrap` folder.

## Eloquent, QueryBuilder
All default WordPress tables are available as models in the `app/Model` folder. WooCommerce tables will be added soon. You can use both the powerful Query Builder and Eloquent to interact with these tables.
- **Eloquent:** <br>
```php
(new \FalconBaseServices\Model\Post())->published()->with('author')->get();
  ```
  
- **Query Builder:** <br>
```php
falconDB()::table('wp_posts')
    ->where('post_status', 'publish')
    ->leftJoin('wp_users', 'wp_posts.post_author', '=', 'wp_users.ID')
    ->select('wp_posts.*', 'wp_users.user_nicename')
    ->get();
```

If you want to use a new table as a model, create its class by extending the `FalconBaseServices\Model\BaseModel` class. If the table does not use the default prefix, set `$with_prefix` to false:
```php
protected $with_prefix = false;
```

The rules and usage of models and Query Builder/Eloquent are exactly like the Laravel documentation.

Happy coding! 🚀
