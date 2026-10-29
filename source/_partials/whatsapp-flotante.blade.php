@php $wa = $page->content['site']['whatsapp'] ?? null; @endphp

@if ($wa)
    {{-- Botón flotante de WhatsApp: por debajo del menú (z-50) y del popup del termómetro (z-100) --}}
    <a href="{{ $wa['href'] }}" target="_blank" rel="noopener noreferrer"
       aria-label="{{ $wa['label'] }}" title="{{ $wa['label'] }}"
       class="fixed bottom-5 right-5 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lift transition-transform duration-300 hover:-translate-y-0.5 hover:scale-105 sm:bottom-6 sm:right-6 sm:h-16 sm:w-16">
        @include('_partials.icon', ['name' => 'whatsapp', 'class' => 'w-7 h-7 sm:w-8 sm:h-8'])
    </a>
@endif
