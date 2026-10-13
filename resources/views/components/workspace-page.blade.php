@props(['title'])
<div class="min-h-screen bg-[#fffaf5] px-4 py-8 text-[#35272a] sm:px-6">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-black">{{ $title }}</h1>
            <a href="{{ route('home') }}" class="text-sm font-bold text-[#427764]">Volver al marketplace</a>
        </div>
        @if(session('success'))<p role="status" class="mb-5 rounded-lg bg-[#e7f5ef] p-4 text-[#315c4d]">{{ session('success') }}</p>@endif
        @if($errors->any())<div role="alert" class="mb-5 rounded-lg bg-[#fff0ec] p-4 text-[#b45040]">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
        {{ $slot }}
    </div>
</div>
