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

use Drewlabs\Core\Helpers\Arr;
use Drewlabs\Core\Helpers\Iter;
use Drewlabs\Core\Helpers\Reflector;

if (!function_exists('drewlabs_core_get')) {

    /**
     * Get value from an object or array using the . seperator.
     *
     * @param mixed      $target
     * @param mixed      $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    function drewlabs_core_get($target, $key, $default = null)
    {
        if (null === $key) {
            return $target;
        }
        $keys = is_array($key) ? $key : explode('.', (string) $key);
        foreach ($keys as $i => $segment) {
            unset($keys[$i]);
            if (null === $segment) {
                return $target;
            }
            if ('*' === $segment) {
                if (!is_array($target)) {
                    return $default instanceof \Closure ? $default() : $default;
                }
                $result = [];
                foreach ($target as $item) {
                    $result[] = drewlabs_core_get($item, $keys);
                }

                return in_array('*', $keys, true) ? Iter::collapse($result) : $result;
            }
            $target = (Arr::isArrayable($target)) ? Arr::get($target, $segment) : ((is_object($target) && (null !== ($target_ = Reflector::getPropertyValue($target, $segment)))) ? $target_ : ($default instanceof \Closure ? $default() : $default));
        }

        return $target;
    }
}
