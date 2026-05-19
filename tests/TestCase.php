<?php

namespace RLuders\JWTAuth\Tests;

use System\Tests\Bootstrap\PluginTestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function guessPluginCode(): ?string
    {
        return 'RLuders.JWTAuth';
    }

    public function setUp(): void
    {
        parent::setUp();

        $cl = $this->app->make(\Winter\Storm\Support\ClassLoader::class);
        $pm = \System\Classes\PluginManager::instance();

        // System\ServiceProvider::boot() sets $noInit = true when the app runs in console
        // mode with the migrations table missing. registerPlugin() silently skips
        // plugin->register() while this flag is set — even after we add our plugins.
        // Reset it so our explicit register() calls below are not suppressed.
        \System\Classes\PluginManager::$noInit = false;

        // Create tables BEFORE calling plugin->register(), because
        // AuthServiceProvider::boot() → loadConfiguration() → PluginSettings::instance()
        // queries system_settings. If the table doesn't exist yet, it throws.
        \Artisan::call('winter:up');

        $plugins = [
            ['Winter\User',    base_path('plugins/winter/user')],
            ['RLuders\JWTAuth', base_path('plugins/rluders/jwtauth')],
        ];

        foreach ($plugins as [$ns, $path]) {
            $cl->autoloadPackage($ns, $path);

            $code = str_replace('\\', '.', $ns);
            $plugin = $pm->findByIdentifier($code);
            if (!$plugin) {
                $plugin = $pm->loadPlugin('\\' . $ns, $path);
                $pm->registerPlugin($plugin);
            }

            // WinterCMS scanned the plugins directory at boot (noInit=true then) so the
            // plugin is known to PluginManager, but plugin->register() was never called.
            // Call it explicitly now: Plugin::register() → App::register(AuthServiceProvider)
            // → since app is already booted, Laravel immediately runs both register() and
            // boot() on the service provider, binding tymon.jwt.auth and jwt.auth middleware.
            $plugin->register();
            $pm->bootPlugin($plugin);
        }

        // Re-clear routes; register plugin routes FIRST so the CMS catch-all
        // Route::any('{slug?}')->where('slug','(.*)?') ends up AFTER our specific routes.
        $this->app['router']->setRoutes(new \Illuminate\Routing\RouteCollection());

        require base_path('plugins/rluders/jwtauth/routes.php');

        foreach (\Config::get('cms.loadModules', []) as $module) {
            include base_path('modules/' . strtolower($module) . '/routes.php');
        }

        // Override JWT config for tests (loadConfiguration() set values from DB settings,
        // but we need a deterministic secret and in-memory storage for the test suite).
        config([
            'jwt.secret'           => env('JWT_SECRET', 'test-secret-key-for-testing-only-32chars'),
            'jwt.ttl'              => 60,
            'jwt.refresh_ttl'      => 20160,
            'jwt.blacklist_enabled'           => true,
            'jwt.show_black_list_exception'   => true,
            'jwt.providers.storage'           => \PHPOpenSourceSaver\JWTAuth\Providers\Storage\Illuminate::class,
        ]);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Create and optionally activate a test user.
     */
    protected function createUser(array $attributes = [], bool $activated = true): \RLuders\JWTAuth\Models\User
    {
        $defaults = [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ];

        $user = \RLuders\JWTAuth\Classes\AuthManager::instance()
            ->register(array_merge($defaults, $attributes), $activated);

        return $user;
    }

    /**
     * Return auth headers with a valid JWT for the given user.
     */
    protected function tokenFor(\RLuders\JWTAuth\Models\User $user): string
    {
        return \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::fromUser($user);
    }
}
