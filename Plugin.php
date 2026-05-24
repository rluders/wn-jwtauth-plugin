<?php

namespace RLuders\JWTAuth;

use App, Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Contracts\Debug\ExceptionHandler;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Models\Settings as PluginSettings;

/**
 * JWTAuth Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Plugin dependencies.
     *
     * @var array
     */
    public $require = ['Winter.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rluders.jwtauth::lang.plugin.name',
            'description' => 'rluders.jwtauth::lang.plugin.description',
            'author'      => 'Ricardo Lüders',
            'icon'        => 'icon-user-secret',
        ];
    }

    /**
     * Register the plugin settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'rluders.jwtauth::lang.settings.menu_label',
                'description' => 'rluders.jwtauth::lang.settings.menu_description',
                'category'    => SettingsManager::CATEGORY_USERS,
                'icon'        => 'icon-user-secret',
                'class'       => 'RLuders\JWTAuth\Models\Settings',
                'order'       => 600,
                'permissions' => ['rluders.jwtauth.access_settings'],
            ]
        ];
    }

    /**
     * Register the plugin permissions
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'rluders.jwtauth.access_settings' => [
                'tab' => 'rluders.jwtauth::lang.plugin.name',
                'label' => 'rluders.jwtauth::lang.permissions.settings'
            ]
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        // MUST be registered before App::register(AuthServiceProvider) so that when the
        // app is already booted (e.g. during tests), AuthServiceProvider::boot() adds its
        // more-specific handlers AFTER this one. Laravel checks renderable handlers in LIFO
        // order, so the last-registered handler is checked first — meaning
        // AuthServiceProvider's JsonValidationException handler will take priority here.
        $this->app->make(ExceptionHandler::class)
            ->renderable(function (\Exception $e, $request) {
                if (!$request->isJson()) {
                    return null;
                }
                // HTTP exceptions (401, 403, 404, 422 …) and Laravel auth exceptions
                // carry their own status codes — let the framework render them.
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                    || $e instanceof \Illuminate\Auth\AuthenticationException) {
                    return null;
                }

                return response()->json([
                    'error' => [
                        'code'    => ErrorCodes::INTERNAL_ERROR,
                        'message' => $e->getMessage(),
                    ],
                ], 500);
            });

        App::register('\RLuders\JWTAuth\Providers\AuthServiceProvider');
        $alias = AliasLoader::getInstance();
        $alias->alias('JWTAuth', '\RLuders\JWTAuth\Facades\JWTAuth');
    }
}
