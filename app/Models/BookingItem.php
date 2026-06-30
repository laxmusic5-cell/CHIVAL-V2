<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Addon;

class BookingItem extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['booking_id', 'item_type', 'item_id', 'name_snapshot', 'price_snapshot'];

    protected $casts = [
        'price_snapshot' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'item_id')->where('item_type', 'service');
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class, 'item_id')->where('item_type', 'addon');
    }
}
