<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingItemController extends Controller
{
    public function index(Request $request): View
    {
        $items = BookingItem::with(['booking', 'service', 'addon'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('booking-items.index', [
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('booking-items.create', [
            'bookings' => Booking::orderBy('created_at', 'desc')->get(),
            'services' => Service::orderBy('name')->get(),
            'addons' => Addon::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'string', 'exists:bookings,id'],
            'service_id' => ['required', 'string', 'exists:services,id'],
            'addon_id' => ['nullable', 'string', 'exists:addons,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'service_price_snapshot' => ['required', 'integer', 'min:0'],
            'addon_price_snapshot' => ['nullable', 'integer', 'min:0'],
            'subtotal' => ['required', 'integer', 'min:0'],
        ]);

        $validated['addon_price_snapshot'] = $validated['addon_price_snapshot'] ?? 0;

        BookingItem::create($validated);

        return redirect()->route('booking-items.index')->with('success', 'Booking item created successfully.');
    }

    public function edit(BookingItem $bookingItem): View
    {
        return view('booking-items.edit', [
            'item' => $bookingItem,
            'bookings' => Booking::orderBy('created_at', 'desc')->get(),
            'services' => Service::orderBy('name')->get(),
            'addons' => Addon::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, BookingItem $bookingItem): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'string', 'exists:bookings,id'],
            'service_id' => ['required', 'string', 'exists:services,id'],
            'addon_id' => ['nullable', 'string', 'exists:addons,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'service_price_snapshot' => ['required', 'integer', 'min:0'],
            'addon_price_snapshot' => ['nullable', 'integer', 'min:0'],
            'subtotal' => ['required', 'integer', 'min:0'],
        ]);

        $validated['addon_price_snapshot'] = $validated['addon_price_snapshot'] ?? 0;

        $bookingItem->update($validated);

        return redirect()->route('booking-items.index')->with('success', 'Booking item updated successfully.');
    }

    public function destroy(BookingItem $bookingItem): RedirectResponse
    {
        $bookingItem->delete();

        return redirect()->route('booking-items.index')->with('success', 'Booking item deleted successfully.');
    }
}
