@php
    $gob = $page->content['gobierno'];
    $familia = $gob['familia'];
    $empresa = $gob['empresa'];
@endphp

<section id="{{ $gob['id'] ?? 'gobierno' }}" x-data
         class="relative overflow-hidden bg-ink-950 py-20 lg:py-28"
         aria-labelledby="gobierno-titulo">
    <div class="pointer-events-none absolute -left-40 top-10 h-96 w-96 rounded-full bg-brand-700/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-brand-500/10 blur-3xl"></div>

    <div class="shell relative">
        <div class="reveal mx-auto max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <span class="eyebrow eyebrow-center !text-brand-300">{{ $gob['eyebrow'] }}</span>
            <h2 id="gobierno-titulo" class="mt-5 font-display text-3xl font-semibold text-white sm:text-4xl lg:text-[2.7rem]">{{ $gob['title'] }}</h2>
            <p class="mt-5 text-[0.975rem] leading-relaxed text-sand-300/80">{{ $gob['text'] }}</p>
        </div>

        <figure class="reveal mt-14 lg:mt-16" x-intersect.once="$el.classList.add('is-visible')">
            <div class="grid items-start gap-6 lg:grid-cols-[1fr_auto_1fr]">

                {{-- Columna izquierda: los órganos de la familia --}}
                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 sm:p-8">
                    <h3 class="inline-flex rounded-full border border-brand-400/30 bg-brand-500/10 px-4 py-1.5 font-sans text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-brand-300">
                        {{ $familia['label'] }}
                    </h3>

                    <div class="mt-6">
                        @foreach ($familia['nodes'] as $index => $node)
                            @if ($index > 0)
                                <span class="mx-auto block h-5 w-px bg-white/20" aria-hidden="true"></span>
                            @endif
                            <p class="rounded-xl border border-white/10 bg-white/[0.07] px-5 py-4 text-center font-display text-[0.95rem] font-medium text-white">{{ $node }}</p>
                        @endforeach
                    </div>

                    @include('_partials.gobierno-aside', ['aside' => $familia['aside']])
                </div>

                {{-- Puente: el consejo familiar conecta con la asamblea de accionistas --}}
                <div class="flex items-center justify-center lg:h-full lg:pt-24" aria-hidden="true">
                    <span class="inline-flex h-11 w-11 rotate-90 items-center justify-center rounded-full border border-white/15 bg-white/[0.06] text-brand-300 lg:rotate-0">
                        @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                    </span>
                </div>

                {{-- Columna derecha: los órganos de la empresa --}}
                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 sm:p-8">
                    <h3 class="inline-flex rounded-full border border-brand-400/30 bg-brand-500/10 px-4 py-1.5 font-sans text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-brand-300">
                        {{ $empresa['label'] }}
                    </h3>

                    <div class="mt-6">
                        @foreach ($empresa['nodes'] as $index => $node)
                            @if ($index > 0)
                                <span class="mx-auto block h-5 w-px bg-white/20" aria-hidden="true"></span>
                            @endif

                            <div class="rounded-xl border border-white/10 bg-white/[0.07] px-5 py-4 text-center">
                                <p class="font-display text-[0.95rem] font-medium text-white">{{ $node['title'] }}</p>

                                @if ($node['children'] ?? false)
                                    <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                                        @foreach ($node['children'] as $child)
                                            <li class="rounded-lg border border-white/10 bg-white/[0.06] px-3 py-2.5 text-xs leading-snug text-sand-200">{{ $child }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @include('_partials.gobierno-aside', ['aside' => $empresa['aside']])
                </div>
            </div>

            @if ($gob['bridge'] ?? false)
                <figcaption class="mt-8 text-center text-sm leading-relaxed text-sand-300/70">{{ $gob['bridge'] }}</figcaption>
            @endif
        </figure>
    </div>
</section>
