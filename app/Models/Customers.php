<?php
/**
 * Created by PhpStorm.
 * User: Mard
 * Date: 05/07/2018
 * Time: 12:29
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'customer', 'date_birth', 'sex', 'cellphone', 'phone', 'address', 'number', 'complement', 'neighborhood',
        'city', 'state', 'zipcode', 'email'
    ];

}
