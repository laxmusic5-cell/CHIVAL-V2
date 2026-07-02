<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Booking Item</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('booking-items.update', $item) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-6">
                            <div>
                                <label for="booking_id" class="block text-sm font-medium text-gray-700">Booking</label>
                                <select id="booking_id" name="booking_id" class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select a booking</option>
                                    @foreach ($bookings as $b)
                                        <option value="{{ $b->id }}" {{ old('booking_id', $item->booking_id) === $b->id ? 'selected' : '' }}>{{ $b->code }} — {{ optional($b->booking_date)->format('Y-m-d') }}</option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
                                <select id="service_id" name="service_id" class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select a service</option>
                                    @foreach ($services as $s)
                                        <option value="{{ $s->id }}" {{ old('service_id', $item->service_id) === $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="addon_id" class="block text-sm font-medium text-gray-700">Addon (optional)</label>
                                <select id="addon_id" name="addon_id" class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">None</option>
                                    @foreach ($addons as $a)
                                        <option value="{{ $a->id }}" {{ old('addon_id', $item->addon_id) === $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                    @endforeach
                                </select>
                                @error('addon_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" type="number" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('quantity')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="service_price_snapshot" class="block text-sm font-medium text-gray-700">Service Price (snapshot)</label>
                                <input id="service_price_snapshot" name="service_price_snapshot" value="{{ old('service_price_snapshot', $item->service_price_snapshot) }}" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('service_price_snapshot')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="addon_price_snapshot" class="block text-sm font-medium text-gray-700">Addon Price (snapshot)</label>
                                <input id="addon_price_snapshot" name="addon_price_snapshot" value="{{ old('addon_price_snapshot', $item->addon_price_snapshot) }}" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('addon_price_snapshot')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subtotal" class="block text-sm font-medium text-gray-700">Subtotal</label>
                                <input id="subtotal" name="subtotal" value="{{ old('subtotal', $item->subtotal) }}" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('subtotal')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between pt-6">
                                <a href="{{ route('booking-items.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
