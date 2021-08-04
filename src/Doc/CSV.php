<?php

use Drewlabs\Core\Helpers\Exceptions\CSVHeaderCountException;
use Drewlabs\Core\Helpers\Exceptions\FileNotFoundException;

class CSV
{

    public static function read(
        string $content,
        bool $fistLineAsHeader = true,
        array $headers = [],
        bool $assoc = true,
        string $separator = ",",
        string $enclosure = "\"",
        string $escape = '\\'
    ) {
        if ($content) {
            // Transform string into an array, using newline separator
            $array = explode(PHP_EOL, $content);
            $out = [];
            // Read CSV file contents
            foreach ($array as $value) {
                $out[] = str_getcsv($value, $separator, $enclosure, $escape);
            }
            // If emty content return an empty array
            if (empty($out)) {
                return $out;
            }
            $headers_ = $fistLineAsHeader ? $out[0] : $headers;
            // Check if column count match between data and headers
            if (!empty($headers_) && (count($headers_) !== count($out[1]))) {
                throw new CSVHeaderCountException('CSV header count does not match data column count');
            }
            if (!empty($headers_ ?? [])) {
                $out =  array_reduce(array_slice($out, 1), function ($carry, $curr) use ($headers_, $assoc) {
                    $value = new \stdClass;
                    $curr = array_values($curr);
                    foreach (array_keys($headers_) as $key) {
                        # code...
                        $value->{$headers_[$key]} = $curr[$key] ?? null;
                    }
                    $carry[] = $assoc ? (array)$value : $value;
                }, []);
            }
            return $out;
        }
        throw new InvalidArgumentException('Function requires parameter 1 to be a valid string');
    }

    public static function readFromFile(
        string $path,
        bool $firstLineAsHeader = true,
        array $headers = [],
        bool $assoc = true,
        string $separator = ",",
        string $enclosure = "\"",
        string $escape = '\\'
    ) {
        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf("Specified file path %s could not be found", $path));
        }
        $content = file_get_contents($path);
        return self::read($content, $firstLineAsHeader, $headers, $assoc, $separator, $enclosure, $escape);
    }
}
