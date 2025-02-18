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

use Drewlabs\Caching\Contracts\CachedItemInterface;

class CachedItem implements CachedItemInterface
{
    /**
     * @var string|int|mixed
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Creates a new cache item.
     *
     * @param string|int|mixed $key
     * @param mixed            $value
     *
     * @return void
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Returns the cached item key.
     *
     * @return string|int|mixed
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Returns the cached value.
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
