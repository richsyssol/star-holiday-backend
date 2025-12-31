<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
     use HasFactory;

      protected $table = "contact_info";
      protected $fillable = [
        'booking_contact_number',
        'whatsapp_number',
        'reception_contact_number',
        'address',
        'email',
        'facebook_link',
        'youtube_link',
        'instagram_link',
        'open_hours'
    ];

}
