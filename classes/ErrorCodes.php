<?php

namespace RLuders\JWTAuth\Classes;

/**
 * Canonical error codes returned by all API endpoints.
 *
 * Every JSON error response uses one of these codes in the `error.code` field
 * so clients can branch on a stable, machine-readable identifier rather than
 * human-readable messages.
 */
final class ErrorCodes
{
    // Authentication
    public const INVALID_CREDENTIALS    = 'invalid_credentials';
    public const COULD_NOT_CREATE_TOKEN = 'could_not_create_token';
    public const USER_IS_BANNED         = 'user_is_banned';
    public const USER_INACTIVE          = 'user_inactive';

    // Registration
    public const REGISTRATION_DISABLED  = 'registration_disabled';

    // Activation & password reset
    public const INVALID_ACTIVATION_CODE     = 'invalid_activation_code';
    public const INVALID_RESET_PASSWORD_CODE = 'invalid_reset_password_code';
    public const INVALID_USER                = 'invalid_user';
    public const USER_NOT_FOUND              = 'user_not_found';

    // Token operations
    public const COULD_NOT_REFRESH_TOKEN = 'could_not_refresh_token';
    public const TOKEN_EXPIRED           = 'token_expired';
    public const TOKEN_BLACKLISTED       = 'token_blacklisted';
    public const TOKEN_INVALID           = 'token_invalid';
    public const TOKEN_ERROR             = 'token_error';

    // Logout
    public const COULD_NOT_LOGOUT = 'could_not_invalidate_token';

    // Rate limiting
    public const TOO_MANY_REQUESTS = 'too_many_requests';

    // Server errors
    public const INTERNAL_ERROR = 'internal_error';
}
