@php $historias = $page->content['historias']; @endphp

<section id="{{ $historias['id'] ?? 'historias' }}" x-data class="relative overflow-hidden bg-sand-50 py-20 lg:py-28" aria-labelledby="historias-titulo">
    <div class="shell">
        <div class="reveal mx-auto max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <span class="eyebrow eyebrow-center">{{ $historias['eyebrow'] }}</span>
            <h2 id="historias-titulo" class="mt-5 font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $historias['title'] }}</h2>
            <p class="mt-5 text-[0.975rem] leading-relaxed text-ink-500">{{ $historias['text'] }}</p>
        </div>

        <div class="mt-14 space-y-8">
            @foreach ($historias['stories'] as $story)
                <article class="card reveal flex flex-col overflow-hidden md:flex-row" x-intersect.once="$el.classList.add('is-visible')">
                    <div class="shrink-0 md:w-1/3">
                        <img src="{{ $story['image'] }}" alt="{{ $story['image_alt'] ?? '' }}" loading="lazy"
                             class="aspect-square h-full w-full object-cover">
                    </div>

                    <div class="p-8 sm:p-10 md:w-2/3">
                        <header>
                            <h3 class="font-display text-2xl font-semibold text-ink-900">{{ $story['title'] }}</h3>
                            <p class="mt-2 text-xs font-semibold uppercase tracking-wider text-brand-600">{{ $story['meta'] }}</p>
                        </header>

                        <div class="mt-6 space-y-4">
                            @foreach ($story['paragraphs'] as $parrafo)
                                <p class="text-justify text-[0.975rem] leading-relaxed text-ink-500 hyphens-auto">{{ $parrafo }}</p>
                            @endforeach
                        </div>

                        <p class="mt-6 border-l-2 border-brand-500 pl-5 font-display text-lg font-medium italic leading-snug text-ink-900">{{ $story['turn'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        @php $cierre = preg_split('/(?<=\.)\s+/u', trim($historias['closing']), 2); @endphp
        <div class="reveal relative mt-8 overflow-hidden rounded-3xl border border-brand-200 bg-white px-8 py-12 text-center shadow-lift sm:px-14"
             x-intersect.once="$el.classList.add('is-visible')">
            <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-brand-500 to-transparent"></span>
            <p class="font-display text-2xl font-semibold leading-snug text-ink-900 sm:text-3xl">
                {{ $cierre[0] }}
                @if (isset($cierre[1]))
                    <span class="mt-3 block text-brand-600">{{ $cierre[1] }}</span>
                @endif
            </p>
        </div>
    </div>
</section>
