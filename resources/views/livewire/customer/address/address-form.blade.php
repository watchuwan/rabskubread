<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ $title }}</h1>
            <p class="text-neutral-600">Lengkapi informasi alamat pengiriman Anda</p>
        </div>

        <div class="card p-6 lg:p-8">
            <form wire:submit="save" class="space-y-6">
                <!-- Address Label -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Label Alamat <span class="text-danger">*</span>
                    </label>
                    <input
                        wire:model="label"
                        placeholder="Contoh: Rumah, Kantor, Apartemen"
                        class="input w-full"
                    />
                    @error('label')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Street Address -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Alamat Lengkap <span class="text-danger">*</span>
                    </label>
                    <textarea
                        wire:model="street_address"
                        rows="3"
                        placeholder="Nama jalan, nomor rumor, nama gedung, dll"
                        class="input w-full"
                    ></textarea>
                    @error('street_address')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Map Picker -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Pilih Lokasi di Peta
                    </label>
                    <div id="map" class="w-full h-96 rounded-lg border border-neutral-300 relative z-0" wire:ignore></div>
                    <div class="mt-2 flex items-center gap-4 text-xs text-neutral-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Koordinat: 
                                <span class="font-mono font-medium">
                                    {{ $latitude ?? '0.7893' }}, {{ $longitude ?? '127.3619' }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-neutral-500 mt-2">
                        📍 Klik pada peta atau drag marker untuk menandai lokasi. Alamat akan otomatis terisi.
                    </p>
                </div>

                <!-- Province -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Provinsi <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        value="Maluku Utara"
                        readonly
                        class="input w-full bg-neutral-100 cursor-not-allowed"
                    />
                </div>

                <!-- City Type & City -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- City Type -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            Tipe Wilayah <span class="text-danger">*</span>
                        </label>
                        <select
                            wire:model.live="city_type"
                            class="input w-full"
                        >
                            <option value="Kota">Kota</option>
                            <option value="Kabupaten">Kabupaten</option>
                        </select>
                    </div>

                    <!-- City Name -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            {{ $city_type }} <span class="text-danger">*</span>
                        </label>
                        <select
                            wire:model.live="city"
                            class="input w-full"
                        >
                            <option value="">Pilih {{ $city_type }}</option>
                            @foreach($cities[$city_type] as $cityName)
                                <option value="{{ $cityName }}">{{ $cityName }}</option>
                            @endforeach
                        </select>
                        @error('city')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- District -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Kecamatan <span class="text-danger">*</span>
                    </label>
                    @if($city && isset($districts[$city]))
                        <select
                            wire:model="district"
                            class="input w-full"
                        >
                            <option value="">Pilih Kecamatan</option>
                            @foreach($districts[$city] as $districtName)
                                <option value="{{ $districtName }}">{{ $districtName }}</option>
                            @endforeach
                        </select>
                    @else
                        <input
                            type="text"
                            value="Pilih kota/kabupaten terlebih dahulu"
                            readonly
                            class="input w-full bg-neutral-100 cursor-not-allowed"
                        />
                    @endif
                    <p class="text-xs text-neutral-500 mt-1">Akan otomatis terisi saat pilih lokasi di peta</p>
                    @error('district')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Postal Code & Country -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Postal Code -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            Kode Pos <span class="text-danger">*</span>
                        </label>
                        <input
                            wire:model="postal_code"
                            type="text"
                            placeholder="Contoh: 97728"
                            class="input w-full"
                            list="postcode-suggestions"
                        />
                        <datalist id="postcode-suggestions">
                            <!-- Kota Ternate -->
                            <option value="97728">Ternate Selatan</option>
                            <option value="97729">Ternate Tengah</option>
                            <option value="97721">Ternate Utara</option>
                            <option value="97722">Pulau Ternate</option>
                            <option value="97723">Moti</option>
                            <option value="97724">Batang Dua</option>
                            <option value="97725">Pulau Hiri</option>
                            <option value="97726">Kota Ternate Selatan</option>
                            <!-- Kota Tidore Kepulauan -->
                            <option value="97811">Tidore</option>
                            <option value="97812">Tidore Selatan</option>
                            <option value="97813">Tidore Utara</option>
                            <option value="97814">Tidore Timur</option>
                            <option value="97815">Oba</option>
                            <option value="97816">Oba Utara</option>
                            <option value="97817">Oba Selatan</option>
                            <option value="97818">Oba Tengah</option>
                            <!-- Kabupaten Halmahera Utara (Sofifi) -->
                            <option value="97762">Sofifi</option>
                            <option value="97763">Oba</option>
                            <option value="97764">Oba Utara</option>
                        </datalist>
                        <p class="text-xs text-neutral-500 mt-1">Akan otomatis terisi saat pilih lokasi di peta</p>
                        @error('postal_code')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                            Negara <span class="text-danger">*</span>
                        </label>
                        <input
                            wire:model="country"
                            value="Indonesia"
                            readonly
                            class="input w-full bg-neutral-100 cursor-not-allowed"
                        />
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Nomor Telepon <span class="text-danger">*</span>
                    </label>
                    <input
                        wire:model="phone"
                        type="tel"
                        placeholder="Contoh: 0812-3456-7890"
                        class="input w-full"
                    />
                    @error('phone')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Address Checkbox -->
                <div class="flex items-start gap-3 p-4 bg-neutral-50 rounded-lg">
                    <input
                        type="checkbox"
                        wire:model="is_default"
                        class="w-5 h-5 text-cream-600 border-neutral-300 rounded focus:ring-cream-500 mt-0.5"
                    />
                    <div class="flex-1">
                        <label class="text-sm font-medium text-neutral-700 cursor-pointer">
                            Jadikan sebagai alamat default
                        </label>
                        <p class="text-xs text-neutral-500 mt-1">
                            Alamat ini akan otomatis terpilih saat checkout
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-neutral-200">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $addressId ? 'Update Alamat' : 'Simpan Alamat' }}
                    </button>
                    <a href="{{ route('addresses.index') }}" class="btn-secondary" wire:navigate>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    /* Fix leaflet z-index to prevent overlap with header */
    .leaflet-pane,
    .leaflet-top,
    .leaflet-bottom {
        z-index: 1 !important;
    }
    
    .leaflet-control-container {
        z-index: 1 !important;
    }
    
    /* Ensure map container doesn't overflow */
    #map {
        position: relative;
        z-index: 0;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
document.addEventListener('livewire:navigated', function() {
    // Default coordinates (Ternate, Maluku Utara)
    const defaultLat = parseFloat(@this.get('latitude')) || 0.7893;
    const defaultLng = parseFloat(@this.get('longitude')) || 127.3619;
    
    // Maluku Utara bounds
    const malukuUtaraBounds = [
        [-1.5, 126.0],  // Southwest
        [3.0, 129.5]    // Northeast
    ];
    
    // Initialize map
    const map = L.map('map', {
        maxBounds: malukuUtaraBounds,
        maxBoundsViscosity: 1.0,
        minZoom: 8
    }).setView([defaultLat, defaultLng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Geocoder instance for reverse geocoding
    const geocoder = L.Control.Geocoder.nominatim({
        geocodingQueryParams: {
            countrycodes: 'id',
            bounded: 1,
            viewbox: malukuUtaraBounds[0][1] + ',' + malukuUtaraBounds[0][0] + ',' + malukuUtaraBounds[1][1] + ',' + malukuUtaraBounds[1][0]
        }
    });
    
    // Add search control
    L.Control.geocoder({
        geocoder: geocoder,
        defaultMarkGeocode: false,
        placeholder: 'Cari lokasi di Maluku Utara...',
        errorMessage: 'Lokasi tidak ditemukan'
    })
    .on('markgeocode', function(e) {
        const latlng = e.geocode.center;
        map.setView(latlng, 15);
        marker.setLatLng(latlng);
        updateLocation(latlng);
    })
    .addTo(map);
    
    // Add marker
    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);
    
    // Function to update location and reverse geocode
    function updateLocation(latlng) {
        @this.set('latitude', latlng.lat.toFixed(6));
        @this.set('longitude', latlng.lng.toFixed(6));
        
        // Reverse geocoding to get address
        geocoder.reverse(latlng, map.getZoom(), function(results) {
            if (results && results.length > 0) {
                const addr = results[0].properties.address;
                console.log('Reverse geocoding result:', addr); // Debug
                
                @this.call('updateFromMap', latlng.lat.toFixed(6), latlng.lng.toFixed(6), {
                    road: addr.road || '',
                    suburb: addr.suburb || '',
                    neighbourhood: addr.neighbourhood || '',
                    city: addr.city || '',
                    town: addr.town || '',
                    village: addr.village || '',
                    county: addr.county || '',
                    postcode: addr.postcode || ''
                });
            }
        });
    }
    
    // Update coordinates when marker is dragged
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateLocation(position);
    });
    
    // Update marker position when clicking on map
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLocation(e.latlng);
    });
    
    // Fix map rendering issue
    setTimeout(() => map.invalidateSize(), 100);
});
</script>
@endpush
