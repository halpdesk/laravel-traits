<?php

namespace Halpdesk\LaravelTraits\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\Config;
use Absolute\DotEnvManipulator\Libs\DotEnv;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @author Daniel Leppänen
 */
class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    /**
     * @param String    The full path to the root of this project
     */
    protected static $dir;

    /**
     * Setup the test environment
     */
    protected function setUp() : void
    {
        parent::setUp();
        $this->initialize();
        $this->getEnvironmentSetUp($this->app);

        // Migrate
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => static::$dir . '/tests/database/migrations',
        ]);
        $this->withFactories(static::$dir . '/tests/database/factories');
    }

    public static function setUpBeforeClass() : void
    {
        static::$dir = realpath(dirname(realpath(__FILE__)).'/../');
        parent::setUpBeforeClass();
    }

    /**
     * This is usually loaded/bootstrapped from phpunit.xml otherwise
     *
     * @return void
     */
    public static function composerAutoLoader()
    {
        require_once static::$dir . 'vendor/autoload.php';
    }

    /**
     * Initialize environment
     * Set ini parameters here, for example
     *
     * @return void
     */
    public function initialize()
    {
        date_default_timezone_set('Europe/Stockholm');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        if (file_exists(static::$dir.'/.env')) {

            $dotenv = new DotEnv(static::$dir, '.env');
            $envs = $dotenv->toArray();
            foreach ($envs as $env => $value) {
                putenv($env.'='.$value);
            }
        }

        $configFiles = glob(static::$dir.'/config/*.php');
        foreach ($configFiles as $configFile) {
            $name   = str_replace(".php", "", basename($configFile));
            $config = require $configFile;
            $existingConfig = Config::get($name, []);
            // Config::set($name, array_replace_recursive($existingConfig, $config));
            $app['config']->set($name, array_replace_recursive($existingConfig, $config));
        }
    }

    public function error($val)
    {
        dd($val);
    }
}
