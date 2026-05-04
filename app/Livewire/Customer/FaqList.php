<?php

namespace App\Livewire\Customer;

use App\Models\Faq;
use Livewire\Component;

class FaqList extends Component
{
    public function render()
    {
        return view('livewire.customer.faq-list', [
            'faqs' => Faq::active()->get()->groupBy('category'),
        ]);
    }
}
