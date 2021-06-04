<?php

// use ReflectionAttribute;
// use ReflectionClass;
// use ReflectionFunction;

if (!function_exists('drewlabs_core_get_param_class_name')) {
    /**
     * Get the class name of the given parameter's type, if possible.
     *
     * @param  \ReflectionParameter  $parameter
     * @return string|null
     */
    function drewlabs_core_get_param_class_name($parameter)
    {
        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return;
        }
        $name = $type->getName();
        if (!is_null($class = $parameter->getDeclaringClass())) {
            if ($name === 'self') {
                return $class->getName();
            }

            if ($name === 'parent' && $parent = $class->getParentClass()) {
                return $parent->getName();
            }
        }

        return $name;
    }
}

if (!function_exists('is_param_subclass_of')) {
    /**
     * Determine if the parameter's type is a subclass of the given type.
     *
     * @param  \ReflectionParameter  $parameter
     * @param  string  $className
     * @return bool
     */
    function is_param_subclass_of($parameter, $className)
    {
        $paramClassName = drewlabs_core_get_param_class_name($parameter);
        return ($paramClassName && class_exists($paramClassName))
            ? (new ReflectionClass($paramClassName))->isSubclassOf($className)
            : false;
    }
}

if (!function_exists('drewlabs_core_get_attributes') && version_compare(phpversion(), '8.0', '>=')) {
    /**
     * Return the list of attributes bound to a given class
     *
     * @param \Reflector $reflector
     * @return array|ReflectionAttribute[]
     */
    function drewlabs_core_get_attributes($param)
    {
        if (is_string($param) && class_exists($param)) {
            $reflector = new  ReflectionClass($param);
        } elseif (is_callable($param) || ($param instanceof \Closure) || is_string($param)) {
            $reflector = new ReflectionFunction($param);
        } else if (is_object($param)) {
            $reflector = new ReflectionClass(get_class($param));
        } else {
            $reflector = $param;
        }
        $attributes = $reflector->{'getAttributes'}();
        return $attributes;
    }
}

if (!function_exists('drewlabs_core_get_has_attribute') && version_compare(phpversion(), '8.0', '>=')) {
    /**
     * Check if the parameter has a given attribute
     *
     * @param \Reflector $reflector
     * @return array|ReflectionAttribute[]
     */
    function drewlabs_core_get_has_attribute($param, string $attribute)
    {
        $attributes = drewlabs_core_get_attributes($param);

        if (empty($attributes)) {
            return false;
        }
        return drewlabs_core_array_has(
            array_map(function (ReflectionAttribute $item) {
                return $item->getName();
            }, $attributes),
            $attribute
        );
    }
}

if (!function_exists('drewlabs_core_get_get_attribute') && version_compare(phpversion(), '8.0', '>=')) {
    /**
     * Get a given reflection attribute from a parameter list of attributes
     *
     * @param \Reflector $reflector
     * @return ReflectionAttribute|null
     */
    function drewlabs_core_get_get_attribute($param, string $attribute)
    {
        $attributes = drewlabs_core_get_attributes($param);

        if (empty($attributes)) {
            return null;
        }
        return drewlabs_core_array_get($attributes, function($values) use ($attribute) {
            $matches = array_filter($values, function (ReflectionAttribute $item) use ($attribute) {
                return $item->getName() == $attribute;
            });
            return isset($matches[0]) ? $matches[0] : null;
        }, null);
    }
}
