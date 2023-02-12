<?php

namespace Drewlabs\Core\Helpers;

/**
 * Class provides a string representation of a floating point value
 * 
 * @package Drewlabs\Core\Helpers
 */
class FloatValue
{
    /**
     * 
     * @var float
     */
    private $value;

    /**
     * 
     * @var string
     */
    private $delimiter;

    /**
     * 
     * @var int
     */
    private $decimal;

    /**
     * Creates a new float value instance
     * 
     * @param float $value 
     */
    public function __construct(float $value, int $decimal, string $delimiter)
    {
        $this->value = $value;
        $this->decimal = $decimal;
        $this->delimiter = $delimiter;
    }

    /**
     * Creates a new float value instance
     * 
     * @param float $value 
     */
    public static function new(float $value)
    {
        return new self($value, 0, ' ');
    }

    /**
     * Format the value with provided delimiter
     * 
     * @param int $decimal 
     * @param string $delimiter 
     * @return string 
     */
    public function format(int $decimal = 0, $delimiter = ' ')
    {
        if (null === $this->value) {
            return '0';
        }
        $inDecimal = round(10 ** $decimal * (abs($this->value) - floor(abs($this->value))), 0);
        $input = floor(abs($this->value));
        if ((0 === $decimal) || ($inDecimal === 10 ** $decimal)) {
            $input = floor(abs($this->value));
            $inDecimal = 0;
        }
        $output = sprintf('%d', $input);
        $nb = strlen($output);
        for ($i = 1; $i < 4; ++$i) {
            if ($input >= 10 ** (3 * $i)) {
                $output = sprintf('%s%s%s', substr($output, 0, ($nb - (3 * $i))), $delimiter, substr($output, $nb - 3 * $i, $nb));
            }
        }
        if ($decimal > 0) {
            $decim = '';
            for ($j = 0; $j < $decimal - strlen((string) $inDecimal); ++$j) {
                $decim .= '0';
            }
            $inDecimal = sprintf('%s%s', $decim, (string) $inDecimal);
            $output = sprintf('%s%s%s', $output, '.', (string) $inDecimal);
        }
        if (((float) $this->value) < 0) {
            $output = sprintf('%s%s', '-', $output);
        }
        return $output;
    }

    /**
     * Returns the string representation of the current instance
     * 
     * @param int $decimal 
     * @param string $delimiter 
     * @return string 
     */
    public function __toString()
    {
        return $this->format($this->decimal, $this->delimiter);
    }
}
