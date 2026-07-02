<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleTypeController extends Controller
{
    public function index(Request $request): View
    {
        $vehicleTypes = VehicleType::orderBy('name')->paginate(10);

        return view('vehicle-types.index', [
            'vehicleTypes' => $vehicleTypes,
        ]);
    }

    public function create(): View
    {
        return view('vehicle-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        VehicleType::create($validated);

        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle type created successfully.');
    }

    public function edit(VehicleType $vehicleType): View
    {
        return view('vehicle-types.edit', [
            'vehicleType' => $vehicleType,
        ]);
    }

    public function update(Request $request, VehicleType $vehicleType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $vehicleType->update($validated);

        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle type updated successfully.');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        $vehicleType->delete();

        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle type deleted successfully.');
    }
}
