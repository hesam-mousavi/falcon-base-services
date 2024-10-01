# About
This plugin provide some most used services like:
- laravel QueryBuilder and Eloquent
- Template Engin(blade , twig)
- Email(phpMailer)
- Request
- Validation
- Logger(monolog)
- Login and logout with and without API
- ENV
- Scheduler
- Symfony var-dumper(dd, dump)
- Carbon
<br>
and some Helpers to develop your plugin fast.

___
### #PHP:

php >=8.2

___
### #Install

in **wp-content** directory of wp project, if **mu-plugins** folder not exist, create it and copy this project to this folder.
<br>then create php file with name that you want in root of mu-plugins and copy below code to this file and save it. 

~~~php
<?php
require 'wp-base-services/wp-base-services.php';
~~~

got to **wp-base-services** folder and open terminal and run this code:
~~~
composer install
~~~

if composer not installed in yor system, download it from [composer](https://getcomposer.org/download/) and install.
then run code.

___
### #Maintenance

if site need maintenance mode, rename **storage/maintenance1.php** file to **storage/maintenance.php** and write to it want you need.

___
### #env
with **$_ENV['ITEM']** can access the ITEM key in .env file.
- if you want to use api for login, please change **JWT_SECRET_KEY** key

___
### #BASE_CONTAINER
you can access **BASE_CONTAINER** globally for manage DI(Dependency Injection).
- if you need create singleton object with DI, use **get()** method.

~~~php
$obj = BASE_CONTAINER->get(Template::class);
~~~

- if you need create object and run one method of this class with DI, use **call()** method.

~~~php
$res = BASE_CONTAINER->call(['BaseService\Http\Controllers\Public\LoginController', 'login']);
~~~

___
### #LOGGER
**LOGGER** use **monolog** package for handle logs and you can access LOGGER globally to manage LoggerInterface methods.<br>
- if ou want to use other packages, just add it with composer and change <code>bootstrap/config.php</code>

<p>Example:</p>

~~~php
try{
    action();
}catch(Exception $e){
    LOGGER->alert(
                'unAuthorization request!',
                [
                    'data' => [
                        'request' => $this->request,
                        'user' => CurrentUser::summaryProfile(),
                        'message' => $e->getMessage(),
                    ],
                ],
            );
}

~~~

- LOGGER save max 30 files and each day logs save to one file.
- if you want change count of files, change **LOGGER_MAX_FILES** in .env file.
- if yot need <code>ProcessIdProcessor</code> or <code>GitProcessor</code> or <code>MemoryUsageProcessor</code>, uncomment related lines in <code>app/Services/logger/Monolog.php</code>
___
### #TEMPLATE
for use **Blade** template engin, you can access it globally with **TEMPLATE**.
- if you want to use **twig**, add it with composer and then change <code>bootstrap/config.php</code>
<p>Example:</p>

~~~php
TEMPLATE->setViewDir(__DIR__.'/views')
     ->setView("tickets") // tickets.blade.php or tickets.twig
     ->share(['name' => $this->getName()])
     ->render();
~~~

___
### #Email
if you want to send email, use <code>\BaseService\Helper\Send::email(to, subject, content, from = null, bcc = null);</code>
email service use [PHPMailer](https://github.com/PHPMailer/PHPMailer/).
<br> if you want to use another package, add it with composer and change <code>bootstrap/config.php</code>
<p>email use some env variable like:

- EMAIL_HOST
- INFO_EMAIL_PASS //if use info@...
- and ....
___
### #SMS
if you want to send sms, use <code>\BaseService\Helper\Send::sms(receptor, message);</code>
<p>only one option exist for sms for now, iranian KavehNegar</p>

___
### #CurrentUser
use <code>\BaseService\Services\CurrentUser</code> for access some data from current user that logged_in.

___
### #Helper
see <code>app/Helper</code> directory. its contain some usefully helpers.

___
### #Model
all wp standard tables model created in <code>app/Model</code>.
<p>if you want to use Model, just do it like laravel eloquent!!!</p>
Example:

~~~php
(new \BaseService\Model\Post())->published()->with('author')->get()
~~~

<p>you want use queryBuilder? it's simple:</p>

~~~php
use Illuminate\Database\Capsule\Manager as QBuilder;

QBuilder::table('wp_posts')->get();
~~~
___
### #Login | Logout
some usefully route created for login/logout with ajax or api. please see <code>app/Routes.php</code>

___
### #Scheduler
do you use cronjob? No more need to use boring ways in cPanel.<br>
All you need from now on is that create just one cron in cpanel that check every one minute <code>app/scheduler.php</code> file.<br>
now with <code>[peppeocchi/php-cron-scheduler](https://github.com/peppeocchi/php-cron-scheduler)</code> package in <code>app/scheduler.php</code> file, write your jobs!!!

___
### Other packages
<code>[Carbon](https://carbon.nesbot.com/docs/)</code>, <code>[dd(), dump()](https://symfony.com/doc/current/components/var_dumper.html)</code> accessed globally.