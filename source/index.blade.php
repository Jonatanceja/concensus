@extends('_layouts.main')

@section('body')
    {{-- Cada sección es un partial independiente y su contenido vive en source/_content/*.md --}}
    @include('_partials.sections.hero')
    @include('_partials.sections.introduccion')
    @include('_partials.sections.biografia')
    @include('_partials.sections.faq')
    @include('_partials.sections.servicios')
    @include('_partials.sections.testimonios')
    @include('_partials.sections.cta')
    @include('_partials.sections.contacto')
@endsection
