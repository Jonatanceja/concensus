@php $intro = $page->content['introduccion']; @endphp

<section id="{{ $intro['id'] ?? 'introduccion' }}" x-data class="relative bg-sand-100 py-20 lg:py-28">
    <div class="shell">
        <div class="reveal mx-auto max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <span class="eyebrow eyebrow-center">{{ $intro['eyebrow'] }}</span>
            <h2 class="mt-5 font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $intro['title'] }}</h2>
            <p class="mt-5 text-[0.975rem] leading-relaxed text-ink-500">{{ $intro['text'] }}</p>
        </div>

        <div class="mt-14 grid gap-6 md:grid-cols-3">
            @foreach ($intro['cards'] as $index => $card)
                <article class="card reveal group overflow-hidden p-8"
                         style="transition-delay: {{ $index * 90 }}ms"
                         x-intersect.once="$el.classList.add('is-visible')">
                    <span class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brand-300 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>

                    <span class="icon-tile">
                        @include('_partials.icon', ['name' => $card['icon'], 'class' => 'w-6 h-6'])
                    </span>

                    <h3 class="mt-7 font-display text-lg font-semibold">{{ $card['title'] }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-ink-500">{{ $card['text'] }}</p>
                </article>
            @endforeach
        </div>

        @if ($intro['cta'] ?? false)
            <div class="reveal mt-12 text-center" x-intersect.once="$el.classList.add('is-visible')">
                <a href="{{ $intro['cta']['href'] }}"
                   class="group inline-flex items-center gap-2 text-sm font-semibold text-brand-600 transition-colors hover:text-brand-700">
                    {{ $intro['cta']['label'] }}
                    <span class="transition-transform duration-300 group-hover:translate-x-1">
                        @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                    </span>
                </a>
            </div>
        @endif
    </div>
</section>
