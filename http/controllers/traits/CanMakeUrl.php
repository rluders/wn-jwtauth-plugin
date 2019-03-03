<?php

namespace RLuders\JWTAuth\Http\Controllers\Traits;

trait CanMakeUrl
{
    /**
     * Make the URL with Code
     *
     * @param string $url
     * @param string $code
     *
     * @return string
     */
    protected function makeUrl($url, $code)
    {
        $url = str_replace('{code}', $code, $url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = url($url);
        }

        return $url;
    }
}