<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddonController extends Controller
{
    public function index(Request $request): View
    {
        $addons = Addon::orderBy('name')->paginate(10);

        return view('addons.index', [
            'addons' => $addons,
        ]);
    }

    public function create(): View
    {
        return view('addons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:addons,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Addon::create($validated);

        return redirect()->route('addons.index')->with('success', 'Addon created successfully.');
    }

    public function edit(Addon $addon): View
    {
        return view('addons.edit', [
            'addon' => $addon,
        ]);
    }

    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:addons,code,' . $addon->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $addon->update($validated);

        return redirect()->route('addons.index')->with('success', 'Addon updated successfully.');
    }

    public function destroy(Addon $addon): RedirectResponse
    {
        $addon->delete();

        return redirect()->route('addons.index')->with('success', 'Addon deleted successfully.');
    }
}
