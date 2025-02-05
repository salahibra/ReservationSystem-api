<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    // fillable fields
    protected $fillable = [
        'user_id',
        'timeSlot',
        'dateReservation',
        'pc'
    ];
    public function user(){
        return this->belongsTo(User::class);
    }
}
