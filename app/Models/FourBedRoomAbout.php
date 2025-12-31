<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FourBedRoomAbout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'tagline',
        'descriptions',
        'specs',
        'amenities',
        'images',
        'styling',
        'booking_button',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */ 
protected $casts = [
    'descriptions' => 'array',
    'specs' => 'array',
    'amenities' => 'array',
    'images' => 'array',
    'styling' => 'array',
    'booking_button' => 'array',
];

    /**
     * Get the first record or create if not exists.
     *
     * @return \App\Models\FourBedRoomAbout
     */
    public static function getFirstOrCreate()
    {
        return static::firstOrCreate([], [
            'title' => '6 Bedded super deluxe AC family Suite',
            'tagline' => 'Perfect for group vacations, this suite brings everyone together while ensuring ample space and convenience for all.',
            'descriptions' => json_encode([
                'Experience luxury and comfort in our spacious 6-bedroom family suite. Designed with large groups in mind, this suite offers privacy when needed and togetherness when desired.',
                'Each bedroom is equipped with premium bedding, individual climate control, and elegant furnishings. The common area features comfortable seating, entertainment options, and a mini-bar for your convenience.',
                'Ideal for family reunions, group trips, or extended stays, our super deluxe AC family suite ensures everyone has a memorable and comfortable experience.'
            ]),
            'specs' => json_encode([
                'Bedrooms' => '6',
                'Bathrooms' => '4',
                'Area' => '1200 sq ft',
                'Max Occupancy' => '12 adults',
                'View' => 'City/Mountain'
            ]),
            'amenities' => json_encode([
                ['amenity' => 'Air Conditioning'],
                ['amenity' => 'Free WiFi'],
                ['amenity' => 'Flat-screen TV'],
                ['amenity' => 'Mini Bar'],
                ['amenity' => 'Safe Deposit Box'],
                ['amenity' => '24/7 Room Service'],
                ['amenity' => 'Daily Housekeeping'],
                ['amenity' => 'Complimentary Toiletries']
            ]),
            'styling' => json_encode([
                'background' => 'linear-gradient(to bottom right, #f9fafb, #ffffff, #f3f4f6)',
                'maxWidth' => '1600px'
            ]),
            'booking_button' => json_encode([
                'text' => 'Book Now',
                'url' => '/bookform'
            ])

        ]);
    }
}