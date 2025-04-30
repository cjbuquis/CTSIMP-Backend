<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'places';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'place_name',
        'address',
        'email_address',
        'contact_no',
        'description',
        'virtual_iframe',
        'map_iframe',
        'image_link',
        'status',
        'entrance',
        'room_or_cottages_price',
        'history',
        'activities',
        'reason_for_rejection',
        'services',

    ];

    // The attributes that should be hidden for arrays
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
