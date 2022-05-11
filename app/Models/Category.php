<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable =['name','image'];

    public function products()
{
    
    return $this->hasMany(Product::class);

}
public function getImagenAttribute($image)
{
    if(file_exists('storage/categories/' . $this->image))
    return $this->image;
    else
    return 'noimg.jpg';
}

}
