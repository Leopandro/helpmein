import { createApp } from "vue";
import { createPinia } from "pinia";
import { Tooltip } from "bootstrap";
import App from "./App.vue";
import mitt from 'mitt';
const emitter = mitt();
/*
TIP: To get started with clean router change path to @/router/clean.ts.
 */
import router from "./router";
import ElementPlus from "element-plus";
import i18n from "@/core/plugins/i18n";

//imports for app initialization
import ApiService from "@/core/services/ApiService";
import { initApexCharts } from "@/core/plugins/apexcharts";
import { initInlineSvg } from "@/core/plugins/inline-svg";
import { initVeeValidate } from "@/core/plugins/vee-validate";
import "@/core/plugins/prismjs";
import {vue3Debounce} from "vue-debounce";
const app = createApp(App);

app.use(i18n);
app.config.globalProperties.emitter = emitter;
app.use(createPinia());
app.use(router);
app.use(ElementPlus);

ApiService.init(app);
initApexCharts(app);
initInlineSvg(app);
initVeeValidate();

app.directive('debounce', vue3Debounce({ lock: true }))
app.directive("tooltip", (el) => {
  new Tooltip(el);
});

app.mount("#app");
