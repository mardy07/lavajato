<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 05/07/2018
 * Time: 13:01
 */

namespace App\Helpers;


class DateFormat
{

    private $date;
    private $format;

    public function __construct($date, $format)
    {
        $this->date = date($format, strtotime($date));
    }

    /**
     * Converte o formato de data BR para formato MYSQL
     * @param $data
     * @return false|string
     */
    private function format($format)
    {
        $dt = explode('/',$this->date);
        return date('Y-m-d H:m:s', strtotime($dt[2].'-'.$dt[1].'-'.$dt[0].' 00:00:00'));
    }

}
