@php
    $quiz = $page->content['termometro'];
    $contactoForm = $page->content['contacto']['form'];
    $config = [
        'questions' => $quiz['questions'],
        'results' => $quiz['results'],
        'action' => $contactoForm['action'] ?? '#',
        'netlify' => $contactoForm['netlify'] ?? false,
        'formName' => 'termometro',
    ];
@endphp

{{-- Popup del termómetro: una pregunta por pantalla, barra de progreso y captura de datos al final --}}
<div x-data="termometro({{ json_encode($config) }})"
     x-on:open-termometro.window="open()"
     x-on:keydown.escape.window="isOpen && close()"
     x-show="isOpen" x-cloak
     class="fixed inset-0 z-[100] flex items-end justify-center sm:items-center sm:p-6"
     role="dialog" aria-modal="true" aria-labelledby="termometro-titulo">
    <div class="absolute inset-0 bg-ink-900/70 backdrop-blur-sm" x-on:click="close()"
         x-show="isOpen" x-transition.opacity></div>

    <div x-ref="panel" tabindex="-1"
         x-show="isOpen"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="relative flex max-h-[100dvh] w-full flex-col bg-white shadow-lift outline-none sm:max-h-[90dvh] sm:max-w-xl sm:rounded-3xl max-sm:h-[100dvh]">

        {{-- Cabecera y progreso --}}
        <div class="shrink-0 px-6 pt-6 sm:px-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="termometro-titulo" class="font-display text-2xl font-semibold leading-tight text-ink-900 sm:text-3xl">{{ $quiz['title'] }}</h2>
                    <p class="mt-2 text-base text-ink-500 sm:text-lg" x-show="step < total && !sent">{{ $quiz['text'] }}</p>
                </div>
                <button type="button" x-on:click="close()" aria-label="Cerrar"
                        class="-mr-2 -mt-1 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-ink-500 transition-colors hover:bg-sand-100 hover:text-ink-900">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <div class="mt-5" x-show="!sent">
                <div class="mb-2.5 flex items-center justify-between text-xs font-bold uppercase tracking-[0.14em] text-ink-700">
                    <span x-text="step < total ? 'Pregunta ' + (step + 1) + ' de ' + total : 'Último paso'"></span>
                    <span class="text-base text-brand-600" x-text="Math.round(progress) + '%'"></span>
                </div>
                <div class="h-4 overflow-hidden rounded-full bg-brand-100 shadow-inner" role="progressbar" aria-valuemin="0" aria-valuemax="100" x-bind:aria-valuenow="Math.round(progress)">
                    <div class="h-full rounded-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-400 shadow-[0_0_14px_rgb(232_112_15/0.6)] transition-all duration-500 ease-out" x-bind:style="'width:' + progress + '%'"></div>
                </div>
            </div>
        </div>

        {{-- Contenido --}}
        <div class="min-h-0 flex-1 overflow-y-auto px-6 py-8 sm:px-8">
            {{-- Preguntas --}}
            <template x-if="step < total && !sent">
                <div>
                    <p class="font-display text-xl font-medium leading-snug text-ink-900 sm:text-2xl" x-text="questions[step]" aria-live="polite"></p>
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <button type="button" x-on:click="answer(true)"
                                class="rounded-2xl border-2 px-4 py-4 text-base font-semibold transition-all duration-200"
                                x-bind:class="answers[step] === true ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-sand-200 text-ink-800 hover:border-brand-300'">
                            Sí pasa
                        </button>
                        <button type="button" x-on:click="answer(false)"
                                class="rounded-2xl border-2 px-4 py-4 text-base font-semibold transition-all duration-200"
                                x-bind:class="answers[step] === false ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-sand-200 text-ink-800 hover:border-brand-300'">
                            No pasa
                        </button>
                    </div>
                </div>
            </template>

            {{-- Captura de datos --}}
            <div x-show="step === total && !sent" x-cloak>
                <h3 class="font-display text-xl font-semibold text-ink-900 sm:text-2xl">{{ $quiz['form']['title'] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-ink-500">{{ $quiz['form']['text'] }}</p>

                <form name="termometro" action="{{ $config['action'] }}" method="POST"
                      @if ($config['netlify']) data-netlify="true" netlify-honeypot="bot-field" @endif
                      x-on:submit.prevent="submit($event)" novalidate class="mt-6">
                    <input type="hidden" name="form-name" value="termometro">
                    <p class="hidden" aria-hidden="true"><label>No llenar este campo: <input name="bot-field" tabindex="-1" autocomplete="off"></label></p>
                    <input type="hidden" name="puntaje" x-bind:value="score + ' de ' + total">
                    <input type="hidden" name="resultado" x-bind:value="result.title">
                    <input type="hidden" name="respuestas" x-bind:value="summary">

                    <div class="space-y-4">
                        <div>
                            <label class="field-label" for="termometro-nombre">Nombre <span class="text-brand-600" aria-hidden="true">*</span></label>
                            <input id="termometro-nombre" name="nombre" type="text" class="field" autocomplete="name" placeholder="Ej. María Fernanda Torres" required>
                        </div>
                        <div>
                            <label class="field-label" for="termometro-email">Correo electrónico <span class="text-brand-600" aria-hidden="true">*</span></label>
                            <input id="termometro-email" name="email" type="email" class="field" autocomplete="email" placeholder="nombre@empresa.com" required>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-brand-600" x-show="error" x-text="error" role="alert"></p>

                    <button type="submit" class="btn btn-primary mt-6 w-full" x-bind:disabled="sending">
                        <span x-show="!sending">{{ $quiz['form']['submit'] }}</span>
                        <span x-show="sending" x-cloak>Enviando…</span>
                    </button>
                    <p class="mt-4 text-xs leading-relaxed text-ink-500">{{ $quiz['form']['privacy'] }}</p>
                </form>
            </div>

            {{-- Resultado --}}
            <div x-show="sent" x-cloak role="status" aria-live="polite" class="text-center">
                <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                    @include('_partials.icon', ['name' => 'check', 'class' => 'w-7 h-7'])
                </span>
                <p class="mt-5 font-display text-4xl font-semibold text-ink-900"><span x-text="score"></span> <small class="text-base font-normal text-ink-500">de <span x-text="total"></span></small></p>
                <h3 class="mt-3 font-display text-xl font-semibold text-brand-600" x-text="result.title"></h3>
                <p class="mx-auto mt-3 max-w-sm text-sm leading-relaxed text-ink-500" x-text="result.text"></p>
                <p class="mx-auto mt-5 max-w-sm text-xs leading-relaxed text-ink-500">{{ $quiz['done']['text'] }}</p>
                <a href="{{ $quiz['done']['cta_href'] }}" x-on:click="close()" class="btn btn-primary mt-7">{{ $quiz['done']['cta_label'] }}</a>
            </div>
        </div>

        {{-- Pie: volver --}}
        <div class="shrink-0 border-t border-sand-200 px-6 py-4 sm:px-8" x-show="step > 0 && !sent">
            <button type="button" x-on:click="back()" class="inline-flex items-center gap-2 text-sm font-semibold text-ink-500 transition-colors hover:text-brand-600">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                Anterior
            </button>
        </div>
    </div>
</div>
