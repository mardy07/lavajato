<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 22/07/2018
 * Time: 08:43
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ExpensesCategories extends Model
{
    protected $table = 'expenses_categories';
    public $timestamps = false;
    protected $fillable = [
        'category',
        'active'
    ];
}