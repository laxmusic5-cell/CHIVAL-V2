<?php

namespace App\Http\Controllers;

use App\Models\CoverageArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoverageAreaController extends Controller
{
    public function index(Request $request): View
    {
        $coverageAreas = CoverageArea::orderBy('name')->paginate(10);

        return view('coverage-areas.index', [
            'coverageAreas' => $coverageAreas,
        ]);
    }

    public function create(): View
    {
        return view('coverage-areas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'fee' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string'],
            'requires_admin_approval' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['requires_admin_approval'] = $request->boolean('requires_admin_approval');
        $validated['is_active'] = $request->boolean('is_active');

        CoverageArea::create($validated);

        return redirect()->route('coverage-areas.index')->with('success', 'Coverage area created successfully.');
    }

    public function edit(CoverageArea $coverageArea): View
    {
        return view('coverage-areas.edit', [
            'coverageArea' => $coverageArea,
        ]);
    }

    public function update(Request $request, CoverageArea $coverageArea): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'fee' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string'],
            'requires_admin_approval' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['requires_admin_approval'] = $request->boolean('requires_admin_approval');
        $validated['is_active'] = $request->boolean('is_active');

        $coverageArea->update($validated);

        return redirect()->route('coverage-areas.index')->with('success', 'Coverage area updated successfully.');
    }

    public function destroy(CoverageArea $coverageArea): RedirectResponse
    {
        $coverageArea->delete();

        return redirect()->route('coverage-areas.index')->with('success', 'Coverage area deleted successfully.');
    }
}
