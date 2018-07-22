<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 22/07/2018
 * Time: 08:39
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable = [
        'category_id', 'expense', 'value', 'expiration_date', 'payment_status', 'payment_date'
    ];

    public function getAllExpenses($dt_start = NULL, $dt_end = NULL)
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

        return $this->select('expenses.*', 'expenses_categories.category')
            ->join('expenses_categories', 'category_id', '=', 'expenses_categories.id')
            ->whereBetween('expenses.expiration_date', [$dt_start, $dt_end])
            ->orderBy('expenses.expiration_date','DESC')
            ->get();
    }

    public function getExpense($id)
    {
        return $this->select('expenses.*', 'expenses_categories.category')
            ->join('expenses_categories', 'category_id', '=', 'expenses_categories.id')
            ->where('expenses.id', '=', $id)
            ->first();
    }
}
