<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Booking extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_id','customer_vehicle_id','vehicle_type_id','coverage_area_id','code','address',
        'booking_date','booking_time_slot','total_service_price','total_addon_price','area_fee',
        'discount_amount','total_amount','required_dp_amount','amount_paid','payment_status','status','notes','completed_at'
    ];
}
