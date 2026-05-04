<?php

namespace App\Livewire\Customer\Address;

use App\Models\Address;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.customer')]
class AddressForm extends Component
{
    public ?int $addressId = null;
    
    #[Rule('required|string|max:255')]
    public string $label = '';
    
    #[Rule('required|string|max:255')]
    public string $street_address = '';
    
    #[Rule('required|in:Kota,Kabupaten')]
    public string $city_type = 'Kota';
    
    #[Rule('required|string|max:100')]
    public string $city = '';
    
    #[Rule('required|string|max:100')]
    public string $district = '';

    #[Rule('required|string|max:20')]
    public string $postal_code = '';

    // Readonly fields - always set to defaults
    public string $state = 'Maluku Utara';
    public string $country = 'Indonesia';
    
    public array $cities = [
        'Kota' => ['Ternate', 'Tidore Kepulauan'],
        'Kabupaten' => ['Halmahera Utara'],
    ];
    
    public array $districts = [
        'Ternate' => [
            'Pulau Ternate', 'Ternate Selatan', 'Ternate Tengah', 'Ternate Utara',
            'Moti', 'Batang Dua', 'Pulau Hiri', 'Kota Ternate Selatan'
        ],
        'Tidore Kepulauan' => [
            'Tidore', 'Tidore Selatan', 'Tidore Utara', 'Tidore Timur',
            'Oba', 'Oba Utara', 'Oba Selatan', 'Oba Tengah'
        ],
        'Halmahera Utara' => [
            'Sofifi', 'Oba', 'Oba Utara'
        ],
    ];
    
    #[Rule('required|string|max:20')]
    public string $phone = '';
    
    #[Rule('nullable|numeric')]
    public ?string $latitude = null;
    
    #[Rule('nullable|numeric')]
    public ?string $longitude = null;
    
    public bool $is_default = false;

    public function updateFromMap($lat, $lng, $address = null): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        
        // Auto-fill address from reverse geocoding
        if ($address) {
            // Street address
            if (empty($this->street_address)) {
                $street = $address['road'] ?? $address['suburb'] ?? $address['neighbourhood'] ?? '';
                if ($street) {
                    $this->street_address = $street;
                }
            }
            
            // City & District detection
            $cityName = $address['city'] ?? $address['town'] ?? $address['village'] ?? '';
            $districtName = $address['suburb'] ?? $address['village'] ?? $address['neighbourhood'] ?? '';
            
            if ($cityName && empty($this->city)) {
                // Parse city
                if (stripos($cityName, 'ternate') !== false) {
                    $this->city_type = 'Kota';
                    $this->city = 'Ternate';
                } elseif (stripos($cityName, 'tidore') !== false) {
                    $this->city_type = 'Kota';
                    $this->city = 'Tidore Kepulauan';
                } elseif (stripos($cityName, 'sofifi') !== false || stripos($cityName, 'halmahera utara') !== false) {
                    $this->city_type = 'Kabupaten';
                    $this->city = 'Halmahera Utara';
                }
            }
            
            // District
            if ($districtName && empty($this->district)) {
                $this->district = $districtName;
            }
            
            // Postal code
            if (empty($this->postal_code)) {
                $postcode = $address['postcode'] ?? '';
                if ($postcode) {
                    $this->postal_code = $postcode;
                }
            }
        }
    }

    public function mount(?int $address = null): void
    {
        // Set default values for new addresses
        if (!$address) {
            $this->state = 'Maluku Utara';
            $this->country = 'Indonesia';
            $this->city_type = 'Kota';
            $this->city = 'Ternate';
        }
        
        if ($address) {
            $this->addressId = $address;
            $addressModel = Address::where('customer_id', auth()->guard('customer')->id())
                ->findOrFail($address);

            $this->label = $addressModel->label;
            $this->street_address = $addressModel->street_address;
            
            // Parse city type and name
            $fullCity = $addressModel->city;
            if (str_starts_with($fullCity, 'Kota ')) {
                $this->city_type = 'Kota';
                $this->city = str_replace('Kota ', '', $fullCity);
            } elseif (str_starts_with($fullCity, 'Kabupaten ')) {
                $this->city_type = 'Kabupaten';
                $this->city = str_replace('Kabupaten ', '', $fullCity);
            } else {
                $this->city_type = 'Kota';
                $this->city = $fullCity;
            }
            
            $this->district = $addressModel->district ?? '';
            $this->state = $addressModel->state;
            $this->postal_code = $addressModel->postal_code;
            $this->country = $addressModel->country;
            $this->phone = $addressModel->phone;
            $this->latitude = $addressModel->latitude;
            $this->longitude = $addressModel->longitude;
            $this->is_default = $addressModel->is_default;
        }
    }

    public function save(): void
    {
        $this->validate();

        $customer = auth()->guard('customer')->user();

        // Always use default values for readonly fields
        $state = 'Maluku Utara';
        $country = 'Indonesia';
        
        // Combine city_type and city
        $fullCity = $this->city_type . ' ' . $this->city;

        // If setting as default, reset other defaults
        if ($this->is_default) {
            Address::where('customer_id', $customer->id)->update(['is_default' => false]);
        }

        if ($this->addressId) {
            // Update existing
            $address = Address::where('customer_id', $customer->id)
                ->findOrFail($this->addressId);

            $address->update([
                'label' => $this->label,
                'street_address' => $this->street_address,
                'city' => $fullCity,
                'district' => $this->district,
                'state' => $state,
                'postal_code' => $this->postal_code,
                'country' => $country,
                'phone' => $this->phone,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'is_default' => $this->is_default,
            ]);

            $this->dispatch('toast', message: 'Alamat berhasil diupdate', type: 'success');
        } else {
            // Create new
            Address::create([
                'customer_id' => $customer->id,
                'label' => $this->label,
                'street_address' => $this->street_address,
                'city' => $fullCity,
                'district' => $this->district,
                'state' => $state,
                'postal_code' => $this->postal_code,
                'country' => $country,
                'phone' => $this->phone,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'is_default' => $this->is_default,
            ]);

            $this->dispatch('toast', message: 'Alamat berhasil ditambahkan', type: 'success');
        }

        redirect()->route('addresses.index');
    }

    public function render()
    {
        $title = $this->addressId ? 'Edit Alamat' : 'Tambah Alamat Baru';
        
        return view('livewire.customer.address.address-form', [
            'title' => $title,
        ]);
    }
}
