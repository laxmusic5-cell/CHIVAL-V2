<?php

namespace App\Http\Controllers;

use App\Models\CoverageArea;
use App\Models\Service;
use App\Models\ServicePriceTier;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicePriceTierController extends Controller
{
    public function index(Request $request): View
    {
        $servicePriceTiers = ServicePriceTier::with(['service', 'vehicleType'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('service-price-tiers.index', [
            'servicePriceTiers' => $servicePriceTiers,
        ]);
    }

    public function create(): View
    {
        return view('service-price-tiers.create', [
            'services' => Service::orderBy('name')->get(),
            'vehicleTypes' => VehicleType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'string', 'exists:services,id'],
            'vehicle_type_id' => ['required', 'string', 'exists:vehicle_types,id'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        ServicePriceTier::create($validated);

        return redirect()->route('service-price-tiers.index')->with('success', 'Service price tier created successfully.');
    }

    public function edit(ServicePriceTier $servicePriceTier): View
    {
        return view('service-price-tiers.edit', [
            'servicePriceTier' => $servicePriceTier,
            'services' => Service::orderBy('name')->get(),
            'vehicleTypes' => VehicleType::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ServicePriceTier $servicePriceTier): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'string', 'exists:services,id'],
            'vehicle_type_id' => ['required', 'string', 'exists:vehicle_types,id'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        $servicePriceTier->update($validated);

        return redirect()->route('service-price-tiers.index')->with('success', 'Service price tier updated successfully.');
    }

    public function destroy(ServicePriceTier $servicePriceTier): RedirectResponse
    {
        $servicePriceTier->delete();

        return redirect()->route('service-price-tiers.index')->with('success', 'Service price tier deleted successfully.');
    }
}
