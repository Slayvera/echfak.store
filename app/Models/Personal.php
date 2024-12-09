<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'email', 'title_head', 'logo', 'description_website', 'instagram', 'facebook' , 'tiktok'];
}
