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

namespace Drewlabs\Core\Helpers;

class Reflector
{
    /**
     * Checks if a class or it instance implements a given interface.
     *
     * @param string|object $class
     *
     * @return bool
     */
    public static function implements($class, string $abstract)
    {
        return \in_array($abstract, class_implements($class), true);
    }

    /**
     * Get the class name of the given parameter's type, if possible.
     *
     * @return string|null
     */
    public static function getParameterClass(\ReflectionParameter $parameter)
    {
        $type = $parameter->getType();
        if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
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
            ? (new \ReflectionClass($name))->isSubclassOf($className)
            : false;
    }

    /**
     * Return the list of attributes bound to a given PHP variable, method/function or class.
     *
     * @param mixed $source
     *
     * @return ReflectionAttribute[]
     */
    public static function getAttributes($source)
    {
        if (\PHP_VERSION_ID < 80000) {
            throw new \BadMethodCallException(__METHOD__.' requires a PHP version 8 or later.');
        }
        if (\is_string($source) && class_exists($source)) {
            $reflector = new \ReflectionClass($source);
        } elseif (\is_callable($source) || ($source instanceof \Closure) || \is_string($source)) {
            $reflector = new \ReflectionFunction($source);
        } elseif (\is_object($source)) {
            $reflector = new \ReflectionClass($source);
        } else {
            $reflector = $source;
        }

        return $reflector->getAttributes();
    }

    /**
     * Check if the parameter has a given attribute.
     *
     * @param mixed           $param
     * @param string|string[] $attribute
     *
     * @return bool
     */
    public static function hasAttribute($param, $attribute)
    {
        if (\PHP_VERSION_ID < 80000) {
            throw new \BadMethodCallException(__METHOD__.' requires a PHP version 8 or later.');
        }
        // We make sure the provided list of attribute argument is a list
        // of attribute name a.k.a PHP strings
        $attribute = Arr::filter(
            Arr::wrap($attribute),
            static function ($current) {
                return \is_string($current);
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
     *
     * @throws \BadMethodCallException
     *
     * @return ReflectionAttribute|null
     */
    public static function getAttribute($type, string $name)
    {
        if (\PHP_VERSION_ID < 80000) {
            throw new \BadMethodCallException(__METHOD__.' requires a PHP version 8 or later.');
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
     * Recursively get all traits of the user provided classes.
     *
     * @param object|string $blueprint
     * @param bool          $autoload
     *
     * @return string[]|false
     */
    public static function usesRecursive($blueprint, $autoload = true)
    {
        if (null === $blueprint) {
            return false;
        }
        $traits = array_merge(class_uses($blueprint, $autoload), []);
        while ($blueprint = get_parent_class($blueprint)) {
            $traits = array_merge(class_uses($blueprint, $autoload), $traits);
        }
        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }

    /**
     * Check if class has a list of PHP traits.
     *
     * @param string|object   $blueprint
     * @param string|string[] $mixins
     * @param bool            $autoload
     *
     * @return void
     */
    public static function hasMixins($blueprint, $mixins, $autoload = true)
    {
        $mixins = Arr::wrap($mixins);
        // We make sure the provided list of mixins argument is a list
        // of mixins name a.k.a PHP strings
        $mixins = Arr::filter(Arr::wrap($mixins), static function ($current) {
            return \is_string($current);
        });
        if (empty($mixins)) {
            return false;
        }

        return \count(array_intersect(static::usesRecursive($blueprint, $autoload), $mixins)) === \count($mixins);
    }

    // #region Callable reflexion helpers
    /**
     * This is a PHP 7.4 compatible implementation of is_callable.
     *
     * @param mixed $object
     * @param bool  $syntax_only
     *
     * @return bool
     */
    public static function isCallable($object, $syntax_only = false)
    {
        if (!\is_array($object)) {
            return \is_callable($object, $syntax_only);
        }

        if ((!isset($object[0]) || !isset($object[1])) || !\is_string($object[1] ?? null)) {
            return false;
        }

        if ($syntax_only && (\is_string($object[0]) || \is_object($object[0])) && \is_string($object[1])) {
            return true;
        }

        [$class, $method] = [\is_object($object[0]) ? \get_class($object[0]) : $object[0], $object[1]];

        if (!class_exists($class)) {
            return false;
        }

        if (method_exists($class, $method)) {
            return (new \ReflectionMethod($class, $method))->isPublic();
        }

        if (\is_object($object[0]) && method_exists($class, '__call')) {
            return (new \ReflectionMethod($class, '__call'))->isPublic();
        }

        if (!\is_object($object[0]) && method_exists($class, '__callStatic')) {
            return (new \ReflectionMethod($class, '__callStatic'))->isPublic();
        }

        return false;
    }

    /**
     * Checks if the given $object is callable, but not a string.
     *
     * @param mixed $object
     *
     * @return bool
     */
    public static function isClosure($object)
    {
        return !\is_string($object) && \is_callable($object);
    }
    // #endregion Callable reflexion helpers

    // #region Class instanciation
    /**
     * Instanciate class without invoking class constructor.
     *
     * @throws \ReflectionException
     *
     * @return object
     */
    public static function newInstanceWithoutConstructor(string $blueprint)
    {
        return (new \ReflectionClass($blueprint))->newInstanceWithoutConstructor();
    }

    /**
     * Create class instance with provided parameters.
     *
     * @param mixed $args
     *
     * @throws \ReflectionException
     *
     * @return object|T
     */
    public static function newInstance(string $blueprint, ...$args)
    {
        return (new \ReflectionClass($blueprint))->newInstance(...$args);
    }
    // #endregion Class instanciation

    // #region Instance properties

    /**
     * Recursively get the value of an attribute in the provided object
     * Nested attributes must be separated with the '.' ponctuation.
     *
     * @param object|array $object
     * @param mixed        $default
     *
     * @return void
     */
    public static function getPropertyValue($object, string $key, $default = null)
    {
        if (\is_string($key) && str_contains($key, '.')) {
            $keys = explode('.', $key);

            return array_reduce($keys, static function ($carry, $current) use ($default) {
                if ($carry === $default) {
                    return $carry;
                }

                return self::_getPropertyValue($carry, $current, $default);
            }, $object);
        }

        return self::_getPropertyValue($object, $key, $default);
    }

    /**
     * Try recursively to find the atribute of the object that need to be setted.
     *
     * @param object|array $object
     * @param mixed        $value
     *
     * @return mixed
     */
    public static function setPropertyValue($object, string $key, $value = null)
    {
        if (\is_string($key) && str_contains($key, '.')) {
            $cache = [];
            $keys = explode('.', $key);
            $last = \count($keys) - 1;
            $top = self::_getPropertyValue($object, $keys[0], null);
            if (null === $top) {
                return $object;
            }
            $i = 1;
            $current = self::clone($top);
            // Build the attributes tree into a cache variable
            while ($i <= $last) {
                // code...
                if ($i === $last) {
                    $current = $value;
                } else {
                    $current = self::_getPropertyValue($current, $keys[$i], null);
                }
                if (null === $current) {
                    return $object;
                }
                $cache[] = ['key' => $keys[$i], 'value' => self::clone($current)];
                ++$i;
            }
            // Set the value of the last item in the cache to equal to user provided value
            $cache = array_reverse($cache);
            // The last key of the reverse cache values is the name of the attribute to set
            $rkey = $cache[0]['key'];
            $rvalue = $cache[0]['value'];
            // Loop through the cache items in the reverse order and rebuild the object tree
            for ($index = 0; $index < \count($cache); ++$index) {
                // code...
                if (($index + 1) === \count($cache)) {
                    break;
                }
                $rvalue = self::_setPropertyValue($cache[$index + 1]['value'], $cache[$index]['key'], $rvalue);
                $rkey = $cache[$index + 1]['key'];
            }

            return self::_setPropertyValue($object, $keys[0], self::_setPropertyValue($top, $rkey, $rvalue));
        }

        return self::_setPropertyValue($object, $key, $value);
    }

    /**
     * Get a property from an object of type array or \stdClass using the provided
     * name or returns the {$default} value if provided or {NULL}.
     *
     * @param \stdClass|array|object $object
     * @param mixed|null             $default
     *
     * @return mixed
     */
    public static function _getPropertyValue($object, string $key, $default = null)
    {
        if (\is_array($object) || ($object instanceof \ArrayAccess)) {
            return \array_key_exists($key, $object) ? $object[$key] : $default;
        }
        if (!\is_object($object)) {
            // Throws an execption
            throw new \InvalidArgumentException('Reflector::getPropertyValue requires a parameter of type array or object');
        }
        $value = $default;
        // Breaking property accessibility of the OOP concept to avoid
        // unexpected private or protected properties errors
        $reflector = new \ReflectionObject($object);
        if ($reflector->hasProperty($key)) {
            $property = $reflector->getProperty($key);
            // Get the accessibility value of the property
            if (!$property->isPublic()) {
                $property->setAccessible(true);
            }
            $value = $property->getValue($object);
            if (!$property->isPublic()) {
                $property->setAccessible(false);
            }
        } else {
            $value = $object->{$key} ?? $default;
        }

        return $value;
    }

    /**
     * Set the value of a given array or object and return the updated object.
     *
     * @param \stdClass|array|object $object
     * @param mixed                  $value
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public static function _setPropertyValue($object, string $key, $value)
    {
        if (\is_array($object) || ($object instanceof \ArrayAccess)) {
            return array_merge($object, [$key => $value]);
        }
        if (!\is_object($object)) {
            // Throws an execption
            throw new \InvalidArgumentException('Reflector::setPropertyValue method requires parameter 1 to be of type array or object');
        }

        if (method_exists($object, 'copy')) {
            return $object->copy([$key => $value]);
        }
        // Create a clone copy of the object
        // Creating clone does not guarantee that nested object are cloned as well
        // We rely on the the implementation of the object class to provide a proper
        // clone method that performs a deep copy of the object
        $clone = self::clone($object);
        $reflector = new \ReflectionObject($clone);
        if ($reflector->hasProperty($key)) {
            // Breaking property accessibility of the OOP concept to avoid
            // unexpected private or protected properties errors [Cannot access private property]
            $property = $reflector->getProperty($key);
            // Get the accessibility value of the property
            if (!$property->isPublic()) {
                $property->setAccessible(true);
            }
            $property->setValue($clone, $value);
            if (!$property->isPublic()) {
                $property->setAccessible(false);
            }
        } else {
            $clone->{$key} = $value;
        }

        return $clone;
    }

    /**
     * Create an operator function that will be use to get attribute on a given array or object.
     *
     * @param mixed $default
     *
     * @return \Closure(mixed $obj): mixed
     */
    public static function propertyGetter(string $key, $default = null)
    {
        return static function ($obj) use ($key, $default) {
            return self::assertArgsDecorator(static function () use ($obj, $key, $default) {
                return self::getPropertyValue($obj, $key, $default);
            })($key, $default);
        };
    }

    /**
     * Create an operator function that will be use to set attribute on a given array or object.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Closure(mixed $obj): mixed
     */
    public static function propertySetter($key, $value = null)
    {
        return static function ($obj) use ($key, $value) {
            // If the provided key is an array, apply a reducer for each key in the array
            if (\is_array($key)) {
                return array_reduce($key, static function ($carry, $current) {
                    return self::assertArgsDecorator(static function () use ($carry, $current) {
                        return self::setPropertyValue($carry, ...$current);
                    })(...$current);
                }, $obj);
            }

            return self::assertArgsDecorator(static function () use ($obj, $key, $value) {
                return self::setPropertyValue($obj, $key, $value);
            })($key, $value);
        };
    }
    // #endregion Instance properties

    // #region Miscellanous
    /**
     * Creates a copy or a clone of php data structure.
     *
     * @param mixed $object
     *
     * @return mixed
     */
    public static function clone($object)
    {
        return \is_object($object) ? clone $object : (\is_array($object) ? array_merge([], $object) : $object);
    }

    /**
     * Validate the first argument [key] of the [createPropertyGetter] function.
     *
     * @return \Closure|callable
     */
    private static function assertArgsDecorator(\Closure $callback)
    {
        return static function (...$args) use ($callback) {
            if (0 === \count($args)) {
                return $callback(...$args);
            }
            $key = $args[0];
            if (!\is_string($key) && !\is_int($key)) {
                throw new \InvalidArgumentException('$key paramater must be a string or an array numeric index');
            }

            return $callback(...$args);
        };
    }
    // #endregion Miscellanous
}
