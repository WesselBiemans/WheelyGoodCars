@extends('layouts.main')

@section('title', 'Tag gebruik')

@section('content')
<div class="max-w-5xl mx-auto p-6 sm:p-8">
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h1 class="text-2xl font-bold text-slate-800">Tag gebruiksoverzicht</h1>
            <p class="text-slate-500 mt-1">Aantal keer dat elke tag aan een auto is gekoppeld.</p>

            <form method="GET" action="{{ route('admin.tags.index') }}" class="mt-4 flex flex-wrap items-center gap-3">
                <label for="count_scope" class="text-sm font-medium text-slate-700">Telling op basis van</label>
                <select id="count_scope" name="count_scope" class="rounded-md border-slate-300 text-sm focus:border-cyan-700 focus:ring-cyan-700">
                    <option value="all" {{ $countScope === 'all' ? 'selected' : '' }}>Alle auto's</option>
                    <option value="exclude_unsold" {{ $countScope === 'exclude_unsold' ? 'selected' : '' }}>Alleen verkochte auto's (onverkocht uitsluiten)</option>
                    <option value="exclude_sold" {{ $countScope === 'exclude_sold' ? 'selected' : '' }}>Alleen onverkochte auto's (verkocht uitsluiten)</option>
                </select>
                <button type="submit" class="inline-flex items-center rounded-md bg-cyan-700 px-3 py-2 text-sm font-semibold text-white hover:bg-cyan-800">Toepassen</button>
            </form>

            <p class="mt-3 text-sm text-slate-600">
                @if ($countScope === 'exclude_unsold')
                    Je ziet nu alleen tag-gebruik op verkochte auto's.
                @elseif ($countScope === 'exclude_sold')
                    Je ziet nu alleen tag-gebruik op onverkochte auto's.
                @else
                    Je ziet nu tag-gebruik op alle auto's.
                @endif
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Tag</th>
                        <th class="px-6 py-3 font-semibold">Kleur</th>
                        <th class="px-6 py-3 font-semibold text-right">Gebruikt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($tags as $tag)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $tag->name }}</td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center gap-2 text-slate-700">
                                    <span class="tag-color-chip inline-flex items-center rounded-full border border-slate-300 px-2 py-0.5 text-xs font-medium" data-tag-color="{{ $tag->color }}">{{ $tag->color }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-cyan-700">{{ $tag->cars_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-slate-500">Geen tags gevonden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.tag-color-chip').forEach(function (chip) {
            const color = chip.getAttribute('data-tag-color');

            if (color) {
                chip.style.backgroundColor = color;

                const hex = color.replace('#', '');
                const isShortHex = hex.length === 3;
                const fullHex = isShortHex
                    ? hex.split('').map(function (c) { return c + c; }).join('')
                    : hex;

                if (/^[0-9a-fA-F]{6}$/.test(fullHex)) {
                    const r = parseInt(fullHex.slice(0, 2), 16);
                    const g = parseInt(fullHex.slice(2, 4), 16);
                    const b = parseInt(fullHex.slice(4, 6), 16);
                    const luminance = (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;
                    const isDark = luminance < 0.55;

                    chip.style.color = isDark ? '#F8FAFC' : '#0F172A';
                    chip.style.textShadow = isDark
                        ? '0 1px 1px rgba(0, 0, 0, 0.35)'
                        : '0 1px 1px rgba(255, 255, 255, 0.45)';
                    chip.style.borderColor = isDark
                        ? 'rgba(248, 250, 252, 0.35)'
                        : 'rgba(15, 23, 42, 0.15)';
                }
            }
        });
    });
</script>
@endsection
