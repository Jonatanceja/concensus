@php $servicios = $page->content['servicios']; @endphp

<section id="{{ $servicios['id'] ?? 'servicios' }}" x-data class="relative overflow-hidden bg-sand-50 py-20 lg:py-28">
    <div class="pointer-events-none absolute -right-32 top-20 h-80 w-80 rounded-full bg-brand-100/60 blur-3xl"></div>

    <div class="shell relative">
        <div class="reveal mx-auto max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <span class="eyebrow eyebrow-center">{{ $servicios['eyebrow'] }}</span>
            <h2 class="mt-5 font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $servicios['title'] }}</h2>
            <p class="mt-5 text-[0.975rem] leading-relaxed text-ink-500">{{ $servicios['text'] }}</p>
        </div>

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($servicios['items'] as $index => $item)
                <article class="card reveal group flex flex-col p-8"
                         style="transition-delay: {{ ($index % 3) * 90 }}ms"
                         x-intersect.once="$el.classList.add('is-visible')">
                    <span class="icon-tile transition-transform duration-500 group-hover:-rotate-6 group-hover:scale-105">
                        @include('_partials.icon', ['name' => $item['icon'], 'class' => 'w-6 h-6'])
                    </span>

                    <h3 class="mt-7 font-display text-lg font-semibold">{{ $item['title'] }}</h3>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-500">{{ $item['text'] }}</p>

                    <span class="mt-6 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.14em] text-brand-600 opacity-0 transition-all duration-300 group-hover:opacity-100">
                        Conocer más
                        @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-3.5 h-3.5'])
                    </span>
                </article>
            @endforeach
        </div>

        @if ($servicios['cta'] ?? false)
            <div class="reveal mt-14 text-center" x-intersect.once="$el.classList.add('is-visible')">
                <a href="{{ $servicios['cta']['href'] }}" class="btn btn-primary">
                    {{ $servicios['cta']['label'] }}
                    @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                </a>
            </div>
        @endif
    </div>
</section>
