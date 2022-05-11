<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','barcode', 'cost','price','stock', 'alerts','image','category_id'];
    
    public function category()
{
    return $this->belongsTo(Category::class);
}
public function getImagenAttribute($image)
{
    if(file_exists('storage/products/' . $this->image))
    return $this->image;
    else
    return 'noimg.jpg';
}

    
}
