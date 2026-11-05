---
title: Gracias · Consensus Family
description: Hemos recibido tu solicitud.
legal: true
noindex: true
---
@extends('_layouts.main')

@section('body')
    <section class="bg-sand-50 pb-20 pt-36 lg:pb-28 lg:pt-44">
        <div class="shell">
            <div class="mx-auto max-w-xl text-center" role="status">
                <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                    @include('_partials.icon', ['name' => 'check', 'class' => 'w-7 h-7'])
                </span>
                <h1 class="mt-6 font-display text-3xl font-semibold sm:text-4xl">¡Gracias por escribirnos!</h1>
                <p class="mx-auto mt-4 max-w-md text-[0.975rem] leading-relaxed text-ink-500">
                    Hemos recibido tu solicitud. Pronto nos pondremos en contacto contigo para coordinar tu cita.
                </p>
                <a href="/" class="btn btn-primary mt-8">Volver al inicio</a>
            </div>
        </div>
    </section>
@endsection
