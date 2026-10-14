@php $frases = $page->content['frases']; @endphp

<section id="{{ $frases['id'] ?? 'familia' }}" x-data class="relative overflow-hidden bg-sand-50 py-20 lg:py-28" aria-labelledby="frases-titulo">
    <div class="shell">
        <div class="mx-auto max-w-3xl text-center">
            <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
                <h2 id="frases-titulo" class="font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $frases['title'] }}</h2>
                <p class="mt-5 font-display text-xl text-brand-600">{{ $frases['subtitle'] }}</p>
                <p class="mt-5 text-[0.975rem] leading-relaxed text-ink-500">{{ $frases['text'] }}</p>

                @if ($frases['cta'] ?? false)
                    <a href="{{ $frases['cta']['href'] }}" class="btn btn-primary mt-8">{{ $frases['cta']['label'] }}</a>
                @endif
            </div>
        </div>

        <div class="reveal mx-auto mt-20 max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <h3 class="font-display text-2xl font-semibold sm:text-3xl">{{ $frases['phrases_title'] }}</h3>
        </div>

        <ul class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($frases['phrases'] as $index => $frase)
                <li class="card reveal flex flex-col p-7"
                    style="transition-delay: {{ ($index % 3) * 90 }}ms"
                    x-intersect.once="$el.classList.add('is-visible')">
                    <span class="text-brand-500">@include('_partials.icon', ['name' => 'quote', 'class' => 'w-6 h-6'])</span>
                    <q class="mt-4 flex-1 font-display text-lg font-medium italic leading-snug text-ink-900">{{ $frase['quote'] }}</q>
                    <small class="mt-4 text-xs leading-relaxed text-ink-500">{{ $frase['context'] }}</small>
                </li>
            @endforeach
        </ul>

        <p class="reveal mx-auto mt-12 max-w-3xl text-center text-[0.975rem] leading-relaxed text-ink-600"
           x-intersect.once="$el.classList.add('is-visible')">{{ $frases['closing'] }}</p>
    </div>
</section>
