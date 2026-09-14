@php $hero = $page->content['hero']; @endphp

<section id="inicio" class="relative"
    x-data="heroSlider({ count: {{ count($hero['slides']) }}, autoplay: {{ $hero['autoplay'] ? 'true' : 'false' }}, interval: {{ $hero['interval'] ?? 7000 }} })"
    x-on:mouseenter="paused = true" x-on:mouseleave="paused = false"
    x-on:focusin="paused = true" x-on:focusout="paused = false"
    x-on:touchstart.passive="onTouchStart($event)" x-on:touchend.passive="onTouchEnd($event)"
    x-on:keydown.window.arrow-right="next()" x-on:keydown.window.arrow-left="prev()"
    aria-roledescription="carrusel" aria-label="Presentación principal">

    <div class="relative h-[100svh] max-h-[100vh] overflow-hidden bg-ink-900">
        {{-- Imágenes de fondo --}}
        @foreach ($hero['slides'] as $index => $slide)
            <div class="absolute inset-0 transition-opacity duration-[1200ms] ease-[cubic-bezier(0.22,1,0.36,1)]"
                 x-bind:class="active === {{ $index }} ? 'opacity-100' : 'opacity-0'"
                 x-bind:aria-hidden="active !== {{ $index }}">
                <img src="{{ $slide['image'] }}" alt="{{ $slide['image_alt'] ?? '' }}"
                     class="h-full w-full object-cover transition-transform duration-[9000ms] ease-out"
                     x-bind:class="active === {{ $index }} ? 'scale-105' : 'scale-100'"
                     @if ($index === 0) fetchpriority="high" @else loading="lazy" @endif>
            </div>
        @endforeach

        {{-- Veladura para legibilidad --}}
        <div class="absolute inset-0 bg-gradient-to-r from-ink-950/85 via-ink-950/45 to-ink-950/5"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/60 via-transparent to-ink-950/55"></div>

        {{-- Contenido --}}
        <div class="relative flex h-full items-center">
            <div class="shell hero-inner w-full pt-28 pb-28 lg:pt-24 lg:pb-24">
                <div class="max-w-xl">
                    {{--
                        El panel de cristal es único y permanece fijo: solo se
                        cruza el contenido de cada slide. Si se animara la opacidad
                        del propio panel, el backdrop-blur no se compondría hasta
                        terminar la transición y aparecería con retraso.
                    --}}
                    <div class="hero-panel rounded-4xl border border-white/15 bg-white/10 p-8 backdrop-blur-xl sm:p-10 lg:bg-white/[0.07]">
                        <div class="hero-stack">
                            @foreach ($hero['slides'] as $index => $slide)
                                <div class="hero-slide" data-active="{{ $index === 0 ? 'true' : 'false' }}"
                                     x-bind:data-active="active === {{ $index }}"
                                     x-bind:aria-hidden="active !== {{ $index }}">
                                    <span class="eyebrow !text-brand-300">{{ $slide['eyebrow'] }}</span>

                                    {{-- Una sola h1 por página: los demás slides usan h2 con el mismo estilo --}}
                                    <{{ $index === 0 ? 'h1' : 'h2' }} class="hero-title mt-5 font-display text-4xl font-semibold leading-[1.08] text-white sm:text-5xl lg:text-[3.4rem]">
                                        {{ $slide['title'] }}
                                        <span class="text-gradient">{{ $slide['highlight'] }}</span>
                                        {{ $slide['title_end'] }}
                                    </{{ $index === 0 ? 'h1' : 'h2' }}>

                                    <p class="hero-copy mt-6 max-w-lg text-[0.975rem] leading-relaxed text-white/75">{{ $slide['text'] }}</p>

                                    <div class="mt-9 flex flex-wrap gap-3">
                                        <a href="{{ $slide['primary']['href'] }}" class="btn btn-primary">
                                            {{ $slide['primary']['label'] }}
                                            @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                                        </a>
                                        <a href="{{ $slide['secondary']['href'] }}" class="btn btn-ghost !bg-white/10 !text-white !border-white/25 hover:!bg-white/20">
                                            {{ $slide['secondary']['label'] }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Controles --}}
        @if (count($hero['slides']) > 1)
            <div class="absolute inset-x-0 bottom-0">
                <div class="shell flex items-center justify-between gap-6 pb-8">
                    <div class="flex items-center gap-3">
                        @foreach ($hero['slides'] as $index => $slide)
                            <button type="button" x-on:click="go({{ $index }})"
                                    x-bind:aria-current="active === {{ $index }} ? 'true' : 'false'"
                                    aria-label="Ir al slide {{ $index + 1 }} de {{ count($hero['slides']) }}: {{ $slide['eyebrow'] }}"
                                    class="h-1 rounded-full transition-all duration-500"
                                    x-bind:class="active === {{ $index }} ? 'w-12 bg-brand-400' : 'w-6 bg-white/35 hover:bg-white/60'"></button>
                        @endforeach
                        <span class="ml-2 font-display text-xs tabular-nums text-white/60">
                            <span class="text-white" x-text="String(active + 1).padStart(2, '0')"></span>/{{ str_pad(count($hero['slides']), 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <div class="hidden gap-2 sm:flex">
                        {{-- Pausar la reproducción automática: requisito de WCAG 2.2.2 --}}
                        <button type="button" x-on:click="toggleAutoplay()"
                                x-bind:aria-label="stopped ? 'Reanudar la presentación automática' : 'Pausar la presentación automática'"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/25 text-white/80 transition-all hover:border-brand-400 hover:bg-white/10 hover:text-white">
                            <span x-show="!stopped">@include('_partials.icon', ['name' => 'pause', 'class' => 'w-4 h-4'])</span>
                            <span x-show="stopped" x-cloak>@include('_partials.icon', ['name' => 'play', 'class' => 'w-4 h-4'])</span>
                        </button>

                        <button type="button" x-on:click="prev()" aria-label="Slide anterior"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/25 text-white/80 transition-all hover:border-brand-400 hover:bg-white/10 hover:text-white">
                            @include('_partials.icon', ['name' => 'chevron-left', 'class' => 'w-5 h-5'])
                        </button>
                        <button type="button" x-on:click="next()" aria-label="Slide siguiente"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/25 text-white/80 transition-all hover:border-brand-400 hover:bg-white/10 hover:text-white">
                            @include('_partials.icon', ['name' => 'chevron-right', 'class' => 'w-5 h-5'])
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
