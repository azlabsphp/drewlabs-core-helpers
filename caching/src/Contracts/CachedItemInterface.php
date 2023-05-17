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

namespace Drewlabs\Caching\Contracts;

interface CachedItemInterface
{

    /**
     * Returns the cached item key
     * 
     * @return string|int|mixed 
     */
    public function key();

    /**
     * Returns the cached value
     * 
     * @return mixed 
     */
    public function value();
}