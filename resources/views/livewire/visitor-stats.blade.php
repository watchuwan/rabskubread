<ul class="space-y-2.5">
    <li class="flex items-center justify-between">
        <span class="text-neutral-400 text-sm">Hari Ini</span>
        <span class="text-amber-400 font-semibold text-sm">{{ number_format($today) }}</span>
    </li>
    <li class="flex items-center justify-between">
        <span class="text-neutral-400 text-sm">Minggu Ini</span>
        <span class="text-amber-400 font-semibold text-sm">{{ number_format($week) }}</span>
    </li>
    <li class="flex items-center justify-between">
        <span class="text-neutral-400 text-sm">Bulan Ini</span>
        <span class="text-amber-400 font-semibold text-sm">{{ number_format($month) }}</span>
    </li>
    <li class="flex items-center justify-between pt-2 border-t border-neutral-800">
        <span class="text-neutral-400 text-sm">Total</span>
        <span class="text-amber-400 font-bold text-sm">{{ number_format($total) }}</span>
    </li>
</ul>
