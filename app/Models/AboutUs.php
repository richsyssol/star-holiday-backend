<?php
// app/Models/AboutUs.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'welcome_title',
        'main_title',
        'description_1',
        'description_2',
        'button_text',
        'image_1',
        'image_2',
        'image_3'
    ];
}