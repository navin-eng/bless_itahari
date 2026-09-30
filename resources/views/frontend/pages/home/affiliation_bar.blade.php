@php
    $estVal = $siteSettings->established_year ?? $siteSettings->about_established_year ?? '2050 B.S. (1993 A.D.)';
    $estText = \Illuminate\Support\Str::startsWith(trim($estVal), ['Established', 'established', 'Est.', 'Est', 'est.', 'est']) 
        ? $estVal 
        : 'Established ' . $estVal;
@endphp
<div class="affiliation-bar">
    <div class="container">
        <div class="aff-inner">
            <span class="aff-label">Curriculum & Affiliation</span>
            <div class="aff-divider"></div>
            <span class="aff-name">{{ $siteSettings->about_affiliation ?? 'National Examination Board (NEB) Nepal' }}</span>
            <div class="aff-divider"></div>
            <span class="aff-badge">
                <i class="fa-solid fa-school"></i>
                {{ $estText }}
            </span>
        </div>
    </div>
</div>
