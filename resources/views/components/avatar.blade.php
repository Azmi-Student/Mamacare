@props(['user', 'size' => 'md', 'class' => ''])

@php
    $sizes = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10 sm:w-12 sm:h-12',
        'lg' => 'w-16 h-16',
        'xl' => 'w-24 h-24 sm:w-28 sm:h-28',
    ];
    $iconSizes = [
        'xs' => 'w-4 h-4',
        'sm' => 'w-5 h-5',
        'md' => 'w-6 h-6 sm:w-7 sm:h-7',
        'lg' => 'w-9 h-9',
        'xl' => 'w-14 h-14 sm:w-16 sm:h-16',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $iconSize = $iconSizes[$size] ?? $iconSizes['md'];

    $imagePath = $user->avatar ?? $user->image ?? null;
    $finalUrl = null;
    if ($imagePath) {
        if (str_starts_with($imagePath, 'http') || str_starts_with($imagePath, '/storage/')) {
            $finalUrl = asset($imagePath);
        } else {
            $finalUrl = asset('storage/' . $imagePath);
        }
    }
@endphp

@if($finalUrl)
    <img src="{{ $finalUrl }}" 
         alt="{{ $user->name }}" 
         class="rounded-full object-cover border-2 border-[#FF3EA5] shrink-0 {{ $sizeClass }} {{ $class }}">
@else
    <div class="bg-pink-100 rounded-full flex items-center justify-center border-2 border-[#FF3EA5] shrink-0 {{ $sizeClass }} {{ $class }}">
        <svg class="{{ $iconSize }} text-[#FF3EA5]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
    </div>
@endif
