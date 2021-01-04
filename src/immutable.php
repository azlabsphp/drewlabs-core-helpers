<?php


if (!function_exists('drewlabs_core_recursive_get_attribute')) {
    /**
     * Recursively get the value of an attribute in the provided object
     * Nested attributes must be separated with the '.' ponctuation
     *
     * @param object|array $obj
     * @param string $key
     * @param mixed $default
     * @return void
     */
    function drewlabs_core_recursive_get_attribute($obj, $key, $default = null)
    {
        if (is_string($key) && \drewlabs_core_strings_contains($key, '.')) {
            $keys = \drewlabs_core_strings_to_array($key, '.');
            return array_reduce($keys, function ($carry, $current) use ($default) {
                if ($carry === $default) {
                    return $carry;
                }
                return \drewlabs_core_get_attribute($carry, $current, $default);
            }, $obj);
        }
        return \drewlabs_core_get_attribute($obj, $key, $default);
    }
}

if (!function_exists('drewlabs_core_recursive_set_attribute')) {
    /**
     * Try recursively to find the atribute of the object that need to be setted
     *
     * @param object|array $obj
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    function drewlabs_core_recursive_set_attribute($obj, $key, $value = null)
    {
        if (is_string($key) && \drewlabs_core_strings_contains($key, '.')) {
            $cache = [];
            $keys = \drewlabs_core_strings_to_array($key, '.');
            $lastIndex = count($keys) - 1;
            $topPropertyValue = drewlabs_core_get_attribute($obj, $keys[0], null);
            if (is_null($topPropertyValue)) {
                return $obj;
            }
            $i = 1;
            $current = \drewlabs_core_copy_object($topPropertyValue);
            // Build the attributes tree into a cache variable
            while ($i <= $lastIndex) {
                # code...
                if ($i === $lastIndex) {
                    $current = $value;
                } else {
                    $current = \drewlabs_core_get_attribute($current, $keys[$i], null);
                }
                if (is_null($current)) {
                    return $obj;
                }
                $cache[] = ['key' => $keys[$i], 'value' => \drewlabs_core_copy_object($current)];
                $i++;
            }
            // Set the value of the last item in the cache to equal to user provided value
            $cache = array_reverse($cache);
            // The last key of the reverse cache values is the name of the attribute to set
            $rkey = $cache[0]['key'];
            $rvalue = $cache[0]['value'];
            // Loop through the cache items in the reverse order and rebuild the object tree
            for ($index = 0; $index < count($cache); $index++) {
                # code...
                if (($index + 1) === count($cache)) {
                    break;
                }
                $rvalue = \drewlabs_core_set_attribute($cache[$index + 1]['value'], $cache[$index]['key'], $rvalue);
                $rkey = $cache[$index + 1]['key'];
            }
            return \drewlabs_core_set_attribute($obj, $keys[0], \drewlabs_core_set_attribute($topPropertyValue, $rkey, $rvalue));
        }
        return \drewlabs_core_set_attribute($obj, $key, $value);
    }
}

if (!function_exists('drewlabs_core_get_attribute')) {
    /**
     * Get a property from an object of type array or \stdClass using the provided
     * name or returns the {$default} value if provided or {NULL}
     *
     * @param \stdClass|array $obj
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function drewlabs_core_get_attribute($obj, $key, $default = null)
    {
        if (is_object($obj)) {
            $result = $default;
            // Breaking property accessibility of the OOP concept to avoid 
            // unexpected private or protected properties errors
            $refObject = new \ReflectionObject($obj);
            $keyProperty = $refObject->getProperty($key);
            $publicProperties = array_map(function (\ReflectionProperty $prop) {
                return $prop->getName();
            }, $refObject->getProperties(\ReflectionProperty::IS_PUBLIC));
            // Get the accessibility value of the property
            if (!in_array($key, $publicProperties)) {
                $keyProperty->setAccessible(true);
            }
            $result = $keyProperty->getValue($obj);
            if (!in_array($key, $publicProperties)) {
                $keyProperty->setAccessible(false);
            }
            return $result;
        }
        if (is_array($obj) || ($obj instanceof \ArrayAccess)) {
            return array_key_exists($key, $obj) ? $obj[$key] : $default;
        }
        // Throws an execption
        throw new \InvalidArgumentException('Create_property_getter method requires a parameter of type array or object');
    }
}

if (!function_exists('drewlabs_core_set_attribute')) {
    /**
     * Set the value of a given array or object and return the updated object
     *
     * @param \stdClass|array $obj
     * @param string $key
     * @param mixed $value
     * @throws \InvalidArgumentException
     * @return mixed
     */
    function drewlabs_core_set_attribute($obj, $key, $value)
    {
        if (is_object($obj)) {
            if (method_exists($obj, 'copyWith')) {
                return $obj->{'copyWith'}([$key => $value]);
            }
            // Create a clone copy of the object
            $clone = \drewlabs_core_copy_object($obj);
            // Breaking property accessibility of the OOP concept to avoid 
            // unexpected private or protected properties errors [Cannot access private property]
            $refObject = new \ReflectionObject($clone);
            $keyProperty = $refObject->getProperty($key);
            $publicProperties = array_map(function (\ReflectionProperty $prop) {
                return $prop->getName();
            }, $refObject->getProperties(\ReflectionProperty::IS_PUBLIC));
            // Get the accessibility value of the property
            if (!in_array($key, $publicProperties)) {
                $keyProperty->setAccessible(true);
            }
            $keyProperty->setValue($clone, $value);
            if (!in_array($key, $publicProperties)) {
                $keyProperty->setAccessible(false);
            }
            return $clone;
        }
        if (is_array($obj) || ($obj instanceof \ArrayAccess)) {
            return array_merge($obj, [$key => $value]);
        }
        // Throws an execption
        throw new \InvalidArgumentException('set_attribute method requires parameter 1 to be of type array or object');
    }
}

if (!function_exists('drewlabs_core_validate_attribute_name')) {
    /**
     * Validate the first argument [key] of the [create_property_getter] function
     *
     * @param \Closure|callable $func
     * @return \Closure|callable
     */
    function drewlabs_core_validate_attribute_name($func)
    {
        if (!($func instanceof \Closure) || !is_callable($func)) {
            throw new InvalidArgumentException('Function parameter must be a PHP Closure');
        }
        return function (...$args) use ($func) {
            if (count($args) === 0) {
                return $func(...$args);
            }
            $key = $args[0];
            if (!is_string($key) && !is_int($key)) {
                throw new \InvalidArgumentException('$key paramater must be a string or an array numeric index');
            }
            return $func(...$args);
        };
    }
}

if (!function_exists('drewlabs_core_create_attribute_getter')) {

    /**
     * Create an operator function that will be use to get attribute on a given array or object
     *
     * @param string|int $key
     * @param mixed|null $default
     * @return \Closure|callable
     */
    function drewlabs_core_create_attribute_getter($key, $default = null)
    {
        return function ($obj) use ($key, $default) {
            return \drewlabs_core_validate_attribute_name(function () use ($obj, $key, $default) {
                return \drewlabs_core_recursive_get_attribute($obj, $key, $default);
            })($key, $default);
        };
    }
}

if (!function_exists('drewlabs_core_create_attribute_setter')) {

    /**
     * Create an operator function that will be use to set attribute on a given array or object
     *
     * @param string|int|array $key
     * @param mixed|null $default
     * @return \Closure
     * @return \Closure|callable
     */
    function drewlabs_core_create_attribute_setter($key, $value = null)
    {
        return function ($obj) use ($key, $value) {
            // If the provided key is an array, apply a reducer for each key in the array
            if (is_array($key)) {
                return array_reduce($key, function ($carry, $current) {
                    return \drewlabs_core_validate_attribute_name(function () use ($carry, $current) {
                        return \drewlabs_core_recursive_set_attribute($carry, ...$current);
                    })(...$current);
                }, $obj);
            }
            // return \drewlabs_core_recursive_set_attribute($obj, $key, $value);
            return \drewlabs_core_validate_attribute_name(function () use ($obj, $key, $value) {
                return \drewlabs_core_recursive_set_attribute($obj, $key, $value);
            })($key, $value);
        };
    }
}

if (!function_exists('drewlabs_core_copy_object')) {
    /**
     * Create a copy of a given object or array. If the parameter is an object, this method makes
     * a clone of the object using PHP {clone} operator, or apply PHP array_merge to the parameter
     * if the parameter is an array
     *
     * @param object|array $obj
     */
    function drewlabs_core_copy_object($obj)
    {
        if (is_object($obj)) {
            return clone $obj;
        }
        if (is_array($obj)) {
            return array_merge([], $obj);
        }
        return $obj;
    }
}
