@php $testimonios = $page->content['testimonios']; @endphp

<section id="{{ $testimonios['id'] ?? 'testimonios' }}" class="bg-sand-100 py-20 lg:py-28"
    x-data="cardsSlider({ count: {{ count($testimonios['items']) }}, autoplay: {{ $testimonios['autoplay'] ? 'true' : 'false' }}, interval: {{ $testimonios['interval'] ?? 8000 }} })"
    x-on:mouseenter="paused = true" x-on:mouseleave="paused = false"
    x-on:focusin="paused = true" x-on:focusout="paused = false"
    aria-roledescription="carrusel" aria-labelledby="testimonios-titulo">

    <div class="shell">
        <div class="reveal mx-auto max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <span class="eyebrow eyebrow-center">{{ $testimonios['eyebrow'] }}</span>
            <h2 id="testimonios-titulo" class="mt-5 font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $testimonios['title'] }}</h2>
            <p class="mt-5 text-[0.975rem] leading-relaxed text-ink-500">{{ $testimonios['text'] }}</p>
        </div>

        <div class="reveal mt-14" x-intersect.once="$el.classList.add('is-visible')">
            <div class="overflow-hidden"
                 x-on:touchstart.passive="onTouchStart($event)" x-on:touchend.passive="onTouchEnd($event)">
                <div class="flex transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                     x-bind:style="`transform: translateX(${offset}%)`">
                    @foreach ($testimonios['items'] as $item)
                        <div class="w-full shrink-0 px-2 md:w-1/2">
                            <figure class="flex h-full flex-col rounded-2xl border border-sand-200 bg-white p-8 shadow-soft">
                                <span class="text-brand-200">
                                    @include('_partials.icon', ['name' => 'quote', 'class' => 'w-8 h-8'])
                                </span>
                                <blockquote class="mt-5 flex-1 text-[0.975rem] italic leading-relaxed text-ink-600">
                                    “{{ $item['quote'] }}”
                                </blockquote>
                                <figcaption class="mt-7 border-t border-sand-200 pt-5">
                                    <span class="block font-display text-sm font-semibold text-ink-900">{{ $item['author'] }}</span>
                                    <span class="mt-0.5 block text-xs text-ink-500">{{ $item['role'] }}</span>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 flex items-center justify-center gap-5">
                <button type="button" x-on:click="prev()" aria-label="Testimonios anteriores"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-sand-300 bg-white text-ink-600 shadow-soft transition-all hover:-translate-y-0.5 hover:border-brand-300 hover:text-brand-600">
                    @include('_partials.icon', ['name' => 'chevron-left', 'class' => 'w-5 h-5'])
                </button>

                <div class="flex items-center gap-2">
                    <template x-for="index in pages" x-bind:key="index">
                        <button type="button" x-on:click="go(index - 1)"
                                x-bind:aria-label="`Ir a la página ${index} de ${pages} de testimonios`"
                                x-bind:aria-current="page === index - 1 ? 'true' : 'false'"
                                class="h-1.5 rounded-full transition-all duration-500"
                                x-bind:class="page === index - 1 ? 'w-9 bg-brand-500' : 'w-3 bg-sand-300 hover:bg-brand-200'"></button>
                    </template>
                </div>

                <button type="button" x-on:click="next()" aria-label="Testimonios siguientes"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-sand-300 bg-white text-ink-600 shadow-soft transition-all hover:-translate-y-0.5 hover:border-brand-300 hover:text-brand-600">
                    @include('_partials.icon', ['name' => 'chevron-right', 'class' => 'w-5 h-5'])
                </button>
            </div>
        </div>
    </div>
</section>
