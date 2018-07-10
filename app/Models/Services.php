<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 04/07/2018
 * Time: 20:04
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Services extends Model
{

    /**
     * Retorna todos os registros da tabela services
     * junto com os campos de tabelas de relacionamento
     *
     * @param $dt_start
     * @param $dt_end
     * @return mixed
     */
    public function getAllServices($dt_start = NULL, $dt_end = NULL)
    {
        if(!$dt_start || !$dt_end){
            $beginning_month = date('Y-m-01');
            $last_day = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

            $dt_start = date('Y-m-d H:m:s', strtotime($beginning_month . ' 00:00:00'));
            $dt_end = date('Y-m-' . $last_day . ' 23:59:59');
        } else {
            $dt_start = $dt_start . ' 00:00:00';
            $dt_end = $dt_end . ' 23:59:59';
        }

        return $this->select('services.*', 'customers.customer', 'services_categories.category')
            ->leftJoin('customers', 'services.customer_id', '=', 'customers.id')
            ->join('services_categories', 'category_id', '=', 'services_categories.id')
            ->whereBetween('services.date', [$dt_start, $dt_end])
            ->orderBy('services.date','DESC')
            ->get();
    }

    public function getService($id)
    {
        return $this->select('services.*', 'customers.customer', 'services_categories.category')
            ->leftJoin('customers', 'services.customer_id', '=', 'customers.id')
            ->join('services_categories', 'category_id', '=', 'services_categories.id')
            ->where('services.id', '=', $id)
            ->first();
    }
}
