<?php
/**
 * Created by PhpStorm.
 * User: genho
 * Date: 07.04.2016
 * Time: 20:30
 */

namespace genhoi\RedisFilter;

use genhoi\BaseInterface\NormalizerInterface;

class RedisKeyNameNormalizer implements NormalizerInterface
{
    public function normalize(array $strings): string
    {
        $arr = [];
        foreach ($strings as $string) {
            if (is_bool($string)) {
                $string = ($string) ? 'true' : 'false';
            }
            $arr []= $string;
        }
        return implode(RedisFilterSetting::DELIMITER, $arr);
    }

}