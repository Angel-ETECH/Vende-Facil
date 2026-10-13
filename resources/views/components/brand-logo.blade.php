@props(['size' => 'default'])
<span {{ $attributes->class(['vf-brand-logo', 'vf-brand-logo--panel' => $size === 'panel']) }}>
    <img class="vf-brand-logo__image" src="{{ asset('images/brand/vendefacil-logo.png') }}" alt="VendeFácil - Negocios con onda" width="2172" height="724" decoding="async">
</span>
