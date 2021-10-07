/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";

window.Vue = require("vue").default;
Vue.config.compilerOptions.whitespace = "condense";

if (process.env.NODE_ENV == "development") {
    Vue.__VUE_PROD_DEVTOOLS__ = true;
}
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context("./", true, /\.vue$/i);
// files
//     .keys()
//     .map((key) =>
//         Vue.component(key.split("/").pop().split(".")[0], files(key).default)
//     );

// Vue2
// Vue.component(
//     "is-educatinng-section",
//     require("./components/IsEducatingSection.vue").default
// );

import isEducatingSection from "./components/tcamp/IsEducatingSection.vue";
import notEducatingSection from "./components/tcamp/NotEducatingSection.vue";

const is_educating = Vue.createApp({
    components: { 
        isEducatingSection,
        notEducatingSection,
    },
});

window.onload = () => {
    if ("#is-educating-section") {
        is_educating.mount("#is-educating-section");
    }
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });
