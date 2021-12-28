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
        $query_params = drewlabs_core_url_request_query_as_array($request);
        $signature = drewlabs_core_url_signature_from_server_request($request, $key_resolver, $absolute);
        $signature_query = $query_params['signature'] ?? '';

        return hash_equals((string) $signature, (string) $signature_query);
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
        $query_params = drewlabs_core_url_request_query_as_array($request);
        $expires = $query_params['expires'] ?? '';

        return !($expires && drewlabs_core_datetime_now()->getTimestamp() > $expires);
    }
}

if (!function_exists('drewlabs_core_url_array_to_query_string')) {

    /**
     * Convert the array into a query string.
     *
     * @param array|object $array
     *
     * @return string
     */
    function drewlabs_core_url_array_to_query_string($array)
    {
        return http_build_query($array, '', '&', \PHP_QUERY_RFC3986);
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
        return drewlabs_core_url_get_url_without_query_string((string) $request->getUri());
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
        return rtrim(preg_replace('/\?.*/', '', (string) $url), '/');
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

        return drewlabs_core_url_get_normalize_request_path(trim($requestURI->getPath(), '/'));
    }
}

if (!function_exists('drewlabs_core_url_get_normalize_request_path')) {

    /**
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
        if ($url instanceof UriInterface) {
            $url = (string) $url;
        }

        return false !== filter_var($url, \FILTER_VALIDATE_URL)
            && in_array(parse_url($url, \PHP_URL_SCHEME), ['http', 'https'], true);
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
        $result = parse_url($query);
        $path = $result['path'] ?? '';
        // Relace every ? with & in the query string to avoid any parsing error
        $query = str_replace('?', '&', $result['query'] ?? '');
        parse_str($path, $tmp1);
        parse_str($query, $tmp2);

        return array_filter(array_merge($tmp1, $tmp2), static function ($value) {
            return null !== $value && !empty($value);
        });
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
        $query_params = drewlabs_core_url_request_query_as_array($request);
        $url = $absolute ? drewlabs_core_url_get_request_url($request) : '/'.drewlabs_core_url_get_request_path($request);
        $original = rtrim($url.'?'.drewlabs_core_url_array_to_query_string(
            drewlabs_core_array_except($query_params, ['signature'])
        ), '?');
        $key = (is_callable($key_resolver) || ($key_resolver instanceof \Closure)) ? call_user_func($key_resolver) : $key_resolver;

        return hash_hmac('sha256', $original, $key);
    }
}

if (!function_exists('drewlabs_core_url_signature_from_url')) {
    /**
     * Creates a signature from a given URI scheme.
     *
     * @param \Closure|string $key_resolver
     *
     * @return string
     */
    function drewlabs_core_url_signature_from_url(string $url, $key_resolver, bool $absolute = true)
    {
        $query_params = drewlabs_core_url_query_string_to_array($url);
        $url = $absolute ? drewlabs_core_url_get_url_without_query_string($url) : '/'.drewlabs_core_url_get_normalize_request_path($url);
        $original = rtrim($url.'?'.drewlabs_core_url_array_to_query_string(
            drewlabs_core_array_except($query_params, ['signature'])
        ), '?');
        $key = (is_callable($key_resolver) || ($key_resolver instanceof \Closure)) ? call_user_func($key_resolver) : $key_resolver;

        return hash_hmac('sha256', $original, $key);
    }
}
