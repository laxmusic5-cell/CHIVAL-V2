<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\BookingItem;

class Addon extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['code', 'name', 'description', 'price', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
    ];

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class, 'item_id')->where('item_type', 'addon');
    }
}
