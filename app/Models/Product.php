<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'image', 'size', 'colors', 'price', 'category_id' , 'isValid' , 'discount' , 'stars'];

    // Define the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
