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

use Drewlabs\Core\Helpers\ValueObject\ModelTypeAttributeRValue;

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('DREWLABS_CORE_ORD_ASC', 'ASC');
define('DREWLABS_CORE_ORD_DESC', 'DESC');

if (!function_exists('drewlabs_core_compare_numeric')) {
    /**
     * Compare two variable of numeric type.
     *
     * @param int|float|float $a
     * @param int|float|float $b
     *
     * @return int
     */
    function drewlabs_core_compare_numeric($a, $b, $order)
    {
        return (DREWLABS_CORE_ORD_DESC === $order) ? ($a - $b >= 0 ? 1 : -1) : ($a - $b >= 0 ? -1 : 1);
    }
}

if (!function_exists('drewlabs_core_compare_str')) {
    /**
     * Compare two variable of numeric type.
     *
     * @param int|float|float $a
     * @param int|float|float $b
     *
     * @return int
     */
    function drewlabs_core_compare_str($a, $b, $order)
    {
        return (DREWLABS_CORE_ORD_DESC === $order) ? ($a - $b >= 0 ? 1 : -1) : ($a - $b >= 0 ? -1 : 1);
    }
}

if (!function_exists('drewlabs_core_is_same')) {
    /**
     * Verify if two variables are same.
     *
     * @param string $a
     * @param string $b
     *
     * @return bool
     */
    function drewlabs_core_is_same($a, $b, $strict = false)
    {
        return $strict ? $a === $b : $a === $b;
    }
}

if (!function_exists('drewlabs_core_convert_size_to_human_readable')) {

    /**
     * Converts bytes into human readable file size.
     *
     * @param string|float $bytes
     *
     * @return string human readable file size (2,87 Мб)
     *
     * @author AzandrewLabs
     */
    function drewlabs_core_convert_size_to_human_readable($bytes, $separator = '.')
    {
        $bytes = (float) $bytes;
        $arBytes = [
            0 => [
                'unit' => 'TB',
                'value' => 1024 ** 4,
            ],
            1 => [
                'unit' => 'GB',
                'value' => 1024 ** 3,
            ],
            2 => [
                'unit' => 'MB',
                'value' => 1024 ** 2,
            ],
            3 => [
                'unit' => 'KB',
                'value' => 1024,
            ],
            4 => [
                'unit' => 'B',
                'value' => 1,
            ],
        ];

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem['value']) {
                $result = $bytes / $arItem['value'];
                $result = str_replace('.', $separator, (string) (round($result, 2))).' '.$arItem['unit'];
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
     * @param \Closure|mixed $value
     *
     * @return mixed
     */
    function drewlabs_core_value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('drewlabs_core_is_valid_php_class')) {

    /**
     * Checks if a provided class is a valid PHP class autoloadable.
     *
     * @param string $clazz
     *
     * @return bool
     */
    function drewlabs_core_is_valid_php_class($clazz)
    {
        return null !== $clazz && drewlabs_core_strings_contains($clazz, ['\\', '\\\\']) && class_exists($clazz);
    }
}

if (!function_exists('drewlabs_core_build_model_type_attributes_rvalue_object')) {

    /**
     * Helper function that tries to build a model attribute and model itself using provided options.
     *
     * @param string|array $config
     *
     * @return ModelTypeAttributeRValue
     */
    function drewlabs_core_build_model_type_attributes_rvalue_object($config, array $options)
    {
        if (drewlabs_core_array_is_arrayable($config)) {
            if (!isset($config['class'])) {
                throw new \RuntimeException('Wrong collection configuration, class key is required on the collection configuration if an array is provided');
            }
            $attributes = [];
            $model = drewlabs_core_create_php_class_instance($config['class']);
            if (!isset($config['attributes'])) {
                $attributes = ['label' => $options['label'] ?? null];
            }
            if (!drewlabs_core_array_is_arrayable($config['attributes'])) {
                throw new \RuntimeException('Wrong collection configuration, attributes key must be a valid PHP array');
            }
            if (drewlabs_core_array_is_assoc($config['attributes'])) {
                foreach ($config['attributes'] as $key => $value) {
                    // code...
                    if (!drewlabs_core_strings_is_str($key)) {
                        throw new \RuntimeException('Wrong collection configuration, attributes key must be an array of PHP strings');
                    }
                    $attributes[$key] = $options[$value] ?? ($options[$key] ?? null);
                }
            } else {
                foreach ($config['attributes'] as $key) {
                    // code...
                    if (!drewlabs_core_strings_is_str($key)) {
                        throw new \RuntimeException('Wrong collection configuration, attributes key must be an array of PHP strings');
                    }
                    $attributes[$key] = $options[$key] ?? null;
                }
            }

            return new ModelTypeAttributeRValue([
                'model' => $model,
                'attributes' => $attributes,
            ]);
        }
        $model = drewlabs_core_create_php_class_instance($config);

        return new ModelTypeAttributeRValue([
            'model' => $model,
            'attributes' => ['label' => $options['label'] ?? null],
        ]);
    }
}

if (!function_exists('drewlabs_core_create_php_class_instance')) {
    /**
     * Try to create a PHP class by loading it from the container or dynamically creating it by calling it default constructor.
     *
     * @param string $clazz
     *
     * @return \stdClass|mixed
     */
    function drewlabs_core_create_php_class_instance($clazz)
    {
        if (!drewlabs_core_is_valid_php_class($clazz)) {
            throw new \InvalidArgumentException("Provided class name $clazz is not a valid PHP class");
        }
        // If there exists a global function that return the container object, call it a build the class with the make method
        $app = function_exists('app') ? call_user_func('app') : null;

        return $app ?
            (method_exists($app, 'make') ?
                $app->make($clazz) :
                $app->get($clazz)) :
            new $clazz();
    }
}

if (!function_exists('drewlabs_core_is_empty')) {
    /**
     * Provides a wrapper arround PHP empty method. It allows to.
     *
     * check if an object is empty.
     *
     * By definition, an object is empty is it properties are null or not set
     *
     * @param object|array|string $value
     *
     * @return bool
     */
    function drewlabs_core_is_empty($value)
    {
        $is_object_empty = static function ($obj) {
            if (method_exists($obj, 'isEmpty')) {
                return call_user_func([$obj, 'isEmpty'], []);
            }
            if (empty(get_object_vars($obj))) {
                return true;
            }
            // Iterate over object properties and return false if one property is set
            foreach ($obj as $v) {
                if (isset($v)) {
                    return false;
                }
            }
            // Return true if all properties of the object are not set
            return true;
        };
        if (is_object($value)) {
            return $is_object_empty($value);
        }

        return empty($value);
    }
}

if (!function_exists('build_data_provider')) {

    /**
     * Helper global method for building data provider based on a closure or a class name.
     *
     * @param \Closure|string $callback
     * @param array           $params
     *
     * @return DataProviderInterface
     */
    function build_data_provider($callback, $params = [])
    {
        $provider = null;
        if (is_string($callback) && (true === drewlabs_core_strings_contains($callback, ['\\', '\\\\']))) {
            // Add condition check for global application helper
            $app = function_exists('app') ? call_user_func('app') : null;
            $provider = $app ?
                (method_exists($app, 'make') ?
                    $app->make($callback) :
                    $app->get($callback)) :
                new $callback();
        }
        if ($callback instanceof \Closure) {
            $provider = $callback(...$params);
        }

        return $provider;
    }
}

if (!function_exists('drewlabs_core_get')) {

    /**
     * Get value from an object or array using the . seperator.
     *
     * @param mixed      $target
     * @param mixed      $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    function drewlabs_core_get($target, $key, $default = null)
    {
        if (null === $key) {
            return $target;
        }
        $key = is_array($key) ? $key : explode('.', (string) $key);
        foreach ($key as $i => $segment) {
            unset($key[$i]);
            if (null === $segment) {
                return $target;
            }
            if ('*' === $segment) {
                if (method_exists($target, 'all')) {
                    $target = $target->all();
                } elseif (!is_array($target)) {
                    return $default instanceof \Closure ? $default() : $default;
                }
                $result = [];
                foreach ($target as $item) {
                    $result[] = drewlabs_core_get($item, $key);
                }

                return in_array('*', $key, true) ? drewlabs_core_iter_collapse($result) : $result;
            }
            if (drewlabs_core_array_is_arrayable($target)) {
                $target = drewlabs_core_array_get($target, $segment);
            } elseif (
                is_object($target) &&
                (null !== ($target_ = drewlabs_core_get_attribute($target, $segment)))
            ) {
                $target = $target_;
            } else {
                return $default instanceof \Closure ? $default() : $default;
            }
        }

        return $target;
    }
}
