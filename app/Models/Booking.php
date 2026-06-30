<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
use App\Models\CustomerVehicle;
use App\Models\CoverageArea;
use App\Models\BookingItem;
use App\Models\BookingInspection;
use App\Models\Payment;

class Booking extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_id', 'customer_vehicle_id', 'coverage_area_id', 'code', 'address',
        'booking_date', 'booking_time_slot', 'total_service_price', 'total_addon_price', 'area_fee',
        'discount_amount', 'total_amount', 'required_dp_amount', 'amount_paid', 'payment_status', 'status', 'notes', 'completed_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'completed_at' => 'datetime',
        'total_service_price' => 'integer',
        'total_addon_price' => 'integer',
        'area_fee' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'required_dp_amount' => 'integer',
        'amount_paid' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function customerVehicle(): BelongsTo
    {
        return $this->belongsTo(CustomerVehicle::class, 'customer_vehicle_id');
    }

    public function coverageArea(): BelongsTo
    {
        return $this->belongsTo(CoverageArea::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(BookingInspection::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
