@php $bio = $page->content['biografia']; @endphp

<section id="{{ $bio['id'] ?? 'biografia' }}" x-data class="relative overflow-hidden bg-sand-50 py-20 lg:py-28" aria-labelledby="bio-titulo">
    <div class="pointer-events-none absolute -left-40 top-1/3 h-96 w-96 rounded-full bg-brand-100/50 blur-3xl"></div>

    <div class="shell relative">
        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <div class="relative">
                    <div class="absolute -bottom-5 -left-5 h-full w-full rounded-4xl border border-brand-200/70"></div>
                    <img src="{{ $bio['image'] }}" alt="{{ $bio['image_alt'] ?? $bio['name'] }}"
                         loading="lazy"
                         class="relative aspect-[4/5] w-full rounded-4xl object-cover shadow-lift">
                </div>
            </div>

            <div class="reveal lg:col-span-7" style="transition-delay: 120ms" x-intersect.once="$el.classList.add('is-visible')">
                <span class="eyebrow">{{ $bio['eyebrow'] }}</span>
                <h2 id="bio-titulo" class="mt-5 font-display text-3xl font-semibold sm:text-4xl">{{ $bio['name'] }}</h2>
                <p class="mt-3 text-[0.7rem] font-semibold uppercase tracking-[0.18em] text-brand-600">{{ $bio['role'] }}</p>

                <div class="prose-brand mt-6 max-w-xl text-[0.975rem]">
                    {!! $bio['body'] !!}
                </div>

                @if ($bio['highlights'] ?? false)
                    <dl class="mt-9 grid max-w-lg grid-cols-3 gap-4">
                        @foreach ($bio['highlights'] as $item)
                            <div class="rounded-2xl border border-sand-200 bg-white px-4 py-5 text-center shadow-soft">
                                <dt class="font-display text-2xl font-semibold text-brand-600">{{ $item['value'] }}</dt>
                                <dd class="mt-1 text-[0.7rem] font-medium uppercase tracking-wider text-ink-500">{{ $item['label'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif

                @if ($bio['cta'] ?? false)
                    <a href="{{ $bio['cta']['href'] }}" class="btn btn-primary mt-9">
                        {{ $bio['cta']['label'] }}
                        @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
