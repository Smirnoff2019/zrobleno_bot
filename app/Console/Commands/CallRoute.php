<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CallRoute extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:call
                                {uri : The path of the route to be called}
                                {method=GET : Request http method called}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'php artsian route:call /route';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $uri = $this->argument('uri');
        $method = $this->argument('method');

        $request = Request::create($uri, $method);
        $this->info(app()['Illuminate\Contracts\Http\Kernel']->handle($request));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['uri', InputArgument::REQUIRED, "The path of the route to be called"],
            ['method', InputArgument::OPTIONAL, "Request http method called", "GET"],
        ];
    }
}
