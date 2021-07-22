<?php

namespace App\Console\Templates\Interfaces;


interface GeneratorByStubCommandInterface
{

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle();

}
