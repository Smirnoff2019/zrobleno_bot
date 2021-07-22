<?php

namespace App\Traits;

trait Loger {

    protected function log(...$args)
    {
        info("=================================================");
        collect($args)->map(function($item) {
            info($item);
        });
        info("=================================================\n");
    }

}
