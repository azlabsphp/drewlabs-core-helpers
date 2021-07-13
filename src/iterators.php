<?php


if (!function_exists('drewlabs_core_iter_map')) {
    /**
     * Map through the values of a given iterator
     *
     * @param Iterator $it
     * @param \Closure $callback
     * @return \Iterator|\ArrayIterator|array
     */
    function drewlabs_core_iter_map(Iterator $it, \Closure $callback, $preserve_keys = true)
    {
        $items = [];
        $keys = [];
        iterator_apply($it, function (Iterator $it) use ($callback, &$items, &$keys, $preserve_keys) {
            list($current, $key) = [$it->current(), $it->key()];
            $items[] = $callback($current, $key);
            if ($preserve_keys) {
                $keys[] = $key;
            }
            return true;
        }, [$it]);
        return new ArrayIterator($preserve_keys ? array_combine($keys, $items) : $items);
    }
}