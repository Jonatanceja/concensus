@php $site = $page->content['site']; @endphp

{{--
    Header fijo: transparente sobre el hero y sólido (cristal) al hacer scroll.
    Los estados se resuelven en CSS con los atributos data-scrolled / data-open
    que escribe Alpine, para evitar parpadeos al cargar.
--}}
<header x-data="siteHeader" class="site-header fixed inset-x-0 top-0 z-50"
        x-bind:data-scrolled="scrolled" x-bind:data-open="open">
    <div class="shell flex items-center justify-between gap-6">
        {{-- Logo: versión blanca sobre el hero, a color al hacer scroll --}}
        <a href="#inicio" class="relative block shrink-0" aria-label="{{ $site['brand']['name'] }} · {{ $site['brand']['tagline'] }}">
            <img src="{{ $site['brand']['logo'] }}" alt="{{ $site['brand']['name'] }}"
                 class="logo-color">
            <img src="{{ $site['brand']['logo_light'] }}" alt="" aria-hidden="true"
                 class="logo-light absolute inset-0">
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Principal">
            @foreach ($site['nav'] as $link)
                <a href="{{ $link['href'] }}" class="nav-link relative px-3.5 py-2 text-sm font-medium">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ $site['cta']['href'] }}" class="btn btn-primary hidden sm:inline-flex !py-3 !px-6 text-sm">
                {{ $site['cta']['label'] }}
            </a>

            <button type="button" x-on:click="toggle()"
                    class="nav-toggle inline-flex h-11 w-11 items-center justify-center rounded-full border lg:hidden"
                    x-bind:aria-expanded="open ? 'true' : 'false'" aria-controls="menu-movil"
                x-bind:aria-label="open ? 'Cerrar menú' : 'Abrir menú'">
                <span x-show="!open">@include('_partials.icon', ['name' => 'menu', 'class' => 'w-5 h-5'])</span>
                <span x-show="open" x-cloak>@include('_partials.icon', ['name' => 'close', 'class' => 'w-5 h-5'])</span>
            </button>
        </div>
    </div>

    {{-- Menú móvil --}}
    <div id="menu-movil" x-show="open" x-cloak x-transition.opacity.duration.200ms class="lg:hidden border-t border-sand-200 bg-sand-50">
        <nav class="shell flex flex-col py-4" aria-label="Móvil">
            @foreach ($site['nav'] as $link)
                <a href="{{ $link['href'] }}" x-on:click="close()"
                   class="border-b border-sand-200/70 py-3.5 font-display text-lg text-ink-800 last:border-0">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ $site['cta']['href'] }}" x-on:click="close()" class="btn btn-primary mt-5">{{ $site['cta']['label'] }}</a>
        </nav>
    </div>
</header>
