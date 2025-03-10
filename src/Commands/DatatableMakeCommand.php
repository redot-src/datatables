<?php

namespace Redot\Datatables\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class DatatableMakeCommand extends GeneratorCommand
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
        return __DIR__ . '/stubs/datatable.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Livewire\Datatables';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the datatable represents'],
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
        if ($this->option('model')) {
            $model = $this->option('model');
        } else {
            $model = $this->ask('What is the model name? (e.g. App\Models\User)');
        }

        if (! class_exists($model)) {
            $model = $this->rootNamespace() . 'Models\\' . $model;
        }

        if (! class_exists($model)) {
            $this->error('Model does not exist!');

            // Remove the model option to avoid infinite loop
            $this->input->setOption('model', null);

            return $this->replaceModel($stub);
        }

        $model = trim($model, '\\');
        $stub = str_replace(['{{ model }}', '{{model}}'], '\\' . $model, $stub);

        return $this;
    }
}
