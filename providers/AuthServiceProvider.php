<?php

namespace RLuders\JWTAuth\Providers;

use Config;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\RateLimiter;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Check;
use PHPOpenSourceSaver\JWTAuth\Providers\AbstractServiceProvider;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Exceptions\JsonValidationException;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;
use RLuders\JWTAuth\Models\Settings as PluginSettings;

class AuthServiceProvider extends AbstractServiceProvider
{
    /**
     * Boot the service provider.
     *
     * Registers exception handlers, loads configuration, sets up the rate
     * limiter, and aliases all JWT middleware.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerExceptionHandlers();
        $this->bindRequests();
        $this->loadConfiguration();
        $this->registerRateLimiter();
        $this->aliasMiddleware();
    }

    /**
     * Register JSON exception renderers for JWT and validation errors.
     *
     * Handlers are evaluated in LIFO order (last registered = first checked),
     * so the most specific exception types are registered last.
     *
     * @return void
     */
    protected function registerExceptionHandlers(): void
    {
        // Generic fallback for any JWTException not caught by a controller.
        // Controllers that need a specific status code (e.g. 403 on refresh)
        // catch the exception themselves before it reaches this handler.
        $this->app->make(ExceptionHandler::class)
            ->renderable(function (JWTException $e, $request) {
                if (!$request->isJson()) {
                    return null;
                }
                return ErrorResponse::json(
                    ErrorCodes::TOKEN_ERROR,
                    $e->getMessage(),
                    \Illuminate\Http\Response::HTTP_UNAUTHORIZED
                );
            });

        // More specific JWT exception types — override the fallback above.
        $this->app->make(ExceptionHandler::class)
            ->renderable(function (TokenInvalidException $e, $request) {
                if (!$request->isJson()) {
                    return null;
                }
                return ErrorResponse::json(
                    ErrorCodes::TOKEN_INVALID,
                    'The token is invalid.',
                    \Illuminate\Http\Response::HTTP_UNAUTHORIZED
                );
            });

        $this->app->make(ExceptionHandler::class)
            ->renderable(function (TokenBlacklistedException $e, $request) {
                if (!$request->isJson()) {
                    return null;
                }
                return ErrorResponse::json(
                    ErrorCodes::TOKEN_BLACKLISTED,
                    'The token has been blacklisted.',
                    \Illuminate\Http\Response::HTTP_UNAUTHORIZED
                );
            });

        $this->app->make(ExceptionHandler::class)
            ->renderable(function (TokenExpiredException $e, $request) {
                if (!$request->isJson()) {
                    return null;
                }
                return ErrorResponse::json(
                    ErrorCodes::TOKEN_EXPIRED,
                    'The token has expired.',
                    \Illuminate\Http\Response::HTTP_UNAUTHORIZED
                );
            });

        // Rate limit exceeded.
        $this->app->make(ExceptionHandler::class)
            ->renderable(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
                if (!$request->isJson()) {
                    return null;
                }
                return ErrorResponse::json(
                    ErrorCodes::TOO_MANY_REQUESTS,
                    'Too many attempts. Please try again later.',
                    \Illuminate\Http\Response::HTTP_TOO_MANY_REQUESTS
                );
            });

        // Validation errors from request classes.
        $this->app->make(ExceptionHandler::class)
            ->renderable(function (JsonValidationException $exception, $request) {
                if ($request->isJson()) {
                    return response()->json(
                        $exception->toArray(),
                        $exception->getStatusCode(),
                        $exception->getHeaders()
                    );
                }
                return null;
            });
    }

    /**
     * Bind request classes to the container so they behave like Laravel FormRequests.
     *
     * @return void
     */
    protected function bindRequests(): void
    {
        $this->app->bind(
            \RLuders\JWTAuth\Http\Requests\TokenRequest::class,
            function ($app) {
                return new \RLuders\JWTAuth\Http\Requests\TokenRequest(input());
            }
        );

        $this->app->bind(
            \RLuders\JWTAuth\Http\Requests\LoginRequest::class,
            function ($app) {
                return new \RLuders\JWTAuth\Http\Requests\LoginRequest(input());
            }
        );

        $this->app->bind(
            \RLuders\JWTAuth\Http\Requests\ActivationRequest::class,
            function ($app) {
                return new \RLuders\JWTAuth\Http\Requests\ActivationRequest(input());
            }
        );

        $this->app->bind(
            \RLuders\JWTAuth\Http\Requests\ForgotPasswordRequest::class,
            function ($app) {
                return new \RLuders\JWTAuth\Http\Requests\ForgotPasswordRequest(input());
            }
        );

        $this->app->bind(
            \RLuders\JWTAuth\Http\Requests\RegisterRequest::class,
            function ($app) {
                return new \RLuders\JWTAuth\Http\Requests\RegisterRequest(input());
            }
        );

        $this->app->bind(
            \RLuders\JWTAuth\Http\Requests\ResetPasswordRequest::class,
            function ($app) {
                return new \RLuders\JWTAuth\Http\Requests\ResetPasswordRequest(input());
            }
        );

        // Auto-validate each request as soon as it is resolved from the container.
        $this->app->resolving(
            \RLuders\JWTAuth\Http\Requests\Request::class,
            function ($request, $app) {
                $request->validate();
            }
        );
    }

    /**
     * Merge JWT configuration from the plugin settings into the jwt config key.
     *
     * @return void
     */
    protected function loadConfiguration(): void
    {
        // Merge plugin providers into the jwt-auth package defaults instead of
        // replacing them. Replacing wipes package defaults (blacklist_enabled, ttl,
        // etc.) leaving only the providers section, which breaks tests and fresh installs.
        Config::set('jwt', array_merge(
            Config::get('jwt', []),
            Config::get('rluders.jwtauth::jwt', [])
        ));

        try {
            $attributes = PluginSettings::instance()->attributes;
        } catch (\Exception $e) {
            $attributes = [];
        }

        foreach ($attributes as $attr => $value) {
            $config = 'jwt.' . str_replace('keys_', 'keys.', $attr);

            if ($config == 'jwt.required_claims'
                || $config == 'jwt.persistent_claims'
            ) {
                $value = explode(' ', $value);
            }

            if ($config == 'jwt.decrypt_cookies') {
                // Inverse logic: the setting label says "encrypt cookies" but the
                // underlying JWT config key is decrypt_cookies.
                $value = !$value;
            }

            $isInteger = in_array(
                $config,
                [
                    'jwt.ttl',
                    'jwt.refresh_ttl',
                    'jwt.leeway',
                    'jwt.blacklist_grace_period',
                    'jwt.throttle_max_attempts',
                    'jwt.throttle_decay_minutes',
                ]
            );
            if ($isInteger) {
                $value = (int) $value;
            }

            Config::set($config, $value);
        }
    }

    /**
     * Register the named rate limiter used by the throttle:jwtauth middleware.
     *
     * Limits are read from plugin settings so administrators can tune them
     * without a code deploy.
     *
     * @return void
     */
    protected function registerRateLimiter(): void
    {
        RateLimiter::for('jwtauth', function (\Illuminate\Http\Request $request) {
            $maxAttempts  = (int) Config::get('jwt.throttle_max_attempts', 5);
            $decayMinutes = (int) Config::get('jwt.throttle_decay_minutes', 1);

            return Limit::perMinutes($decayMinutes, $maxAttempts)->by($request->ip());
        });
    }

    /**
     * Alias JWT middleware so they can be used by name in route definitions.
     *
     * Adds `jwt.auth.optional` (soft authentication via Check middleware) in
     * addition to the aliases provided by the parent class.
     *
     * @return void
     */
    protected function aliasMiddleware(): void
    {
        $router = $this->app['router'];

        $method = method_exists($router, 'aliasMiddleware')
            ? 'aliasMiddleware'
            : 'middleware';

        $aliases = array_merge($this->middlewareAliases, [
            'jwt.auth.optional' => Check::class,
        ]);

        foreach ($aliases as $alias => $middleware) {
            $router->$method($alias, $middleware);
        }
    }
}
