<?php

namespace App\Livewire\Customer\Address;

use App\Models\Address;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class AddressList extends Component
{
    public function delete(int $addressId): void
    {
        $address = Address::where('customer_id', auth()->guard('customer')->id())->findOrFail($addressId);
        
        if ($address->is_default) {
            $this->dispatch('toast', message: 'Tidak dapat menghapus alamat default', type: 'error');
            return;
        }
        
        $address->delete();
        
        $this->dispatch('toast', message: 'Alamat berhasil dihapus', type: 'success');
    }

    public function setDefault(int $addressId): void
    {
        $customer = auth()->guard('customer')->user();
        
        // Reset all default addresses
        Address::where('customer_id', $customer->id)->update(['is_default' => false]);
        
        // Set new default
        Address::where('customer_id', $customer->id)->where('id', $addressId)->update(['is_default' => true]);
        
        $this->dispatch('toast', message: 'Alamat default berhasil diubah', type: 'success');
    }

    public function render()
    {
        $addresses = Address::where('customer_id', auth()->guard('customer')->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.customer.address.address-list', [
            'addresses' => $addresses,
        ]);
    }
}
