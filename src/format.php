<?php


if (!function_exists('drewlabs_core_format_amount_value')) {

    function drewlabs_core_format_amount_value($balance, float $decimal = 0, $separator = ' ')
    {
        $inDecimal = round(
            pow(10, $decimal) *
                (abs($balance) - floor(abs($balance))),
            0
        );
        $inBalance = floor(abs($balance));
        if (($decimal === 0) || ($inDecimal === pow(10, $decimal))) {
            $inBalance = floor(abs($balance));
            $inDecimal = 0;
        }
        $balanceFormat = \sprintf('%d', $inBalance);
        $nb = strlen($balanceFormat);
        for ($i = 1; $i < 4; $i++) {
            if ($inBalance >= pow(10, 3 * $i)) {
                $balanceFormat = sprintf('%s%s%s', substr($balanceFormat, 0, ($nb - (3 * $i))), $separator, substr($balanceFormat, $nb - 3 * $i, $nb));
            }
        }
        if ($decimal > 0) {
            $decim = '';
            for ($j = 0; $j < $decimal - strlen((string)$inDecimal); $j++) {
                $decim .= '0';
            }
            $inDecimal = sprintf('%s%s', $decim, (string)$inDecimal);
            $balanceFormat = sprintf('%s%s%s', $balanceFormat, '.', (string)$inDecimal);
        }
        if (((float)$balance) < 0) {
            $balanceFormat = sprintf('%s%s',  '-', $balanceFormat);
        }
        return $balanceFormat;
    }
}
