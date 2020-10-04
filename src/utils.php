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
