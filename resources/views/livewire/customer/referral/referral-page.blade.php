<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Referral Program</h1>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Your Referral Code</h2>
        <div class="flex items-center gap-4">
            <input type="text" value="{{ $referralCode }}" readonly class="flex-1 px-4 py-2 border rounded-lg bg-gray-50 font-mono text-lg">
            <button wire:click="copyCode" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Copy
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-2">Share this code with friends and earn 100 points for each successful referral!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-600 text-sm">Total Referrals</div>
            <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-600 text-sm">Rewarded</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['rewarded'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-600 text-sm">Pending</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-600 text-sm">Total Points Earned</div>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_points']) }}</div>
        </div>
    </div>

    <h2 class="text-xl font-semibold mb-4">Referral History</h2>

    <div class="bg-white rounded-lg shadow">
        @forelse($referrals as $referral)
            <div class="border-b last:border-b-0 p-4 flex justify-between items-center">
                <div>
                    <div class="font-semibold">{{ $referral->referred->name }}</div>
                    <div class="text-sm text-gray-500">{{ $referral->created_at->format('d M Y') }}</div>
                </div>
                <div>
                    @if($referral->is_rewarded)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            +{{ $referral->reward_points }} points
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                            Pending
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                No referrals yet. Start sharing your code!
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $referrals->links() }}
    </div>
</div>
