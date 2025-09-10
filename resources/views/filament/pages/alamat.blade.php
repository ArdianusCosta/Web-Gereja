<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Alamat & Maps</h2>

        <!-- Grid utama -->
        <div class="grid grid-cols-2 gap-4">

            <!-- Kategori Pencarian -->
            <div>
                <label for="searchType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Kategori Pencarian
                </label>
                <select id="searchType"
                    class="filament-forms-input mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                    <option value="lokasi">Lokasi</option>
                    <option value="koordinat">Titik Koordinat</option>
                </select>
            </div>

            <!-- Ganti Map View -->
            <div>
                <label for="mapStyle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Ganti Map View
                </label>
                <select id="mapStyle"
                    class="filament-forms-input mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                    <option value="https://tiles.openfreemap.org/styles/liberty">OSM</option>
                    <option value="https://basemaps.cartocdn.com/gl/positron-gl-style/style.json">Carto Light</option>
                    <option value="https://basemaps.cartocdn.com/gl/dark-matter-gl-style/style.json">Carto Dark</option>
                    <option value="https://demotiles.maplibre.org/style.json">Satellite</option>
                </select>
            </div>

            <!-- Input Lokasi -->
            <div id="lokasiInputWrapper" class="col-span-2">
                <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Pencarian Lokasi
                </label>
                <div class="relative mt-1">
                    <input type="text" id="alamat" placeholder="cari kota dan lokasi"
                        class="filament-forms-input w-full rounded-lg border-gray-300 dark:border-gray-600 
                               bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 
                               focus:border-primary-500 focus:ring-primary-500" />
                    <!-- Dropdown suggestion -->
                    <ul id="suggestions"
                        class="absolute z-50 w-full mt-1 rounded-lg bg-white dark:bg-gray-800 
                               border border-gray-300 dark:border-gray-600 
                               text-gray-800 dark:text-gray-200 hidden max-h-48 overflow-y-auto shadow-lg">
                    </ul>
                </div>
            </div>

            <!-- Latitude -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                <input type="text" id="latitude" 
                    class="filament-forms-input mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                        bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200" 
                    readonly />
            </div>

            <!-- Longitude -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                <input type="text" id="longitude" 
                    class="filament-forms-input mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                        bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200" 
                    readonly />
            </div>

            <!-- Map -->
            <div class="col-span-2">
                <div id="map" class="w-full h-96 rounded-lg border border-gray-300 dark:border-gray-600"></div>
            </div>
        </div>
    </div>

    @once
        @push('styles')
            <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
        @endpush

        @push('scripts')
            <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Inisialisasi Map
                    let map = new maplibregl.Map({
                        container: 'map',
                        style: 'https://tiles.openfreemap.org/styles/liberty',
                        center: [110.414, -7.005],
                        zoom: 11
                    });

                    map.addControl(new maplibregl.NavigationControl());

                    let marker = new maplibregl.Marker({ draggable: true })
                        .setLngLat([110.414, -7.005])
                        .addTo(map);

                    // Update lat lon
                    function updateLatLon(lat, lon) {
                        document.getElementById("latitude").value = lat;
                        document.getElementById("longitude").value = lon;
                    }
                    updateLatLon(-7.005, 110.414);

                    // Drag marker
                    marker.on('dragend', function () {
                        const lngLat = marker.getLngLat();
                        updateLatLon(lngLat.lat, lngLat.lng);
                    });

                    // Klik map
                    map.on("click", function (e) {
                        let lat = e.lngLat.lat;
                        let lon = e.lngLat.lng;
                        marker.setLngLat([lon, lat]);
                        updateLatLon(lat, lon);
                    });

                    // Autocomplete lokasi
                    const searchType = document.getElementById("searchType");
                    const searchInput = document.getElementById("alamat");
                    const suggestionsBox = document.getElementById("suggestions");
                    const lokasiWrapper = document.getElementById("lokasiInputWrapper");
                    let typingTimer;

                    searchInput.addEventListener("input", function () {
                        clearTimeout(typingTimer);
                        let query = this.value;
                        if (query.length < 3) {
                            suggestionsBox.innerHTML = "";
                            suggestionsBox.classList.add("hidden");
                            return;
                        }

                        typingTimer = setTimeout(async () => {
                            if (searchType.value === "lokasi") {
                                let response = await fetch(
                                    `https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=id&addressdetails=1&limit=5`
                                );
                                let data = await response.json();

                                suggestionsBox.innerHTML = "";
                                if (data.length > 0) {
                                    data.forEach(item => {
                                        let li = document.createElement("li");
                                        li.textContent = item.display_name;
                                        li.className = "px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700";
                                        li.addEventListener("click", function () {
                                            searchInput.value = item.display_name;
                                            let lat = parseFloat(item.lat);
                                            let lon = parseFloat(item.lon);

                                            map.flyTo({ center: [lon, lat], zoom: 13 });
                                            marker.setLngLat([lon, lat]);
                                            updateLatLon(lat, lon);

                                            suggestionsBox.classList.add("hidden");
                                        });
                                        suggestionsBox.appendChild(li);
                                    });
                                    suggestionsBox.classList.remove("hidden");
                                } else {
                                    suggestionsBox.classList.add("hidden");
                                }
                            }
                        }, 400);
                    });

                    // Tutup suggestion jika klik luar
                    document.addEventListener("click", function (e) {
                        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                            suggestionsBox.classList.add("hidden");
                        }
                    });

                    // Ganti style peta
                    document.getElementById("mapStyle").addEventListener("change", function () {
                        map.setStyle(this.value);
                    });

                    // Switch mode koordinat
                    searchType.addEventListener("change", function () {
                        searchInput.value = "";
                        suggestionsBox.innerHTML = "";
                        suggestionsBox.classList.add("hidden");

                        const latInput = document.getElementById("latitude");
                        const lonInput = document.getElementById("longitude");

                        if (this.value === "koordinat") {
                            lokasiWrapper.classList.add("hidden");
                            searchInput.placeholder = "contoh: -7.005, 110.414";

                            // latitude & longitude jadi bisa diketik
                            latInput.removeAttribute("readonly");
                            lonInput.removeAttribute("readonly");

                            // kalau user ketik manual koordinat
                            [latInput, lonInput].forEach(input => {
                                input.addEventListener("change", function () {
                                    let lat = parseFloat(latInput.value.trim());
                                    let lon = parseFloat(lonInput.value.trim());
                                    if (!isNaN(lat) && !isNaN(lon)) {
                                        map.flyTo({ center: [lon, lat], zoom: 13 });
                                        marker.setLngLat([lon, lat]);
                                        updateLatLon(lat, lon);
                                    }
                                });
                            });

                        } else {
                            lokasiWrapper.classList.remove("hidden");
                            searchInput.placeholder = "cari kota dan lokasi";

                            // latitude & longitude balik jadi readonly
                            latInput.setAttribute("readonly", true);
                            lonInput.setAttribute("readonly", true);
                        }
                    });
                });
            </script>
        @endpush
    @endonce
</x-filament-panels::page>
