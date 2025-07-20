<div class="security-section">
    <div class="certificate-number">Certificate Number</div>
    <div class="number-value">{{ $certificateNumber ?? 'CERT-2024-001' }}</div>
    @if(isset($qrCode) && $qrCode)
    <img src="{{ $qrCode }}" alt="QR Code" class="qr-code">
    @endif
    @if(isset($barcode) && $barcode)
    <img src="{{ $barcode }}" alt="Barcode" class="barcode">
    @endif
</div>
