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
     * level that the functions appear
     *
     * @param mixed $source
     * @param \Closure[] ...$funcs
     * @return \Closure|callable 
     */
    function drewlabs_core_fn_compose(...$funcs)
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
     * @param mixed $source
     * @param \Closure[] ...$funcs
     * @return \Closure|callable 
     */
    function drewlabs_core_fn_reverse_compose(...$funcs)
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
     * @return void
     */
    function drewlabs_core_recursive_set_attribute($obj, $key, $value = null)
    {
        throw new \RuntimeException('Recursive setter not implemented');
        // if (is_string($key) && \drewlabs_core_strings_contains($key, '.')) {
        //     $keys = \drewlabs_core_strings_to_array($key, '.');
        //     $lastIndex = count($keys) - 1;
        //     $i = 0;
        //     $current = null;
        //     while ($i < $lastIndex) {
        //         # code...
        //         $rvalue = \drewlabs_core_get_attribute($obj, $keys[$i], null);
        //         if (is_null($rvalue)) {
        //             break;
        //         }
        //         $current = $rvalue;
        //         $i++;
        //     }
        //     $current = \drewlabs_core_set_attribute($current, $keys[$lastIndex], $value);
        // }
        // return \drewlabs_core_set_attribute($obj, $key, $value);
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
            $clone = clone $obj;
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
     * @param [type] $func
     * @return void
     */
    function drewlabs_core_validate_attribute_name($func)
    {
        if (!($func instanceof \Closure) || !is_callable($func)) {
            throw new InvalidArgumentException('Function parameter must be a PHP Closure');
        }
        return function (...$args) use ($func) {
            if (count($args) < 1) {
                throw new InvalidArgumentException("__FUNCTION__ requires at least 2 argument");
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
     * @return string
     */
    function drewlabs_core_create_attribute_getter()
    {
        return \drewlabs_core_validate_attribute_name(function ($key, $default = null) {
            return \drewlabs_core_create_attribute_getter_unsafe($key, $default);
        });
    }
}

if (!function_exists('drewlabs_core_create_attribute_getter_unsafe')) {
    /**
     * Create an operator function that does not enforce the rules for the attribute name
     * being either a string or an integer
     *
     * @param string|int $key
     * @param mixed|null $default
     * @return \Closure
     */
    function drewlabs_core_create_attribute_getter_unsafe($key, $default = null)
    {
        return function ($obj) use ($key, $default) {
            return \drewlabs_core_recursive_get_attribute($obj, $key, $default);
        };
    }
}
