<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 04/07/2018
 * Time: 20:05
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ServicesCategories extends Model
{
    protected $table = 'services_categories';
    public $timestamps = false;
    protected $fillable = [
        'category',
        'value',
        'active'
    ];

}
