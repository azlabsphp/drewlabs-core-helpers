<?php

declare(strict_types=1);

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Psr\Http\Message\ServerRequestInterface;

if (!function_exists('drewlabs_core_url_has_valid_signature')) {

    /**
     * Check if Request URI is has valid signature or has not being tempered.
     *
     * @param \Closure $key_resolver
     * @param bool     $absolute
     *
     * @return bool
     */
    function drewlabs_core_url_has_valid_signature(ServerRequestInterface $request, Closure $key_resolver, $absolute = true)
    {
        return drewlabs_core_url_has_correct_signature($request, $key_resolver, $absolute)
            && drewlabs_core_url_has_not_expired_signature($request);
    }
}

if (!function_exists('drewlabs_core_url_has_correct_signature')) {

    /**
     * Verify if request URI is correct.
     *
     * @param \Closure $key_resolver
     * @param bool     $absolute
     *
     * @return bool
     */
    function drewlabs_core_url_has_correct_signature(ServerRequestInterface $request, Closure $key_resolver, $absolute = true)
    {
        $query_params = $request->getQueryParams();
        $url = $absolute ? drewlabs_core_url_get_request_url($request) : '/'.drewlabs_core_url_get_request_path($request);
        $original = rtrim($url.'?'.drewlabs_core_url_array_to_query_string(
            drewlabs_core_array_except($query_params, 'signature')
        ), '?');
        $signature = hash_hmac('sha256', $original, call_user_func($key_resolver));
        $signature_query = $query_params['signature'] ?? '';

        return hash_equals($signature, (string) $signature_query);
    }
}

if (!function_exists('drewlabs_core_url_has_not_expired_signature')) {

    /**
     * Checks if request URI has noot expired.
     *
     * @return bool
     */
    function drewlabs_core_url_has_not_expired_signature(ServerRequestInterface $request)
    {
        $query_params = $request->getQueryParams();
        $expires = $query_params['expires'] ?? '';

        return !($expires && drewlabs_core_datetime_now()->getTimestamp() > $expires);
    }
}

if (!function_exists('drewlabs_core_url_array_to_query_string')) {

    /**
     * Convert the array into a query string.
     *
     * @param array $array
     *
     * @return string
     */
    function drewlabs_core_url_array_to_query_string($array)
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }
}

if (!function_exists('drewlabs_core_url_get_request_url')) {

    /**
     * @description Returns the request URI without any query string
     *
     * @return string
     */
    function drewlabs_core_url_get_request_url(ServerRequestInterface $request)
    {
        return rtrim(preg_replace('/\?.*/', '', $request->getUri()), '/');
    }
}

if (!function_exists('drewlabs_core_url_get_request_path')) {

    /**
     * Normalize the request path.
     *
     * @return string
     */
    function drewlabs_core_url_get_request_path(ServerRequestInterface $request)
    {
        $requestURI = $request->getUri();
        $pattern = trim($requestURI->getPath(), '/');

        return '' === $pattern ? '/' : $pattern;
    }
}
