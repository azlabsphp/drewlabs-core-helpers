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

namespace Drewlabs\Caching;

use Drewlabs\Caching\Contracts\BufferedCacheInterface;
use Drewlabs\Caching\Contracts\CachedItemInterface;
use Drewlabs\Caching\Contracts\ProvidesPredicate;

class LRUCache implements BufferedCacheInterface, ProvidesPredicate
{
    /**
     * Internal cache storage object
     * 
     * @var array<CachedItem>
     */
    private $storage = [];

    /**
     * Max size of the LRU cache.
     *
     * @var int
     */
    private $size = 10;

    /**
     * @var \Closure
     */
    private $predicate;

    /**
     * Creates class instance
     * 
     * @param callable $predicate 
     * @param int $size 
     * 
     */
    public function __construct(callable $predicate = null, int $size = 16)
    {
        $this->predicate = $predicate;
        $this->size = $size ?? 16;
    }

    public function setPredicate($predicate)
    {
        $this->predicate = $predicate;
    }

    public function setCapacity(int $capacity)
    {
        $this->size = $capacity;
    }

    public function getCapacity()
    {
        return $this->size;
    }

    public function get($key)
    {
        /**
         * @var CachedItemInterface $current
         */
        $current = null;
        $index = -1;

        /**
         * @var CachedItemInterface $c
         */
        foreach ($this->storage ?? [] as $i => $c) {
            if (($this->predicate)($c->key(), $key)) {
                $current = $c;
                $index = $i;
                break;
            }
        }
        if (-1 !== $index && ($index > 0)) {
            // Move the recent argument list search to the top of the
            // to optimize searching algorithm for subsequent searchs
            $this->storage = array_merge(
                [$current],
                \array_slice($this->storage, 0, $index),
                \array_slice($this->storage, $index + 1)
            );
        }
        return $current ? $current->value() : Tokens::__MEMOIZED__NOT_FOUND__;
    }

    public function set($key, $value)
    {
        if (\count($this->storage) === $this->size) {
            // If storage size is equals to the max size
            // remove the last item from the storage
            array_pop($this->storage);
        }
        $this->storage[] = new CachedItem($key, $value);
    }

    public function remove($key)
    {
        $this->storage = $this->arrayRemove($this->storage, function (CachedItemInterface $value) use ($key) {
            return ($this->predicate)($value->key(), $key);
        });
    }

    public function clear()
    {
        return $this->storage = [];
    }


    public function __destruct()
    {
        unset($this->storage);
    }

    /**
     * Remove from array where the `predicate` return `true`
     * 
     * @param array $array 
     * @param callable $predicate 
     * @return array 
     */
    private function arrayRemove(array $array, callable $predicate)
    {
        $index = -1;
        foreach ($array ?? [] as $key => $current) {
            ++$index;
            if ($predicate($current, $key)) {
                return array_merge(
                    \array_slice($array, 0, $index),
                    \array_slice($array, $index + 1)
                );
            }
        }
        return $array;
    }
}
