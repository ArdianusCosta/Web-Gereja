<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Table --}}
        <div class="mt-6">
            {{ $this->table }}
        </div>

        {{-- Map --}}
        <div id="map" class="w-full h-96 rounded-lg border shadow"></div>

        {{-- Datalist untuk auto-complete --}}
        <datalist id="alamat-list"></datalist>

        {{-- Form --}}
        <form wire:submit.prevent="save" class="space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit" color="warning" class="w-full">
                Simpan Alamat
            </x-filament::button>
        </form>
    </div>

    @push('styles')
        {{-- CSS Leaflet --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            #map {
                width: 100%;
                height: 400px;
                border-radius: 10px;
                overflow: hidden;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            const alamatInput = document.getElementById("alamat-input");
            const latInput = document.getElementById("latitude-input");
            const lonInput = document.getElementById("longitude-input");
            const datalist = document.getElementById("alamat-list");

            let marker;

            // Init Map
            let lat = latInput.value || -6.200000; // default Jakarta
            let lon = lonInput.value || 106.816666;
            const map = L.map('map').setView([lat, lon], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Kalau ada data sebelumnya → tampilkan marker
            if (latInput.value && lonInput.value) {
                marker = L.marker([latInput.value, lonInput.value])
                    .addTo(map)
                    .bindPopup(alamatInput.value || "Lokasi tersimpan")
                    .openPopup();
            }

            // Auto-complete alamat dari API
            let timeout;
            alamatInput.addEventListener("input", function () {
                clearTimeout(timeout);
                const query = this.value;
                if (query.length < 3) return;

                timeout = setTimeout(async () => {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=id&limit=5`);
                    const data = await res.json();

                    datalist.innerHTML = "";
                    data.forEach(item => {
                        const option = document.createElement("option");
                        option.value = item.display_name;
                        option.dataset.lat = item.lat;
                        option.dataset.lon = item.lon;
                        datalist.appendChild(option);
                    });
                }, 500);
            });

            // Saat pilih alamat dari datalist
            alamatInput.addEventListener("change", function () {
                const selected = [...datalist.options].find(opt => opt.value === this.value);
                if (selected) {
                    const lat = selected.dataset.lat;
                    const lon = selected.dataset.lon;

                    latInput.value = lat;
                    lonInput.value = lon;

                    map.setView([lat, lon], 16);
                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lon]).addTo(map).bindPopup(this.value).openPopup();
                }
            });

            // Klik di map → update lat/lon + marker
            map.on("click", function (e) {
                const { lat, lng } = e.latlng;
                latInput.value = lat;
                lonInput.value = lng;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map).bindPopup("Lokasi dipilih").openPopup();
            });
        </script>    
    @endpush
</x-filament-panels::page>
