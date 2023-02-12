<?php

namespace Drewlabs\Core\Helpers;

class Bytes
{
    /**
     * 
     * @var float
     */
    private $bytes;

    /**
     * 
     * @var mixed
     */
    private $delimiter;

    /**
     * Creates class instance
     * 
     * @param float $bytes 
     * @param mixed $delimiter 
     */
    public function __construct(float $bytes, $delimiter)
    {
        $this->bytes = $bytes;
        $this->delimiter = $delimiter;
    }

    /**
     * Creates a new bytes instance
     * 
     * @param float $bytes 
     * @return self 
     */
    public static function new(float $bytes)
    {
        return new self($bytes, ',');
    }

    /**
     * Human readable string representation of the bytes instance
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
                $result = str_replace('.', $this->delimiter, (string) (round($result, 2))) . ' ' . $item['unit'];
                break;
            }
        }
        return (string)$result;
    }
}
