<div class="signature-section">
    @if(isset($signature) && $signature)
    <img src="{{ $signature }}" alt="Signature" class="signature-image">
    @endif
    <div class="signature-name">{{ $signatureName ?? 'Authorized Signature' }}</div>
    <div class="signature-title">{{ $signatureTitle ?? 'Course Director' }}</div>
</div>
