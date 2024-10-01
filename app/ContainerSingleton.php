<?php

namespace FalconBaseServices;

use Exception;

class ContainerSingleton
{

    private static $instance = null;

    /**
     * The Singleton's constructor should always be not public to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct() {}

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = require __DIR__.'/../bootstrap/bootstrap.php';
        }

        return self::$instance;
    }

    /**
     * Singletons should not be restorable from strings.
     *
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone() {}

}
