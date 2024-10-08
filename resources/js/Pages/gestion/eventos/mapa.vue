<template>
    <div ref="mapContainer" style="width: 100%; height: 500px"></div>
    <div>
        <Button class="mb-5 mt-5" type="button" @click="obtenerDireccion">
            Ubicación Actual
        </Button>
    </div>
</template>

<script>
import { onMounted, ref } from "vue";
import L from "leaflet";

export default {
    emits: ["obtenerDatos"],
    props: ["latitud", "longitud"],
    setup(props, { emit }) {
        const previousLat = ref(-16.5393727);
        const previousLng = ref(-68.066687);
        const lat = ref(
            props.latitud !== undefined ? props.latitud : previousLat.value
        );
        const lng = ref(
            props.longitud !== undefined ? props.longitud : previousLng.value
        );
        const map = ref();
        const mapContainer = ref();
        let marker = null;
        let circle = null;
        let marcadorMovido = false;

        const customIcon = L.icon({
            iconUrl: `${import.meta.env.VITE_APP_BASE_URL}/marker-icon.png`,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        });

        onMounted(() => {
            map.value = L.map(mapContainer.value).setView(
                [lat.value, lng.value],
                18
            );
            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 20,
                attribution:
                    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(map.value);

            agregarMarcadorInicial();

            // Manejar clic en el mapa
            map.value.on("click", (e) => {
                lat.value = e.latlng.lat;
                lng.value = e.latlng.lng;
                actualizarMarcador();
                enviarDatos({ lat: lat.value, lng: lng.value });
            });
        });

        function agregarMarcadorInicial() {
            if (marker) {
                marker.remove();
            }

            if (circle) {
                circle.remove();
            }

            marker = L.marker([lat.value, lng.value], {
                draggable: true,
                icon: customIcon,
            })
                .addTo(map.value)
                .on("dragend", (event) => {
                    marcadorMovido = true; // Marcador movido
                    const { lat: newLat, lng: newLng } =
                        event.target.getLatLng();
                    lat.value = newLat;
                    lng.value = newLng;
                    updateCirclePosition();
                    enviarDatos({ lat: lat.value, lng: lng.value });
                });

            circle = L.circle([lat.value, lng.value], {
                color: "blue",
                fillColor: "blue",
                fillOpacity: 0.2,
                radius: 50,
            }).addTo(map.value);
        }

        function actualizarMarcador() {
            if (marker) {
                marker.setLatLng([lat.value, lng.value]);
                updateCirclePosition();
            }
        }

        function updateCirclePosition() {
            if (circle) {
                circle.setLatLng([lat.value, lng.value]);
            }
        }

        function enviarDatos(data) {
            emit("obtenerDatos", data);
        }

        function obtenerDireccion() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition((position) => {
                    lat.value = position.coords.latitude;
                    lng.value = position.coords.longitude;
                    map.value.setView([lat.value, lng.value], 17);
                    enviarDatos({ lat: lat.value, lng: lng.value });

                    if (!marcadorMovido) {
                        agregarMarcadorInicial();
                    }
                });
            }
        }

        return {
            obtenerDireccion,
            lat,
            lng,
            mapContainer,
        };
    },
};
</script>
