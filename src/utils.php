<?php


if (!function_exists('drewlabs_core_convert_size_to_human_readable')) {

    /**
     * Converts bytes into human readable file size.
     *
     * @param string|float $bytes
     * @return string human readable file size (2,87 Мб)
     * @author AzandrewLabs
     */
    function drewlabs_core_convert_size_to_human_readable($bytes, $separator = '.')
    {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "unit" => "TB",
                "value" => pow(1024, 4)
            ),
            1 => array(
                "unit" => "GB",
                "value" => pow(1024, 3)
            ),
            2 => array(
                "unit" => "MB",
                "value" => pow(1024, 2)
            ),
            3 => array(
                "unit" => "KB",
                "value" => 1024
            ),
            4 => array(
                "unit" => "B",
                "value" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["value"]) {
                $result = $bytes / $arItem["value"];
                $result = str_replace(".", $separator, strval(round($result, 2))) . " " . $arItem["unit"];
                break;
            }
        }
        return $result;
    }
}


if (!function_exists('drewlabs_core_value')) {
    /**
     * Return the default value of the given value.
     *
     * @param \Closure|mixed  $value
     * @return mixed
     */
    function drewlabs_core_value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('drewlabs_core_is_valid_php_class')) {

    /**
     * Checks if a provided class is a valid PHP class autoloadable
     *
     * @param string $clazz
     * @return boolean
     */
    function drewlabs_core_is_valid_php_class($clazz)
    {
        return !is_null($clazz) && \drewlabs_core_strings_contains($clazz, ['\\', '\\\\']) && class_exists($clazz);
    }
}

if (!function_exists('drewlabs_core_build_model_type_attributes_rvalue_object')) {

    /**
     * Helper function that tries to build a model attribute and model itself using provided options
     *
     * @param string|array $config
     * @param array $options
     * @return ModelTypeAttributeRValue
     */
    function drewlabs_core_build_model_type_attributes_rvalue_object($config, array $options)
    {
        if (drewlabs_core_array_is_arrayable($config)) {
            if (!isset($config['class'])) {
                throw new \RuntimeException('Wrong collection configuration, class key is required on the collection configuration if an array is provided');
            }
            $attributes = [];
            $model = \drewlabs_core_create_php_class_instance($config['class']);
            if (!isset($config['attributes'])) {
                $attributes = ['label' => isset($options['label']) ? $options['label'] : null];
            }
            if (!drewlabs_core_array_is_arrayable($config['attributes'])) {
                throw new \RuntimeException('Wrong collection configuration, attributes key must be a valid PHP array');
            }
            if (\drewlabs_core_array_is_assoc($config['attributes'])) {
                foreach ($config['attributes'] as $key => $value) {
                    # code...
                    if (!\drewlabs_core_strings_is_str($key)) {
                        throw new \RuntimeException('Wrong collection configuration, attributes key must be an array of PHP strings');
                    }
                    $attributes[$key] = isset($options[$value]) ? $options[$value] : (isset($options[$key]) ? $options[$key] : null);
                }
            } else {
                foreach ($config['attributes'] as $key) {
                    # code...
                    if (!\drewlabs_core_strings_is_str($key)) {
                        throw new \RuntimeException('Wrong collection configuration, attributes key must be an array of PHP strings');
                    }
                    $attributes[$key] = isset($options[$key]) ? $options[$key] : null;
                }
            }
            return new \Drewlabs\Core\Helpers\ValueObject\ModelTypeAttributeRValue([
                'model' => $model,
                'attributes' => $attributes
            ]);
        } else {
            $model = \drewlabs_core_create_php_class_instance($config);
            return new \Drewlabs\Core\Helpers\ValueObject\ModelTypeAttributeRValue([
                'model' => $model,
                'attributes' => ['label' => isset($options['label']) ? $options['label'] : null]
            ]);
        }
    }
}

if (!function_exists('drewlabs_core_create_php_class_instance')) {
    /**
     * Try to create a PHP class by loading it from the container or dynamically creating it by calling it default constructor
     *
     * @param string $clazz
     * @return \stdClass|mixed
     */
    function drewlabs_core_create_php_class_instance($clazz)
    {
        if (!\drewlabs_core_is_valid_php_class($clazz)) {
            throw new \InvalidArgumentException("Provided class name $clazz is not a valid PHP class");
        }
        // Try loading container class if running in Laravel or lumen environment
        if (\drewlabs_core_is_valid_php_class("\\Illuminate\\Container\\Container")) {
            $containerInstance = call_user_func_array(array("\\Illuminate\\Container\\Container", "getInstance"), []);
            return $containerInstance->make($clazz);
        }
        // If there exists a global function that return the container object, call it a build the class with the make method
        if (function_exists('app')) {
            return app()->make($clazz);
        }
        // Call the class default constructor to make the class
        return new $clazz;
    }
}

/*** Below global functions are related to functionnal approch to certain problems */


if (!function_exists('drewlabs_core_fn_compose')) {
    /**
     * Function composition function that apply transformations to the source input in the top -> down 
     * level that the functions appear.
     * It decorates an inner function that accept only single values, and threat array 
     * params as single value. To pass list of parameters as array, use {drewlabs_core_fn_compose_array}
     *
     * @param mixed $source
     * @param \Closure[] ...$funcs
     * @return \Closure|callable 
     */
    function drewlabs_core_fn_compose(...$funcs)
    {
        return function ($source) use ($funcs) {
            return array_reduce(
                $funcs,
                function ($carry, $func) {
                    return $func($carry);
                },
                $source
            );
        };
    }
}


if (!function_exists('drewlabs_core_fn_compose_array')) {
    /**
     * Function composition function that apply transformations to the source input in the top -> down 
     * level that the functions appear.
     * This method decorate an variadic inner function that one or many parameter or an array or parameter
     * If should call with single value, use {drewlabs_core_fn_compose} which does not decorate an inner
     * variadic function and accept only single values, and threat array params as single value
     *
     * @param mixed $source
     * @param \Closure[] ...$funcs
     * @return \Closure|callable 
     */
    function drewlabs_core_fn_compose_array(...$funcs)
    {
        return function (...$source) use ($funcs) {
            return array_reduce(
                $funcs,
                function ($carry, $func) {
                    return $func($carry);
                },
                $source
            );
        };
    }
}

if (!function_exists('drewlabs_core_fn_reverse_compose')) {
    /**
     * Function composition function that apply transformations to the source input in the bottom -> up order 
     * in which functions appear
     *
     * Use {drewlabs_core_fn_reverse_compose_array} instead for multiple parameter value or multiple parameter as array.
     * 
     * @param mixed $source
     * @param \Closure[] ...$funcs
     * @return \Closure|callable 
     */
    function drewlabs_core_fn_reverse_compose(...$funcs)
    {
        return function ($source) use ($funcs) {
            return array_reduce(
                array_reverse($funcs),
                function ($carry, $func) {
                    return $func($carry);
                },
                $source
            );
        };
    }
}

if (!function_exists('drewlabs_core_fn_reverse_compose_array')) {
    /**
     * Function composition function that apply transformations to the source input in the bottom -> up order 
     * in which functions appear
     * 
     * This method decorate an variadic inner function that take one or many parameter or an array or parameters.
     * Use {drewlabs_core_fn_reverse_compose} instead for single parameter value.
     *
     * @param mixed $source
     * @param \Closure[] ...$funcs
     * @return \Closure|callable 
     */
    function drewlabs_core_fn_reverse_compose_array(...$funcs)
    {
        return function (...$source) use ($funcs) {
            return array_reduce(
                array_reverse($funcs),
                function ($carry, $func) {
                    return $func($carry);
                },
                $source
            );
        };
    }
}

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
            return property_exists($obj, $key) ? $obj->{$key} : $default;
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
            $clone->{$key} = $value;
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
            // if (count($args) < 1) {
            //     throw new InvalidArgumentException("__FUNCTION__ requires at least 1 argument");
            // }
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
