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


interface ProvidesPredicate
{
    /**
     * Set the predicate function used when comparing cached values
     * 
     * @param callable|\Closure($a, $b):bool $predicate
     * 
     * @return void 
     */
    public function setPredicate($predicate);
}