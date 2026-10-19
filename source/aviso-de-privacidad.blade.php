---
title: Aviso de Privacidad · Consensus Family
description: Aviso de Privacidad de Consensus Family.
legal: true
---
@extends('_layouts.main')

@section('body')
    @php $doc = $page->content['aviso-privacidad']; @endphp
    <section class="bg-sand-50 pb-20 pt-36 lg:pb-28 lg:pt-44">
        <div class="shell">
            <div class="mx-auto max-w-3xl">
                <h1 class="font-display text-3xl font-semibold sm:text-4xl lg:text-[2.7rem]">{{ $doc['title'] }}</h1>
                <p class="mt-3 text-sm text-ink-500">Última actualización: {{ $doc['updated'] }}</p>
                <div class="legal-body mt-10">{!! $doc['body'] !!}</div>
            </div>
        </div>
    </section>
@endsection
