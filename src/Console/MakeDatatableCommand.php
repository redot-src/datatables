<?php

namespace Redot\LivewireDatatable\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeDatatableCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:datatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new datatable class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Datatable';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/datatable.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Livewire';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the datatable already exists'],
        ];
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this
            ->replaceModel($stub)
            ->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceModel(&$stub)
    {
        $model = $this->ask('What is the model name? (e.g. App\Models\User)');

        if (! class_exists($model)) {
            $model = $this->rootNamespace().'Models\\'.$model;
        }

        if (! class_exists($model)) {
            $this->error('Model does not exist!');

            return $this->replaceModel($stub);
        }

        $model = trim($model, '\\');
        $stub = str_replace(['{{ model }}', '{{model}}'], '\\'.$model, $stub);

        return $this;
    }
}
