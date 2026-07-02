<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Coverage Area</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('coverage-areas.update', $coverageArea) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input id="name" name="name" value="{{ old('name', $coverageArea->name) }}" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fee" class="block text-sm font-medium text-gray-700">Fee</label>
                                <input id="fee" name="fee" value="{{ old('fee', $coverageArea->fee) }}" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('fee')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                                <textarea id="note" name="note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('note', $coverageArea->note) }}</textarea>
                                @error('note')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="requires_admin_approval" name="requires_admin_approval" value="1" {{ old('requires_admin_approval', $coverageArea->requires_admin_approval) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="requires_admin_approval" class="text-sm font-medium text-gray-700">Requires Admin Approval</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $coverageArea->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                            </div>

                            <div class="flex items-center justify-between pt-6">
                                <a href="{{ route('coverage-areas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
