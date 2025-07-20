<div class="header-section">
    @if(isset($organizationLogo) && $organizationLogo)
    <img src="{{ $organizationLogo }}" alt="Organization Logo" class="organization-logo">
    @endif

    <div class="organization-name">{{ $organizationName ?? 'Training Academy' }}</div>
    <div class="organization-tagline">{{ $organizationTagline ?? 'Excellence in Education' }}</div>
</div>
