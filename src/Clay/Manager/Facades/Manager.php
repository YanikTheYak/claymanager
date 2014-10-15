<?php

namespace Manager\Facades;

use Illuminate\Support\Facades\Facade;

class Manager extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'manager'; }

}
