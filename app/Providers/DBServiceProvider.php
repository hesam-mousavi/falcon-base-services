<?php

namespace FalconBaseServices\Providers;

use HesamMousavi\FalconContainer\FalconServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;

class DBServiceProvider extends FalconServiceProvider
{
    public function register()
    {
        $this->container->singleton('db', function () {
            $capsule = new Capsule();
            global $wpdb;

            if (isset($wpdb)) {
                $data_connection = [
                    'driver' => 'mysql',
                    'host' => DB_HOST,
                    'database' => DB_NAME,
                    'username' => DB_USER,
                    'password' => DB_PASSWORD,
                    'charset' => $wpdb->charset,
                    'collation' => $wpdb->collate,
                    'prefix' => '',
                ];
            } else { // for scheduler
                $data_connection = [
                    'driver' => 'mysql',
                    'host' => 'localhost',
                    'database' => 'service-test',
                    'username' => 'root',
                    'password' => '',
                    'prefix' => '',
                ];
            }

            $capsule->addConnection($data_connection);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        });
    }

    public function boot() {}
}
