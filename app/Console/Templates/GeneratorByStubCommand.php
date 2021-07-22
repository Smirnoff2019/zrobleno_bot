<?php

namespace App\Console\Templates;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use App\Console\Templates\Interfaces\GeneratorByStubCommandInterface;

abstract class GeneratorByStubCommand extends GeneratorCommand implements GeneratorByStubCommandInterface
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:any';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new any file";

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $stubFileName = 'any.stub';

    /**
     * Default dirname.
     *
     * @var string
     */
    protected $defaultDirname = 'App';

    /**
     * Indicates whether to add the type to the name.
     *
     * @var bool
     */
    protected $addTypeIndex = false;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, "{$this->type} name"],
        ];
    }

    /**
     * This method will be executed immediately when the command method handler is called
     *
     * @return void
     */
    protected function boot()
    {
        $this->addTypeIndex = trim($this->option('type')) === 'true';
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->boot();

        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');

            return false;
        }

        $namespace = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($namespace);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())
        ) {
            $this->error($this->type . ' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildFile($namespace)));

        $this->showInfoTable($namespace);

        $this->info($this->type . ' created successfully.');
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, "\\/");

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name
        );
    }

    /**
     * Build the routes file with the given name.
     *
     * @param  string  $namespace
     * @return string  $stub
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    abstract protected function buildFile($namespace): string;

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
                ["ClassName", $this->getNameInput($namespace), '<info>created</info>'],
                ["Namespace", $this->qualifyClass($namespace), '<info>created</info>'],
            ],
        );
    }

    /**
     * Build the routes file with the given name.
     *
     * @param  string  $namespace
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStubContent()
    {
        return $this->files->get($this->getStub());
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path("stubs/{$this->stubFileName}");
    }

    /**
     * Get the resource type.
     *
     * @return string
     */
    protected function getType()
    {
        return Str::of($this->type)
            ->trim();
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $namespace
     * @return string
     */
    protected function getPath($namespace)
    {
        $basePath = base_path($this->defaultDirname);
        $name = Str::of($namespace)
            ->replaceFirst($this->rootNamespace(), '')
            ->trim('\\')
            ->start('/')
            ->replace('\\', '/');

        return "{$basePath}{$name}.php";
    }

    /**
     * Get the atribute 'name' from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $type = Str::of($this->type)->replaceMatches("/[\s]/", "");
        $defName = $this->argument('name');
        
        $name = (string) Str::of($defName)
            ->trim()
            ->finish($this->addTypeIndex ? (string) $type : '');

        return $this->formatedNameInput($name);
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
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return (string) Str::of($this->defaultDirname)->replace('/', '\\');
    }

}
