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

namespace Drewlabs\Core\Helpers;

use Psr\Http\Message\UriInterface;

class URI
{
    /**
     * Merge uri query string and parameters into a single array.
     *
     * @param string|UriInterface $query
     */
    public static function params($query): array
    {
        $result = parse_url((string) $query);
        $path = $result['path'] ?? '';
        $query = str_replace('?', '&', $result['query'] ?? '');
        parse_str($path, $tmp1);
        parse_str($query, $tmp2);

        return Arr::filter(array_merge($tmp1, $tmp2), static function ($value) {
            return !empty($value);
        });
    }

    /**
     * Returns the request URI without any query string.
     *
     * @param mixed $url
     *
     * @return string
     */
    public static function trimQueryStrings($url)
    {
        $url = \is_string($url) ? $url : (string) $url;

        return rtrim(preg_replace('/\?.*/', '', $url), '/');
    }

    /**
     * Convert the array/object into a query string.
     *
     * @param array|object $data
     * @param int          $encoding
     *
     * @return string
     */
    public static function buildQuery($data, $encoding = \PHP_QUERY_RFC3986)
    {
        return http_build_query($data, '', '&', $encoding);
    }

    /**
     * Creates a signature from a given URI scheme.
     *
     * @param string|UriInterface $url
     * @param mixed               $key_resolver
     *
     * @return string|false
     */
    public static function sign($url, $key_resolver, bool $absolute = true)
    {
        $url = \is_string($url) ? $url : (string) $url;
        $params = static::params($url);
        $url = $absolute ? static::trimQueryStrings($url) : '/'.static::normalizePath($url);
        $original = rtrim($url.'?'.static::buildQuery(Arr::except($params, ['signature'])), '?');

        return Str::hash($original, $key_resolver);
    }

    /**
     * Creates a signature from a given URI scheme and append it to URI.
     *
     * @param mixed $url
     * @param mixed $key_resolver
     *
     * @return string
     */
    public static function withSignature($url, $key_resolver, bool $absolute = true)
    {
        $url = \is_string($url) ? $url : (string) $url;
        $signature = Str::contains($url, '?') ? '&signature='.static::sign($url, $key_resolver, $absolute) : '?signature='.static::sign($url, $key_resolver, $absolute);

        return $url.$signature;
    }

    /**
     * Verify if request URI is correct.
     *
     * @param string|UriInterface $url
     * @param bool                $absolute
     *
     * @return bool
     */
    public static function verifySignature($url, callable $key_resolver, $absolute = true)
    {
        $url = \is_string($url) ? $url : (string) $url;
        $params = static::params($url);

        return Str::hequals($params['signature'] ?? '', static::sign($url, $key_resolver, $absolute));
    }

    /**
     * Checks if request URI has not expired.
     *
     * @param string|UriInterface $uri
     *
     * @return bool
     */
    public static function expires($uri)
    {
        $query_params = static::params($uri);
        if (!isset($query_params['expires'])) {
            return false;
        }

        return ImmutableDateTime::now()->getTimestamp() > (int) $query_params['expires'];
    }

    /**
     * Check if Request URI is has valid signature or has not being tempered.
     *
     * @param string|UriInterface $url
     * @param bool                $absolute
     *
     * @return bool
     */
    public static function verify($url, callable $key_resolver, $absolute = true)
    {
        $url = \is_string($url) ? $url : (string) $url;

        return static::verifySignature($url, $key_resolver, $absolute)
            && !static::expires($url);
    }

    /**
     * Checks if the provided uri is a valid HTTP url.
     *
     * @param string|UriInterface $url
     *
     * @return bool
     */
    public static function isValidHttpURI($url)
    {
        $url = \is_string($url) ? $url : (string) $url;

        return static::isValid($url) && \in_array(parse_url($url, \PHP_URL_SCHEME), ['http', 'https'], true);
    }

    /**
     * Checks if the provided uri is a valid HTTP url.
     *
     * @param string|UriInterface $url
     *
     * @return bool
     */
    public static function isValid($url)
    {
        if ($url instanceof UriInterface) {
            $url = (string) $url;
        }

        return false !== filter_var($url, \FILTER_VALIDATE_URL);
    }

    /**
     * Normalize the request path.
     *
     * @return string
     */
    private static function normalizePath(string $pattern)
    {
        return '' === $pattern ? '/' : $pattern;
    }
}
