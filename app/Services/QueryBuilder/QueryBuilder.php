<?php

namespace FalconBaseServices\Services\QueryBuilder;

use Illuminate\Database\Capsule\Manager as Capsule;

class QueryBuilder
{
    protected $capsule;

    public function __construct()
    {
        $this->capsule = new Capsule();
        global $wpdb;

        if (isset($wpdb)) {
            $data_connection = [
                'driver'    => 'mysql',
                'host'      => DB_HOST,
                'database'  => DB_NAME,
                'username'  => DB_USER,
                'password'  => DB_PASSWORD,
                'charset'   => $wpdb->charset,
                'collation' => $wpdb->collate,
                'prefix'    => '',
            ];
        } else { // for scheduler
            $data_connection = [
                'driver'   => 'mysql',
                'host'     => 'localhost',
                'database' => 'service-test',
                'username' => 'root',
                'password' => '',
                'prefix'   => '',
            ];
        }

        $this->capsule->addConnection($data_connection);

//        Set the event dispatcher used by Eloquent models... (optional)

//        $capsule->setEventDispatcher(new Dispatcher(new Container));

//        Make this Capsule instance available globally via static methods... (optional)
//        ex: Illuminate\Database\Capsule\Manager::table('users')->get()
        $this->capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $this->capsule->bootEloquent();
    }

    public function get()
    {
        return $this->capsule;
    }

}
