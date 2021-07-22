<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CreateDomain extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain
                                {host}
                                {-p=80}
                                {-o=false : Open in browser}
                                {-als=true : Allow invalid ssl cert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'php artsian make:domain';

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
        # lt -p=80 --local-host=bot.zrobleno.loc --allow-invalid-cert -o

        // dd($this->arguments());
        $query = implode(' ', [
            'lt',
            "-p=". $this->argument('-p'),
            "--local-host=" . $this->argument('host'),
            (bool) $this->argument('-als')
                ? "--allow-invalid-cert"
                : "",
            (bool) $this->argument('-o')
                ? "-o"
                : "",
        ]);
        $this->info($this->argument('-o'));
        $this->info($query);
        // $res = shell_exec($query);

        return $res ?? false;
        // return $this->call($query);

        // $uri = $this->argument('uri');
        // $method = $this->argument('method');

        // $request = Request::create($uri, $method);
        // $this->info(app()['Illuminate\Contracts\Http\Kernel']->handle($request));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    // protected function getArguments()
    // {
    //     return [
    //         ['uri', InputArgument::REQUIRED, "The path of the route to be called"],
    //         ['method', InputArgument::OPTIONAL, "Request http method called", "GET"],
    //     ];
    // }
}
