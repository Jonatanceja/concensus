{{-- Recuadro lateral del esquema de gobierno (condiciones, comités…). --}}
@if ($aside ?? false)
    <div class="mt-6 rounded-2xl border border-dashed border-white/15 bg-white/[0.02] p-5">
        <p class="text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-brand-300">{{ $aside['title'] }}</p>
        <ul class="mt-3 space-y-2 text-sm leading-snug text-sand-300/85">
            @foreach ($aside['items'] as $item)
                <li class="flex gap-2.5">
                    <span class="mt-[0.45rem] h-1 w-1 shrink-0 rounded-full bg-brand-400" aria-hidden="true"></span>
                    <span>{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
