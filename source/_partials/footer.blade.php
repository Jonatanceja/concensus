@php $site = $page->content['site']; @endphp

<footer class="bg-ink-900 text-ink-400">
    <div class="shell py-16 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <img src="{{ $site['brand']['logo_light'] }}" alt="{{ $site['brand']['name'] }}" class="h-20 w-auto">
                <p class="mt-5 max-w-sm text-sm leading-relaxed">{{ $site['footer']['about'] }}</p>

                <div class="mt-7 flex items-center gap-3">
                    @foreach ($site['footer']['social'] as $social)
                        <a href="{{ $social['href'] }}" aria-label="{{ $social['label'] }}"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-ink-400 transition-all duration-300 hover:-translate-y-0.5 hover:border-brand-400/50 hover:bg-brand-500/10 hover:text-brand-300">
                            @include('_partials.icon', ['name' => $social['icon'], 'class' => 'w-[18px] h-[18px]'])
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach ($site['footer']['columns'] as $column)
                <div class="lg:col-span-3">
                    <h3 class="text-[0.7rem] font-semibold uppercase tracking-[0.18em] text-white/70">{{ $column['title'] }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ($column['links'] as $link)
                            <li>
                                <a href="{{ $link['href'] }}" class="transition-colors hover:text-brand-300">{{ $link['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="lg:col-span-1"></div>
        </div>

        <div class="mt-14 border-t border-white/10 pt-6 text-xs text-ink-500">
            © {{ date('Y') }} {{ $site['footer']['copyright'] }}
        </div>
    </div>
</footer>
