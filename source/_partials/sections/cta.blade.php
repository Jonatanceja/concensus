@php $cta = $page->content['cta']; @endphp

<section id="{{ $cta['id'] ?? 'llamado' }}" x-data class="relative">
    <div class="bg-brand-gradient relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 opacity-[0.18]"
             style="background-image: radial-gradient(circle at 20% 20%, #fff 0, transparent 45%), radial-gradient(circle at 80% 80%, #fff 0, transparent 40%);"></div>

        <div class="shell relative py-20 text-center lg:py-24">
            <div class="reveal mx-auto max-w-3xl" x-intersect.once="$el.classList.add('is-visible')">
                <h2 class="font-display text-3xl font-semibold leading-tight text-white sm:text-4xl lg:text-[2.75rem]">{{ $cta['title'] }}</h2>
                <p class="mx-auto mt-5 max-w-xl text-[0.975rem] leading-relaxed text-white/80">{{ $cta['text'] }}</p>

                <a href="{{ $cta['button']['href'] }}" class="btn btn-light mt-9">
                    {{ $cta['button']['label'] }}
                    @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                </a>

                @if ($cta['note'] ?? false)
                    <p class="mt-6 text-xs font-medium uppercase tracking-[0.14em] text-white/60">{{ $cta['note'] }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
