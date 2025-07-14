<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">
                        Checkout - {{ $purchasable->title }}
                    </h2>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-2">Order Summary</h3>
                        <div class="border rounded p-4">
                            <div class="flex justify-between mb-4">
                                <span>{{ $type === 'course' ? 'Course' : 'Program' }} Fee</span>
                                <span>{{ $purchasable->formatted_price }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-4">
                                <span>Total</span>
                                <span>{{ $purchasable->formatted_price }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="purchasable_type" value="{{ $type }}">
                        <input type="hidden" name="purchasable_id" value="{{ $purchasable->id }}">

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                            Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
