<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    //llenar columnas//
    protected $fillable =['name','addres', 'phone','taxpayer_id'];
}
