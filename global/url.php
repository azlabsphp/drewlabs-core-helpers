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

use Drewlabs\Core\Helpers\URI;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

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
        return URI::verify($request->getUri(), $key_resolver, $absolute);
    }
}

if (!function_exists('drewlabs_core_url_has_correct_signature')) {
    /**
     * Verify if request URI is correct.
     *
     * @param bool $absolute
     *
     * @return bool
     */
    function drewlabs_core_url_has_correct_signature(ServerRequestInterface $request, Closure $key_resolver, $absolute = true)
    {
        return URI::verifySignature($request->getUri(), $key_resolver, $absolute);
    }
}

if (!function_exists('drewlabs_core_url_has_not_expired_signature')) {

    /**
     * @deprecated v2.0.x
     * Checks if request URI has noot expired.
     *
     * @return bool
     */
    function drewlabs_core_url_has_not_expired_signature(ServerRequestInterface $request)
    {
        $query_params = drewlabs_core_url_request_query_as_array($request);
        $expires = $query_params['expires'] ?? '';

        return !($expires && drewlabs_core_datetime_now()->getTimestamp() > $expires);
    }
}

if (!function_exists('drewlabs_core_url_array_to_query_string')) {

    /**
     * Convert the array into a query string.
     *
     * @return string
     */
    function drewlabs_core_url_array_to_query_string($data)
    {
        return URI::buildQuery($data);
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
        return URI::trimQueryStrings($request->getUri());
    }
}

if (!function_exists('drewlabs_core_url_get_url_without_query_string')) {

    /**
     * @description Returns the request URI without any query string
     *
     * @return string
     */
    function drewlabs_core_url_get_url_without_query_string(string $url)
    {
        return URI::trimQueryStrings($url);
    }
}

if (!function_exists('drewlabs_core_url_get_request_path')) {

    /**
     * @deprecated v2.0.x
     *
     * Normalize the request path.
     *
     * @return string
     */
    function drewlabs_core_url_get_request_path(ServerRequestInterface $request)
    {
        $requestURI = $request->getUri();

        return drewlabs_core_url_get_normalize_request_path(trim($requestURI->getPath(), '/'));
    }
}

if (!function_exists('drewlabs_core_url_get_normalize_request_path')) {

    /**
     * @deprecated v2.0.x
     *
     * Normalize the request path.
     *
     * @return string
     */
    function drewlabs_core_url_get_normalize_request_path(string $pattern)
    {
        return '' === $pattern ? '/' : $pattern;
    }
}

if (!function_exists('drewlabs_core_url_is_http_url')) {
    /**
     * Checks if the provided uri is a valid HTTP url.
     *
     * @param string|UriInterface $url
     *
     * @return bool
     */
    function drewlabs_core_url_is_http_url($url)
    {
        return URI::isValidHttpURI($url);
    }
}

if (!function_exists('drewlabs_core_url_query_string_to_array')) {
    /**
     * Parse a PHP query string into a array.
     *
     * @return array<string,string>
     */
    function drewlabs_core_url_query_string_to_array(string $query): array
    {
        return URI::params($query);
    }
}

if (!function_exists('drewlabs_core_url_request_query_as_array')) {
    /**
     * Parse a {ServerRequestInterface} query parameters into a array.
     *
     * @return array<string,string>
     */
    function drewlabs_core_url_request_query_as_array(ServerRequestInterface $request): array
    {
        $array = $request->getQueryParams();
        if (empty($array)) {
            $url = $request->getUri()->getQuery();
            $array = drewlabs_core_url_query_string_to_array($url);
        }

        return $array;
    }
}

if (!function_exists('drewlabs_core_url_signature_from_server_request')) {
    /**
     * Creates a signature from a given URI scheme.
     *
     * @param \Closure|string $key_resolver
     *
     * @return string
     */
    function drewlabs_core_url_signature_from_server_request(ServerRequestInterface $request, $key_resolver, bool $absolute)
    {
        return URI::sign($request->getUri(), $key_resolver, $absolute);
    }
}

if (!function_exists('drewlabs_core_url_signature_from_url')) {

    /**
     * Creates a signature from a given URI scheme.
     *
     * @param mixed $key_resolver
     *
     * @return string|false
     */
    function drewlabs_core_url_signature_from_url(string $url, $key_resolver, bool $absolute = true)
    {
        return URI::sign($url, $key_resolver, $absolute);
    }
}
