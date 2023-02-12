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

class Bytes
{
    /**
     * @var float
     */
    private $bytes;

    /**
     * @var mixed
     */
    private $delimiter;

    /**
     * Creates class instance.
     *
     * @param mixed $delimiter
     */
    public function __construct(float $bytes, $delimiter)
    {
        $this->bytes = $bytes;
        $this->delimiter = $delimiter;
    }

    /**
     * Human readable string representation of the bytes instance.
     *
     * @return string
     */
    public function __toString()
    {
        $result = $this->bytes;
        $bytes = [
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

        foreach ($bytes as $item) {
            if ($this->bytes >= $item['value']) {
                $result = $this->bytes / $item['value'];
                $result = str_replace('.', $this->delimiter, (string) (round($result, 2))).' '.$item['unit'];
                break;
            }
        }

        return (string) $result;
    }

    /**
     * Creates a new bytes instance.
     *
     * @return self
     */
    public static function new(float $bytes)
    {
        return new self($bytes, ',');
    }
}
