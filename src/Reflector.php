<?php

namespace Drewlabs\Core\Helpers;

use BadMethodCallException;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

class Reflector
{

    /**
     * Checks if a class or it instance implements a given interface
     * 
     * @param string|object $class 
     * @param string $abstract 
     * @return bool 
     */
    public static function implements($class, string $abstract)
    {
        return in_array($abstract, class_implements($class));
    }

    /**
     * Get the class name of the given parameter's type, if possible.
     *
     * @param \ReflectionParameter $parameter
     *
     * @return string|null
     */
    public static function getParameterClass(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return;
        }
        $name = $type->getName();
        if (null !== ($class = $parameter->getDeclaringClass())) {
            if ('self' === $name) {
                return $class->getName();
            }

            if ('parent' === $name && $parent = $class->getParentClass()) {
                return $parent->getName();
            }
        }

        return $name;
    }

    /**
     * Determine if the parameter's type is a subclass of the given type.
     *
     * @param \ReflectionParameter $parameter
     * @param string               $className
     *
     * @return bool
     */
    public static function isSubclassOf($parameter, $className)
    {
        $name = static::getParameterClass($parameter);

        return ($name && class_exists($name))
            ? (new ReflectionClass($name))->isSubclassOf($className)
            : false;
    }

    /**
     * Return the list of attributes bound to a given PHP variable, method/function or class
     * 
     * @param mixed $source 
     * @return ReflectionAttribute[] 
     */
    public static function getAttributes($source)
    {
        if (\PHP_VERSION_ID < 80000) {
            throw new BadMethodCallException(__METHOD__ . ' requires a PHP version 8 or later.');
        }
        if (is_string($source) && class_exists($source)) {
            $reflector = new ReflectionClass($source);
        } elseif (is_callable($source) || ($source instanceof \Closure) || is_string($source)) {
            $reflector = new ReflectionFunction($source);
        } elseif (is_object($source)) {
            $reflector = new ReflectionClass($source);
        } else {
            $reflector = $source;
        }
        return $reflector->getAttributes();
    }

    /**
     * Check if the parameter has a given attribute.
     * 
     * @param mixed $param 
     * @param string|string[] $attribute 
     * @return bool 
     */
    public static function hasAttribute($param, $attribute)
    {
        if (\PHP_VERSION_ID < 80000) {
            throw new BadMethodCallException(__METHOD__ . ' requires a PHP version 8 or later.');
        }
        // We make sure the provided list of attribute argument is a list
        // of attribute name a.k.a PHP strings
        $attribute = Arr::filter(
            Arr::wrap($attribute),
            function ($current) {
                return is_string($current);
            }
        );
        if (empty($attribute)) {
            return false;
        }
        $attributes = static::getAttributes($param);
        if (empty($attributes)) {
            return false;
        }
        return Arr::containsAll(
            array_map(static function (\ReflectionAttribute $item) {
                return $item->getName();
            }, $attributes),
            Arr::wrap($attribute)
        );
    }

    /**
     * Get a given reflection attribute from a parameter list of attributes.
     * 
     * @param mixed $type 
     * @param string $name 
     * @return ReflectionAttribute|null
     * @throws BadMethodCallException 
     */
    public static function getAttribute($type, string $name)
    {
        if (\PHP_VERSION_ID < 80000) {
            throw new BadMethodCallException(__METHOD__ . ' requires a PHP version 8 or later.');
        }
        $attributes = static::getAttributes($type);
        if (empty($attributes)) {
            return null;
        }
        return Arr::get($attributes, static function ($values) use ($name) {
            $matches = array_filter(
                $values,
                static function (\ReflectionAttribute $item) use ($name) {
                    return $item->getName() === $name;
                }
            );
            return Arr::first($matches);
        }, null);
    }

    /**
     * Recursively get all traits of the user provided classes
     * 
     * @param object|string $clazz 
     * @param bool $autoload 
     * @return string[]|false 
     */
    public static function usesRecursive($clazz, $autoload = true)
    {
        if (null === $clazz) {
            return false;
        }
        $traits = array_merge(class_uses($clazz, $autoload), []);
        while ($clazz = get_parent_class($clazz)) {
            $traits = array_merge(class_uses($clazz, $autoload), $traits);
        }
        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }
        return array_unique($traits);
    }

    /**
     * 
     * @param string|object $clazz 
     * @param string|string[] $mixins 
     * @param bool $autoload 
     * @return void 
     */
    public static function hasMixins($clazz, $mixins, $autoload = true)
    {
        $mixins = Arr::wrap($mixins);
        // We make sure the provided list of mixins argument is a list
        // of mixins name a.k.a PHP strings
        $mixins = Arr::filter(
            Arr::wrap($mixins),
            function ($current) {
                return is_string($current);
            }
        );
        if (empty($mixins)) {
            return false;
        }
        return Arr::containsAll(static::usesRecursive($clazz, $autoload), $mixins);
    }
}
