<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSubmission extends Model
{
    protected $table = 'booking_submissions';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'booking_date',
        'phone_number',
        'room_type',
        'day',
        'message',
        'is_viewed'
    ];
    
    protected $casts = [
        'booking_date' => 'date',
        'is_viewed' => 'boolean'
    ];
    
    // Room type options
    const ROOM_TYPES = [
        '2_bedded_super_deluxe_ac_couple_rooms' => '2 Bedded Super Deluxe AC Couple Rooms',
        '4_bedded_super_deluxe_ac_family_rooms' => '4 Bedded Super Deluxe AC Family Rooms',
        '6_bedded_super_deluxe_ac_family_suite' => '6 Bedded Super Deluxe AC Family Suite'
    ];
    
    // Day options
    const DAYS = [
        'sunday' => 'Sunday',
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday'
    ];
    
    // Get full name attribute
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    // Get formatted room type
    public function getFormattedRoomTypeAttribute()
    {
        return self::ROOM_TYPES[$this->room_type] ?? $this->room_type;
    }
    
    // Get formatted day
    public function getFormattedDayAttribute()
    {
        return self::DAYS[$this->day] ?? $this->day;
    }
    
    // Scope for unviewed submissions
    public function scopeUnviewed($query)
    {
        return $query->where('is_viewed', false);
    }
    
    // Scope for viewed submissions
    public function scopeViewed($query)
    {
        return $query->where('is_viewed', true);
    }
    
    // Scope ordered by latest
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}