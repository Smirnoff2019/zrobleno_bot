<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Console\Templates\GeneratorByStubCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeBotRoutes extends GeneratorByStubCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:bot-routes
                                {name : Bot routes file name}
                                {--t|type=true : Indicates whether to add the type to the name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new bot routes file";

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Routes';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $stubFileName = 'route.bot.stub';

    /**
     * Default dirname.
     *
     * @var string
     */
    protected $defaultDirname = 'routes/Bots';

    /**
     * Indicates whether to add the type to the name.
     *
     * @var bool
     */
    protected $addTypeIndex = false;

    /**
     * Build the routes file with the given name.
     *
     * @param  string  $namespace
     * @return string  $stub
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildFile($namespace): string
    {
        $this->info("\nMake {$this->type}:");

        $stub = $this->getStubContent();

        $this->replaceName($stub, $namespace);

        return $stub;
    }

    /**
     * Get a bot routes file name.
     *
     * @param  string  $namespace
     * @return string
     */
    protected function getRoutesName($namespace)
    {
        return (string) Str::of($namespace)->replace(
            Str::finish($this->getNamespace($namespace), '\\'),
            ''
        );
    }

    /**
     * Replace the bot routes file name.
     *
     * @param  string  &$stub
     * @param  string  $namespace
     * @return string
     */
    protected function replaceName(&$stub, $namespace)
    {
        return $stub = str_replace(
            ['{name}'],
            $this->getRoutesName($namespace),
            $stub
        );
    }

    /**
     * Formats the original atribute 'name' from the input and returns the final result
     *
     * @param  string  $name
     * @return string
     */
    protected function formatedNameInput(string $name)
    {
        return $name;
    }

    /**
     * Print information table.
     *
     * @param  string  $namespace
     * @return void
     */
    protected function showInfoTable($namespace)
    {
        $this->table(
            ['Key', 'Value', 'Status'],
            [
                ["Routes Name", $this->getRoutesName($namespace), '<info>created</info>'],
            ],
        );
    }

}
