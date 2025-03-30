import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import App from "./App.vue";
import UploadCsv from "./pages/UploadCsv.vue";

const routes = [{ path: "/", component: UploadCsv }];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(router);
app.mount("#app");