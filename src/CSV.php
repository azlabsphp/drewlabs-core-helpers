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

use Closure;
use Exception;
use InvalidArgumentException;

class CSV
{
    /**
     * Read data from string using csv standard
     * 
     * @param string $content 
     * @param bool $fistLineAsHeader 
     * @param array $headers 
     * @param bool $assoc 
     * @param null|Closure $headerTransformer 
     * @param string $separator 
     * @param string $enclosure 
     * @param string $escape 
     * @return array 
     * @throws InvalidArgumentException 
     */
    public static function read(
        string $content,
        bool $fistLineAsHeader = true,
        array $headers = [],
        bool $assoc = true,
        ?\Closure $headerTransformer = null,
        string $separator = ',',
        string $enclosure = '"',
        string $escape = '\\'
    ) {
        if ($content) {
            // Transform string into an array, using newline separator
            $array = explode(\PHP_EOL, (string) $content);
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
            $headers_ = $headerTransformer ? $headerTransformer($headers_) : $headers_;
            if (!\is_array($headers_)) {
                throw new \InvalidArgumentException('Transformer fn must return an array of header keys');
            }
            // Check if column count match between data and headers
            if (!empty($headers_) && (\count($headers_) !== \count($out[1]))) {
                throw new \InvalidArgumentException('CSV header count does not match data column count');
            }
            if (!empty($headers_ ?? [])) {
                $out = array_reduce(\array_slice($out, 1), static function ($carry, $curr) use ($headers_, $assoc) {
                    $value = new \stdClass();
                    $curr = array_values($curr);
                    foreach (array_keys($headers_) as $key) {
                        // code...
                        $value->{$headers_[$key]} = $curr[$key] ?? null;
                    }
                    $carry[] = $assoc ? (array) $value : $value;

                    return $carry;
                }, []);
            }

            return $out;
        }
        throw new \InvalidArgumentException('Function requires parameter 1 to be a valid string');
    }

    /**
     * Read data from file base on csv standard
     * 
     * @param string $path 
     * @param bool $firstLineAsHeader 
     * @param array $headers 
     * @param bool $assoc 
     * @param null|Closure $headerTransformer 
     * @param string $separator 
     * @param string $enclosure 
     * @param string $escape 
     * @return array 
     * @throws Exception 
     * @throws InvalidArgumentException 
     */
    public static function readFromFile(
        string $path,
        bool $firstLineAsHeader = true,
        array $headers = [],
        bool $assoc = true,
        ?\Closure $headerTransformer = null,
        string $separator = ',',
        string $enclosure = '"',
        string $escape = '\\'
    ) {
        if (!file_exists($path)) {
            throw new Exception(sprintf('Specified file path %s could not be found', $path));
        }
        $content = file_get_contents($path);

        return self::read(
            $content,
            $firstLineAsHeader,
            $headers,
            $assoc,
            $headerTransformer,
            $separator,
            $enclosure,
            $escape
        );
    }
}
