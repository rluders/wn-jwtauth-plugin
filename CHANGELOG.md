# Release Notes

##  v1.3.0 (2019-03-03)

### Changes

- The AuthController was removed and splited into Single Action Controllers
- Added some Traits to keep the code simple

### Fixes

- Activate user by email is now working
- Inactive users can't reset they passwords

### Added

- German language

## From v1.2.1 to v1.2.5

- I forgot to track it here and I'm too lazy to find out the changes. ¯\_(ツ)_/¯

## v1.2.0 (2018-05-30)

### Changes

- Updating `tymon/jwt-auth` version
- Routes changes
  - Named route support under `api.auth.*`
    - `/api/auth/account_activation` is now `/api/auth/account-activation`
    - `/api/auth/forgot_password` is now `/api/auth/forgot-password`
    - `/api/auth/reset_password` is now `/api/auth/reset-password`
    - `/api/auth/reset_token` is now `/api/auth/reset-token`
- The route `/api/auth/reset-token` is now protected by `jwt`

### Added

- Added new route
  - `/api/auth/me` protected by `jwt`, returns the user
- Added more settings to the configuration form
- Support to `RanLab.User` events
  - `rainlab.user.beforeAuthenticate`
  - `rainlab.user.beforeRegister`
  - `rainlab.user.register`

## v1.1.6 (2018-03-02)

### Added

- Handling with blacklisted tokens

## v1.1.5 (2018-03-02)

### Added

- Added CHANGELOG.md
- Now you can login using `username` or `email` depends on `loginAttribute` configured by `RainLab.User`
- Adding support to Brazilian Portuguese (Suporte ao idioma Português do Brasil)

## v1.1.4 (2018-03-02)

### Added

- User object to the refresh endpoint response

### Fixed

- Small bug on refresh token endpoint

## v1.1.3 (2018-03-01)

### Added

- Refresh token endpoint

## 1.1.2 (2017-11-21)

### Fixed
- URL on email templates

## v1.1.1 (2017-11-21)

### Added

- Ads JWTAuth Facade
- Ads JWTFactory Facade

### Test

- Tested with October CMS build 428 (Laravel 5.5 and last RainLab.User 1.4.3)

## v1.1.0 (2017-03-03)

### Added

- Adding settings support

## v1.0.0 (2017-03-02)

- Plugin's first release.
