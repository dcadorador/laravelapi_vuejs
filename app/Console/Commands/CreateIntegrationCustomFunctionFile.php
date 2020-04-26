<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use App\Models\Account;
use App\Models\Integration;
use Illuminate\Support\Facades\File;

class CreateIntegrationCustomFunctionFile extends GeneratorCommand
{
    const PLATFORM_SERVICE_FILE_APPEND = 'Service.php';

    /**
     * @var string
     */
    protected $account;

    /**
     * @var string
     */
    protected $integrations;
    protected $integration;

    /**
     * @var string
     */
    protected $platform_path = '/Services/Platforms/';

    /**
     * @var string
     */
    protected $platform_custom_functions_directory = '/CustomFunctions';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:integration-custom-function {client : The ID of the Client\'s Account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will create all custom integration file for the client\'s platform.';

    /**
     * CreateIntegrationCustomFunctionFile constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $id = $this->argument('client');

        // get the client account
        $this->account = Account::find($id);

        // check if the client exists
        if (!$this->account) {
            $this->error('Client Not Found.');
            return false;
        }

        // integrations
        $this->integrations = $this->account->integrations()->get();

        // check if empty
        if ($this->integrations->isEmpty()) {
            $this->info('No Integrations Found for Client ID: ' . $id);
            return true;
        }

        // loop through the integrations
        foreach ($this->integrations as $integration) {
            // check if PLATFORM directory is existing
            $platform_full_path = base_path() . $integration->integrationType->getPlatformPath();
            if (!File::isDirectory($platform_full_path)) {
                $this->error("Platform is not existing. " . $platform_full_path);
                return false;
            }


            // check if the Custom Functions for the integration is existing
            $platform_cust_func_path = $platform_full_path . $this->platform_custom_functions_directory . '/';
            if (!File::isDirectory($platform_cust_func_path)) {
                File::makeDirectory($platform_cust_func_path, 0775, true, true);
            }

            $custom_function_file = $platform_cust_func_path . $this->getFunctionClassName($integration->id, true);
            if (File::isFile($custom_function_file)) {
                $this->error("Custom Function already existed : " . $custom_function_file);
                continue;
            }

            // create the file
            $this->integration = $integration;
            $this->files->put($custom_function_file, $this->buildClass($integration->integrationType->name));
            $this->info('Custom Function created successfully.');

            // check the platform file
            $platform_file = $platform_full_path . '/'. $integration->integrationType->name .  self::PLATFORM_SERVICE_FILE_APPEND;
            if (!File::isFile($platform_file)) {
                $this->info('Platform file not found! : ' . $platform_file);
                return true;
            }

            // loop through the file content
            $file_name  = $this->getFunctionClassName($integration->id);
            $contents = file($platform_file);
            foreach ($contents as $key => $content) {
                // insert the usage of the custom function in the class
                if (stripos($content, "namespace") !== false) {
                    $trait_loader = "use App\Services\Platforms\\" . $integration->integrationType->name . "\CustomFunctions\\" . $file_name . ';';
                    array_splice($contents, $key + 1, 0, "\n");
                    array_splice($contents, $key + 2, 0, $trait_loader . "\n");
                }

                // check if the line is the first bracket of class, so that we can insert the "use TRAIT" in the platform
                if (stripos($content, "{") !== false) {
                    $trait_line = '    use ' . $file_name . ';';
                    array_splice($contents, $key + 3, 0, $trait_line . "\n"); // todo: can't think why this, contents cached array keys?!?
                    break;
                }
            }

            file_put_contents($platform_file, $contents);

            $this->info('Platform file updated with custom integration trait usage.');
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return app_path() . '/Stubs/Integration.stub';
    }


    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "Services\Platforms\\";
    }

    /**
     * @param $id
     * @param bool $file
     * @return string
     */
    protected function getFunctionClassName($id, $file = false)
    {
        $name = 'integration_' . $id . '_custom_functions';
        if ($file) {
            return $name . '.php';
        }
        return $name;
    }

    /**
     * @param string $stub
     * @param $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        return parent::replaceClass($stub, $this->getFunctionClassName($this->integration->id));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getNamespace($name)
    {
        $namespace =  $this->getDefaultNamespace($this->rootNamespace());
        return $namespace . $name . '\CustomFunctions';
    }
}
