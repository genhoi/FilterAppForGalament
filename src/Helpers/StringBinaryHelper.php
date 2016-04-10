<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 07.04.2016
 * Time: 21:24
 */

namespace genhoi\Helpers;


class StringBinaryHelper
{

    public function convertStringToBinaryString(string $input):string
    {
        if (!is_string($input))
            return false;
        $ret = '';
        for ($i = 0; $i < strlen($input); $i++)
        {
            $temp = decbin(ord($input{$i}));
            $ret .= str_repeat("0", 8 - strlen($temp)) . $temp;
        }
        return $ret;
    }

    public function getGeneratorBitFromBinaryString(string $input)
    {
        $countBits = 7;
        $length = strlen($input);
        for( $i = 0; $i < $length; ++$i ) {
            $num = ord( $input[$i] );
            for( $j = $countBits; $j >= 0; --$j ) {
                yield ( $num & ( 1 << $j ) ) ? 1 : 0;
            }
        }
    }

    public function getPositionsBitInBinaryString(string $string):array
    {
        $count = 0;
        $positions = [];
        foreach ($this->getGeneratorBitFromBinaryString($string) as $bit) {
            $count++;
            if ($bit === 1) {
                $positions []= $count;
            }
        }
        return $positions;
    }

    public function getGeneratorPositionsBitInBinaryString(string $string)
    {
        $count = 0;
        foreach ($this->getGeneratorBitFromBinaryString($string) as $bit) {
            $count++;
            if ($bit === 1) {
                yield $count;
            }
        }
    }

}