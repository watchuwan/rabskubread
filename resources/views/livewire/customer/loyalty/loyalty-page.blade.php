<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Loyalty Points</h1>

    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-6 mb-6">
        <div class="text-sm opacity-90">Your Balance</div>
        <div class="text-4xl font-bold">{{ number_format($balance) }} Points</div>
        <div class="text-sm mt-2 opacity-90">1 point = Rp 10,000 spent</div>
    </div>

    <h2 class="text-xl font-semibold mb-4">Transaction History</h2>

    <div class="bg-white rounded-lg shadow">
        @forelse($transactions as $transaction)
            <div class="border-b last:border-b-0 p-4 flex justify-between items-center">
                <div>
                    <div class="font-semibold">{{ $transaction->description ?? $transaction->type }}</div>
                    <div class="text-sm text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</div>
                    @if($transaction->expires_at)
                        <div class="text-xs text-gray-400">Expires: {{ $transaction->expires_at->format('d M Y') }}</div>
                    @endif
                </div>
                <div class="text-lg font-bold {{ $transaction->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                No transactions yet
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
