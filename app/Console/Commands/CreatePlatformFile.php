<?php

namespace App\Console\Commands;

use App\Models\IntegrationType;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CreatePlatformFile extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:platform {name} {--seeder : option to create a seeder class if needed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a platform scaffolding.';

    /**
     * @var string
     */
    protected $type = 'Platform';

    /**
     * @var string
     */
    protected $platform_directory = '/Services/Platforms/';

    /**
     * @var string
     */
    protected $platform_lib_directory = '/Libraries/';

    /**
     * @var string
     */
    protected $platform_custom_functions_directory = '/CustomFunctions';

    /*
     * @return bool|void|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $seeder = $this->option('seeder');
        $platform = $this->argument('name');
        if (!$platform) {
            $this->error("Platform Name doesn't exists.");
            return false;
        }

        // check if directory exists, if not create
        $path = $this->getPlatformDirectory($platform);
        $custom_functions_path = $path . $this->platform_custom_functions_directory;
        $library_path = $this->getPlatformLibDirectory($platform);

        if (File::isDirectory($path)) {
            $this->error("Platform already exists.");
            return false;
        }

        // create the platform directory
        File::makeDirectory($path, 0775, true, true);

        // create the platform custom functions directory
        if (!File::isDirectory($library_path)) {
            File::makeDirectory($custom_functions_path, 0775, true, true);
        }

        // create the platform library directory
        if (!File::isDirectory($library_path)) {
            File::makeDirectory($library_path, 0775, true, true);
        }

        // create the platform file with the Stub
        $this->files->put($this->getPlatformFullFilePath($this->makeDirectory($path), $platform), $this->buildClass($platform));
        $this->info($this->type.' service class and folder created successfully.');

        // create the integration type when a platform is created
        $integration_type =  IntegrationType::where('name', ucwords($platform))->first();

        if (!$integration_type) {
            $data = [
                'name' => ucwords($platform),
                'directory' => '\\App\\Services\\Platforms\\' . ucwords($platform),
                'is_active' => 1
            ];

            IntegrationType::create($data);

            $this->info("Integration Type for Platform: $platform created!");
        }

        if ($seeder) {
            Artisan::call('make:seeder', [ 'name' => 'Integration' . ucwords($platform) . 'Seeder']);
            $this->info("Seeder file for Platform: $platform created!");
        }

        return true;
    }

    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "Services\Platforms\\" . ucwords($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return app_path() . '/Stubs/Platform.stub';
    }

    /**
     * @param $name
     * @return string
     */
    protected function getPlatformLibDirectory($name)
    {
        return app_path() . $this->platform_lib_directory . ucwords($name);
    }

    /**
     * @param $name
     * @return string
     */
    protected function getPlatformDirectory($name)
    {
        return app_path(). $this->platform_directory . ucwords($name);
    }

    /**
     * @param $path
     * @param $name
     * @return string
     */
    protected function getPlatformFullFilePath($path, $name)
    {
        return $path . '/' . $name . 'Service.php';
    }

    /**
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        return parent::replaceClass($stub, $name . 'Service');
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return $this->getDefaultNamespace($this->rootNamespace());
    }
}
