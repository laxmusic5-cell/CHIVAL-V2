<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\CustomerVehicle;
use App\Models\CoverageArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with(['customer', 'customerVehicle', 'coverageArea'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function create(): View
    {
        $customers = User::role('customer')->orderBy('name')->get();
        $customerVehicles = CustomerVehicle::orderBy('brand')->get();
        $coverageAreas = CoverageArea::orderBy('name')->get();

        return view('bookings.create', [
            'customers' => $customers,
            'customerVehicles' => $customerVehicles,
            'coverageAreas' => $coverageAreas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'string', 'exists:users,id'],
            'customer_vehicle_id' => ['required', 'string', 'exists:customer_vehicles,id'],
            'coverage_area_id' => ['required', 'string', 'exists:coverage_areas,id'],
            'booking_date' => ['required', 'date'],
            'booking_time_slot' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string'],
            'payment_status' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
        ]);

        $defaults = [
            'code' => 'BK-' . strtoupper(Str::random(6)),
            'total_service_price' => 0,
            'total_addon_price' => 0,
            'area_fee' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'required_dp_amount' => 0,
            'amount_paid' => 0,
        ];

        $data = array_merge($validated, $defaults);

        Booking::create($data);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function edit(Booking $booking): View
    {
        $customers = User::role('customer')->orderBy('name')->get();
        $customerVehicles = CustomerVehicle::orderBy('brand')->get();
        $coverageAreas = CoverageArea::orderBy('name')->get();

        return view('bookings.edit', [
            'booking' => $booking,
            'customers' => $customers,
            'customerVehicles' => $customerVehicles,
            'coverageAreas' => $coverageAreas,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'string', 'exists:users,id'],
            'customer_vehicle_id' => ['required', 'string', 'exists:customer_vehicles,id'],
            'coverage_area_id' => ['required', 'string', 'exists:coverage_areas,id'],
            'booking_date' => ['required', 'date'],
            'booking_time_slot' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string'],
            'payment_status' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
        ]);

        // keep monetary fields as-is; set to 0 if missing
        $monetaryDefaults = [
            'total_service_price' => $booking->total_service_price ?? 0,
            'total_addon_price' => $booking->total_addon_price ?? 0,
            'area_fee' => $booking->area_fee ?? 0,
            'discount_amount' => $booking->discount_amount ?? 0,
            'total_amount' => $booking->total_amount ?? 0,
            'required_dp_amount' => $booking->required_dp_amount ?? 0,
            'amount_paid' => $booking->amount_paid ?? 0,
        ];

        $data = array_merge($validated, $monetaryDefaults);

        $booking->update($data);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
