import { createApp } from "vue";
import { createHead } from "@unhead/vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
const app = createApp(App);
const head = createHead();

// bootstrap
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

// modals
import "./apps/admin/assets/sass/components/custom-modal.scss";

// perfect scrollbar
import PerfectScrollbar from "vue3-perfect-scrollbar";
import "vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css";

//Sweetalert
import Swal from "sweetalert2";
window.Swal = Swal;

// nouislider - later remove and add to page due to not working in page
import VueNouislider from "vue3-nouislider";
import "vue3-nouislider/dist/vue3-nouislider.css";

// vue input mask
import Maska from "maska";

// smooth scroll
import { registerScrollSpy } from "vue3-scroll-spy/dist/index";
registerScrollSpy(app, { offset: 118 });

//vue-i18n
import i18n from "./i18n";

// datatables
import { ClientTable } from "v-tables-3";

// json to excel
import vue3JsonExcel from "vue3-json-excel";

//vue-wizard
import VueFormWizard from "vue3-form-wizard";
import "vue3-form-wizard/dist/style.css";

// set default settings
import appSetting from "./app-setting";
window.$appSetting = appSetting;
window.$appSetting.init();

//markdown editor
import VueEasymde from "vue3-easymde";
import "easymde/dist/easymde.min.css";

app.use(store)
    .use(router)
    .use(i18n)
    .use(PerfectScrollbar)
    .use(VueNouislider)
    .use(Maska)
    .use(ClientTable)
    .use(vue3JsonExcel)
    .use(VueFormWizard)
    .use(head)
    .use(VueEasymde)
    .mount("#app");
