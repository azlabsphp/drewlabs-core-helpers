<?php

use Psr\Http\Message\ServerRequestInterface;

if (!function_exists('drewlabs_core_url_has_valid_signature')) {

    /**
     * Check if Request URI is has valid signature or has not being tempered
     *
     * @param ServerRequestInterface $request
     * @param \Closure $key_resolver
     * @param boolean $absolute
     * @return boolean
     */
    function drewlabs_core_url_has_valid_signature(ServerRequestInterface $request, \Closure $key_resolver, $absolute = true)
    {
        return \drewlabs_core_url_has_correct_signature($request, $key_resolver, $absolute)
            && \drewlabs_core_url_has_not_expired_signature($request);
    }
}

if (!function_exists('drewlabs_core_url_has_correct_signature')) {

    /**
     * Verify if request URI is correct
     *
     * @param ServerRequestInterface $request
     * @param \Closure $key_resolver
     * @param boolean $absolute
     * @return boolean
     */
    function drewlabs_core_url_has_correct_signature(ServerRequestInterface $request, \Closure $key_resolver, $absolute = true)
    {
        $requestURI = $request->getUri();
        $query_params = $request->getQueryParams();
        $url = $absolute ? (string)($requestURI) : '/' . $requestURI->getPath();
        $original = rtrim($url . '?' . \drewlabs_core_url_array_to_query_string(
            \drewlabs_core_array_except($query_params, 'signature')
        ), '?');
        $signature = hash_hmac('sha256', $original, call_user_func($key_resolver));
        $signature_query = isset($query_params['signature']) ? $query_params['signature'] : '';
        return hash_equals($signature, (string) $signature_query);
    }
}

if (!function_exists('drewlabs_core_url_has_not_expired_signature')) {

    /**
     * Checks if request URI has noot expired
     *
     * @param ServerRequestInterface $request
     * @return boolean
     */
    function drewlabs_core_url_has_not_expired_signature(ServerRequestInterface $request)
    {
        $query_params = $request->getQueryParams();
        $expires = isset($query_params['expires']) ? $query_params['expires'] : '';
        return !($expires && \drewlabs_core_datetime_now()->getTimestamp() > $expires);
    }
}

if (!function_exists('drewlabs_core_url_array_to_query_string')) {

    /**
     * Convert the array into a query string.
     *
     * @param  array  $array
     * @return string
     */
    function drewlabs_core_url_array_to_query_string($array)
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }
}
