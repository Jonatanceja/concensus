@php
    $contacto = $page->content['contacto'];
    $form = $contacto['form'];
@endphp

<section id="{{ $contacto['id'] ?? 'contacto' }}" x-data class="relative overflow-hidden bg-sand-50 py-20 lg:py-28" aria-labelledby="contacto-titulo">
    <div class="pointer-events-none absolute -left-32 bottom-0 h-96 w-96 rounded-full bg-brand-100/50 blur-3xl"></div>

    <div class="shell relative">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
            {{-- Columna informativa --}}
            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <span class="eyebrow">{{ $contacto['eyebrow'] }}</span>
                <h2 id="contacto-titulo" class="mt-5 font-display text-3xl font-semibold sm:text-4xl">{{ $contacto['title'] }}</h2>
                <p class="mt-5 max-w-md text-[0.975rem] leading-relaxed text-ink-500">{{ $contacto['text'] }}</p>

                <ul class="mt-10 space-y-5">
                    @foreach ($contacto['details'] as $detail)
                        <li class="flex items-start gap-4">
                            <span class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-sand-200 bg-white text-brand-600 shadow-soft">
                                @include('_partials.icon', ['name' => $detail['icon'], 'class' => 'w-[18px] h-[18px]'])
                            </span>
                            <span>
                                <span class="block text-[0.7rem] font-semibold uppercase tracking-[0.14em] text-ink-500">{{ $detail['label'] }}</span>
                                @if ($detail['href'] ?? false)
                                    <a href="{{ $detail['href'] }}" class="mt-1 block text-[0.95rem] font-medium text-ink-800 transition-colors hover:text-brand-600">{{ $detail['value'] }}</a>
                                @else
                                    <span class="mt-1 block text-[0.95rem] font-medium text-ink-800">{{ $detail['value'] }}</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Formulario --}}
            <div class="reveal lg:col-span-7" style="transition-delay: 120ms" x-intersect.once="$el.classList.add('is-visible')">
                <div class="rounded-4xl border border-sand-200 bg-white p-8 shadow-lift sm:p-10" x-data="contactForm">
                    @php $netlify = $form['netlify'] ?? false; $netlifyName = $form['netlify_name'] ?? 'contacto'; @endphp

                    <form action="{{ $form['action'] }}" method="{{ $form['method'] ?? 'POST' }}"
                          @if ($netlify) name="{{ $netlifyName }}" data-netlify="true" netlify-honeypot="bot-field" @endif
                          x-on:submit="submit($event)" x-show="!sent" novalidate>
                        @if ($netlify)
                            {{-- Campos que necesita Netlify Forms --}}
                            <input type="hidden" name="form-name" value="{{ $netlifyName }}">
                            <p class="hidden" aria-hidden="true">
                                <label>No llenar este campo: <input name="bot-field" tabindex="-1" autocomplete="off"></label>
                            </p>
                        @endif

                        <p class="mb-6 text-xs text-ink-500">Los campos marcados con <span class="text-brand-600">*</span> son obligatorios.</p>

                        <div class="grid gap-5 sm:grid-cols-2">
                            @foreach ($form['fields'] as $field)
                                <div class="{{ ($field['width'] ?? 'full') === 'full' ? 'sm:col-span-2' : '' }}">
                                    <label class="field-label" for="field-{{ $field['name'] }}">
                                        {{ $field['label'] }}
                                        @if ($field['required'] ?? false)<span class="text-brand-600" aria-hidden="true">*</span>@endif
                                    </label>

                                    @if ($field['type'] === 'textarea')
                                        <textarea id="field-{{ $field['name'] }}" name="{{ $field['name'] }}" rows="5"
                                                  class="field resize-none" placeholder="{{ $field['placeholder'] ?? '' }}"
                                                  @if ($field['required'] ?? false) required @endif></textarea>
                                    @elseif ($field['type'] === 'select')
                                        <select id="field-{{ $field['name'] }}" name="{{ $field['name'] }}" class="field appearance-none bg-[url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236b7689%22 stroke-width=%221.6%22 stroke-linecap=%22round%22><path d=%22m6 9 6 6 6-6%22/></svg>')] bg-[length:18px_18px] bg-[right_1rem_center] bg-no-repeat pr-11"
                                                @if ($field['required'] ?? false) required @endif>
                                            <option value="" selected disabled>Seleccione una opción</option>
                                            @foreach ($field['options'] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input id="field-{{ $field['name'] }}" name="{{ $field['name'] }}" type="{{ $field['type'] }}"
                                               class="field" placeholder="{{ $field['placeholder'] ?? '' }}"
                                               @if ($field['autocomplete'] ?? false) autocomplete="{{ $field['autocomplete'] }}" @endif
                                               @if ($field['required'] ?? false) required @endif>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary mt-8 w-full sm:w-auto" x-bind:disabled="sending">
                            <span x-show="!sending">{{ $form['submit'] }}</span>
                            <span x-show="sending" x-cloak>Enviando…</span>
                            @include('_partials.icon', ['name' => 'arrow-right', 'class' => 'w-4 h-4'])
                        </button>

                        @if ($form['privacy'] ?? false)
                            <p class="mt-5 text-xs leading-relaxed text-ink-500">{{ $form['privacy'] }}</p>
                        @endif
                    </form>

                    {{-- Confirmación --}}
                    <div x-show="sent" x-cloak x-transition.opacity role="status" aria-live="polite" class="py-10 text-center">
                        <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                            @include('_partials.icon', ['name' => 'check', 'class' => 'w-7 h-7'])
                        </span>
                        <h3 class="mt-6 font-display text-xl font-semibold">¡Gracias por escribirnos!</h3>
                        <p class="mx-auto mt-3 max-w-sm text-sm leading-relaxed text-ink-500">
                            Hemos recibido su solicitud. Le contactaremos en menos de 24 horas hábiles para coordinar su cita.
                        </p>
                        <button type="button" x-on:click="sent = false" class="mt-7 text-sm font-semibold text-brand-600 hover:text-brand-700">
                            Enviar otra solicitud
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
