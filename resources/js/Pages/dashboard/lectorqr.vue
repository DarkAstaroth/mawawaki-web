<template>
    <div>
        <p class="error" v-if="noFrontCamera">
            Parece que no tienes una cámara frontal en tu dispositivo
        </p>

        <p class="error" v-if="noRearCamera">
            Parece que no tienes una cámara trasera en tu dispositivo
        </p>

        <qrcode-stream
            :paused="paused"
            @detect="onDetect"
            @camera-on="onCameraOn"
            @camera-off="onCameraOff"
            @error="onError"
        >
            <div v-show="showScanConfirmation" class="scan-confirmation">
                <div class="d-flex align-items-center text-success">
                    <Icon icon="lets-icons:check-fill" class="display-1" />
                </div>
            </div>
        </qrcode-stream>
    </div>
</template>

<script>
import { QrcodeStream } from "vue-qrcode-reader";
import { ref } from "vue";
import { useDataAsistencias } from "@/store/dataAsistencias";

export default {
    emits: ["codigoQR"],
    name: "LectorQR",
    components: { QrcodeStream },
    data() {
        return {
            facingMode: "environment",
            noRearCamera: false,
            noFrontCamera: false,
            paused: false,
            result: "",
            showScanConfirmation: false,
            codigoEscaneado: false,
        };
    },
    setup(props, { emit }) {
        const store = useDataAsistencias();
        const result = ref("");
        return { result, store, emit };
    },
    methods: {
        onCameraOn() {
            this.showScanConfirmation = false;
        },

        onCameraOff() {
            this.showScanConfirmation = true;
        },
        switchCamera() {
            switch (this.facingMode) {
                case "environment":
                    this.facingMode = "user";
                    break;
                case "user":
                    this.facingMode = "environment";
                    break;
            }
        },

        onError(error) {
            const triedFrontCamera = this.facingMode === "user";
            const triedRearCamera = this.facingMode === "environment";

            const cameraMissingError = error.name === "OverconstrainedError";

            if (triedRearCamera && cameraMissingError) {
                this.noRearCamera = true;
            }

            if (triedFrontCamera && cameraMissingError) {
                this.noFrontCamera = true;
            }

            console.error(error);
        },

        async onDetect(detectedCodes) {
            this.result = JSON.stringify(
                detectedCodes.map((code) => code.rawValue)
            );
            this.codigoEscaneado = JSON.parse(this.result);
            this.emit("codigoQR", this.codigoEscaneado);

            this.paused = true;
            await this.timeout(500);
            this.paused = false;
            this.codigoEscaneado = null;
            this.result = null;
        },
        timeout(ms) {
            return new Promise((resolve) => {
                window.setTimeout(resolve, ms);
            });
        },
    },
};
</script>

<style scoped>
button {
    position: absolute;
    left: 10px;
    top: 10px;
}
button img {
    width: 50px;
    height: 50px;
}
.error {
    color: red;
    font-weight: bold;
}
.scan-confirmation {
    position: absolute;
    width: 100%;
    height: 100%;

    background-color: rgba(255, 255, 255, 0.8);

    display: flex;
    flex-flow: row nowrap;
    justify-content: center;
}
</style>
