<?php

namespace Drewlabs\Core\Helpers\Arrays;

class BinarySearchBoundEnum
{
    /**
     * Indicates that the search must be performed in the lower bound of the mid element
     */
    const LOWER = -1;
    /**
     * Indicates that the search must be performed in the upper bound of the mid element
     */
    const UPPER = 1;

    /**
     * Indicated the item was found by the predicates function
     */
    const FOUND = 0;
}