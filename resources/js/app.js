import { Ziggy } from "@/ziggy";
import route from "ziggy-js";
import { createApp } from "vue/dist/vue.esm-bundler";
import { createPinia } from "pinia";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import PrimeVue from "primevue/config";

import "primevue/resources/themes/lara-light-teal/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "@flaticon/flaticon-uicons/css/all/all.css";

import componentesApp from "./componentes";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import Toast from "primevue/toast";
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";
import DialogService from "primevue/dialogservice";
import InputSwitch from "primevue/inputswitch";
import Calendar from "primevue/calendar";
import Password from "primevue/password";
import Badge from "primevue/badge";
import BadgeDirective from "primevue/badgedirective";
import Avatar from "primevue/avatar";
import AvatarGroup from "primevue/avatargroup";
import Message from "primevue/message";
import RadioButton from "primevue/radiobutton";
import MultiSelect from "primevue/multiselect";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import FileUpload from "primevue/fileupload";
import Menu from "primevue/menu";
import ProgressSpinner from "primevue/progressspinner";
import Card from "primevue/card";
import { Icon } from "@iconify/vue";
import ConfirmPopup from "primevue/confirmpopup";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup";
import Row from "primevue/row";
import Tooltip from "primevue/tooltip";
import router from "@/index";


const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
export const appComponents = createApp({
    components: {
        ...componentesApp,
    },
})
    .use(router)
    .use(pinia)
    .use(Ziggy)
    .use(LaravelPermissionToVueJS)
    .use(PrimeVue, { ripple: true })
    .use(ConfirmationService)
    .use(ToastService)
    .use(DialogService)
    .mixin({ methods: { route } })
    .component("VueDatePicker", VueDatePicker)
    .component("Dialog", Dialog)
    .component("InputText", InputText)
    .component("Password", Password)
    .component("Textarea", Textarea)
    .component("Dropdown", Dropdown)
    .component("Toast", Toast)
    .component("Button", Button)
    .component("InputSwitch", InputSwitch)
    .component("Calendar", Calendar)
    .component("Badge", Badge)
    .component("AvatarGroup", AvatarGroup)
    .component("Avatar", Avatar)
    .component("Message", Message)
    .component("RadioButton", RadioButton)
    .component("MultiSelect", MultiSelect)
    .component("TabView", TabView)
    .component("TabPanel", TabPanel)
    .component("FileUpload", FileUpload)
    .component("Menu", Menu)
    .component("ProgressSpinner", ProgressSpinner)
    .component("Card", Card)
    .component("Icon", Icon)
    .component("ConfirmPopup", ConfirmPopup)
    .component("DataTable", DataTable)
    .component("Column", Column)
    .component("ColumnGroup", ColumnGroup)
    .component("Row", Row)
    .directive("tooltip", Tooltip)
    .directive("badge", BadgeDirective)

    .mount("#app");
