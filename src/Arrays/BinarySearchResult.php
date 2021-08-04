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

namespace Drewlabs\Core\Helpers\Arrays;

class BinarySearchResult
{
    /**
     * Indicates that the search must be performed in the lower bound of the mid element.
     */
    public const LEFT = -1;
    /**
     * Indicates that the search must be performed in the upper bound of the mid element.
     */
    public const RIGHT = 1;

    /**
     * Indicated the item was found by the predicates function.
     */
    public const FOUND = 0;
}
