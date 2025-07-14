@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                <h2 class="text-2xl font-bold mb-2">Processing Your Payment</h2>
                <p class="text-gray-600 mb-6">Please complete the payment on your M-Pesa phone</p>

                <div class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold mb-4">Payment Instructions:</h3>
                    <ol class="text-left space-y-2 text-gray-700">
                        <li>1. Wait for the M-Pesa prompt on your phone</li>
                        <li>2. Enter your M-Pesa PIN to authorize payment</li>
                        <li>3. Do not close or refresh this page</li>
                        <li>4. You will be redirected automatically once payment is confirmed</li>
                    </ol>
                </div>

                <div class="text-sm text-gray-500">
                    Payment Reference: {{ $payment->id }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Poll for payment status every 5 seconds
    const checkPaymentStatus = () => {
        fetch(`/api/payments/{{ $payment->id }}/status`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'completed') {
                    window.location.href = "{{ route('programs.payment.success', ['program' => $program->id, 'payment' => $payment->id]) }}";
                }
            });
    };

    // Start polling
    const interval = setInterval(checkPaymentStatus, 5000);

    // Clean up on page leave
    window.addEventListener('beforeunload', () => {
        clearInterval(interval);
    });
</script>
@endpush
@endsection
