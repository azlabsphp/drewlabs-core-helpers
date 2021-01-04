<?php

if (!function_exists('drewlabs_core_hashing_base62encode')) {

    function drewlabs_core_hashing_base62encode($value)
    {
        return (new \Tuupola\Base62)->encode($value);
    }
}

if (!function_exists('drewlabs_core_hashing_base62decode')) {

    function drewlabs_core_hashing_base62decode($value)
    {
        return (new \Tuupola\Base62)->decode($value);
    }
}

if (!function_exists('drewlabs_core_hashing_hash_str')) {
    /**
     * Creates a hash value from the provided string
     *
     * @param string $source
     * @param \Closure $key_resolver
     * @return string
     */
    function drewlabs_core_hashing_hash_str($source, \Closure $key_resolver)
    {
        if ($key_resolver instanceof \Closure) {
            $key_resolver = call_user_func($key_resolver);
        }
        if (!is_string($key_resolver)) {
            throw new \RuntimeException(\sprintf('%s : Requires either a PHP Closure or a string as 2nd parameter'), __METHOD__);
        }
        return hash_hmac('sha256', \drewlabs_core_hashing_base62encode($source), $key_resolver);
    }
}

if (!function_exists('drewlabs_core_hashing_hash_str_compare')) {
    /**
     * Compare the has value of the source string against the user provided hash
     *
     * @param string $source
     * @param string $match
     * @param \Closure|string $key_resolver
     * @return boolean
     */
    function drewlabs_core_hashing_hash_str_compare($source, $match, $key_resolver)
    {
        $result = \drewlabs_core_hashing_hash_str($source, $key_resolver);
        return hash_equals($result, $match);
    }
}
