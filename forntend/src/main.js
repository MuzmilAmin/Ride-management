import "./assets/main.css";

import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "./App.vue";
import router from "./router";

/*
 ** third-party plugins
 */
import VueGoogleMaps from "@fawmi/vue-google-maps"; 

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyC5iXwzYkS1m8Ij0PjTqLwqH3wKjLxXpZ8",
    libraries: "places",
  },
});
app.mount("#app");
