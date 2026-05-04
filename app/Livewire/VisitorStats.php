<?php

namespace App\Livewire;

use Livewire\Component;

class VisitorStats extends Component
{
    protected $polling = '30s';

    public function render(): \Illuminate\View\View
    {
        return view('livewire.visitor-stats', [
            'today' => cache()->get('visitors_today', 0),
            'week'  => cache()->get('visitors_week', 0),
            'month' => cache()->get('visitors_month', 0),
            'total' => cache()->get('visitors_total', 0),
        ]);
    }
}
