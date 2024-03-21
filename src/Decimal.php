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

class Decimal
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var int
     */
    private $decimal;

    /**
     * Creates a new float value instance.
     */
    public function __construct(float $value, int $decimal, string $delimiter)
    {
        $this->value = $value;
        $this->decimal = $decimal;
        $this->delimiter = $delimiter;
    }

    /**
     * Returns the string representation of the current instance.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format($this->decimal, $this->delimiter);
    }

    /**
     * @param string|float $value
     * Creates a new float value instance.
     */
    public static function new($value)
    {
        return new self(floatval($value), 0, ' ');
    }

    /**
     * Format the value with provided delimiter.
     *
     * @param string $delimiter
     *
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
        $nb = \strlen($output);
        for ($i = 1; $i < 4; ++$i) {
            if ($input >= 10 ** (3 * $i)) {
                $output = sprintf('%s%s%s', substr($output, 0, ($nb - (3 * $i))), $delimiter, substr($output, $nb - 3 * $i, $nb));
            }
        }
        if ($decimal > 0) {
            $decim = '';
            for ($j = 0; $j < $decimal - \strlen((string) $inDecimal); ++$j) {
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
}
