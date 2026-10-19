@php $faq = $page->content['faq']; @endphp

<section id="{{ $faq['id'] ?? 'faq' }}" x-data="faqAccordion()" class="bg-sand-100 py-20 lg:py-28"
         aria-labelledby="faq-titulo">
    <div class="shell">
        <div class="reveal mx-auto max-w-2xl text-center" x-intersect.once="$el.classList.add('is-visible')">
            <span class="eyebrow eyebrow-center">{{ $faq['eyebrow'] }}</span>
            <h2 id="faq-titulo" class="mt-5 font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $faq['title'] }}</h2>
            <p class="mt-5 text-[0.975rem] leading-relaxed text-ink-500">{{ $faq['text'] }}</p>
        </div>

        <div class="mx-auto mt-14 max-w-3xl space-y-3">
            @foreach ($faq['items'] as $index => $item)
                <div class="reveal overflow-hidden rounded-2xl border bg-white transition-all duration-300"
                     style="transition-delay: {{ $index * 60 }}ms"
                     x-bind:class="isOpen({{ $index }}) ? 'border-brand-200 shadow-lift' : 'border-sand-200 shadow-soft hover:border-sand-300'"
                     x-intersect.once="$el.classList.add('is-visible')">
                    <h3>
                        <button type="button" x-on:click="toggle({{ $index }})"
                                id="faq-boton-{{ $index }}" aria-controls="faq-panel-{{ $index }}"
                                x-bind:aria-expanded="isOpen({{ $index }}) ? 'true' : 'false'"
                                class="flex w-full items-center justify-between gap-5 px-6 py-5 text-left">
                            <span class="font-display text-[0.975rem] font-medium text-ink-900">{{ $item['question'] }}</span>
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full transition-all duration-300"
                                  x-bind:class="isOpen({{ $index }}) ? 'rotate-180 bg-brand-500 text-white' : 'bg-sand-100 text-ink-500'">
                                @include('_partials.icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4'])
                            </span>
                        </button>
                    </h3>

                    <div class="faq-answer" id="faq-panel-{{ $index }}" role="region"
                         aria-labelledby="faq-boton-{{ $index }}"
                         x-show="isOpen({{ $index }})" x-collapse x-cloak>
                        <div class="space-y-4 border-t border-sand-200/80 px-6 py-5">
                            @foreach (preg_split('/\R\s*\R/', trim($item['answer'])) as $parrafo)
                                <p class="text-justify text-sm leading-relaxed text-ink-500 hyphens-auto">{!! preg_replace('/\*\*(.+?)\*\*/u', '<strong class="font-medium text-ink-900">$1</strong>', e(trim($parrafo))) !!}</p>
                            @endforeach

                            @if ($item['file'] ?? false)
                                <a href="{{ $item['file']['href'] }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 rounded-full border border-sand-200 bg-sand-100 px-4 py-2 text-xs font-semibold text-ink-700 transition-colors hover:border-brand-300 hover:text-brand-600">
                                    @include('_partials.icon', ['name' => 'file-down', 'class' => 'w-4 h-4'])
                                    {{ $item['file']['label'] ?? 'Descargar PDF' }}
                                    <span class="sr-only">(PDF, se abre en una pestaña nueva)</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
