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
import utcampUnitAndBatchSection from "./components/utcamp/UtcampUnitAndBatchSection.vue";
import utcampTitle from "./components/utcamp/UtcampTitle.vue";
import utcampIsBlisswisdom from "./components/utcamp/UtcampIsBlisswisdom.vue";

window.onload = () => {
    // if(document.getElementById("is-educating-section"))
    if ($("#is-educating-section").length) {
        const is_educating = Vue.createApp({
            components: { 
                isEducatingSection,
            },
        });
        is_educating.mount("#is-educating-section");
    }
    // if(document.getElementById("utcamp-unit-and-batch-section"))
    if ($("#utcamp-unit-and-batch-section").length) {
        const utcamp = Vue.createApp({
            components: { 
                utcampUnitAndBatchSection,
            },
        });
        utcamp.mount("#utcamp-unit-and-batch-section");
    }

    if ($("#utcamp-title").length) {
        const utcTitle = Vue.createApp({
            components: { 
                utcampTitle,
            },
        });
        utcTitle.mount("#utcamp-title");
    }

    if($("#utcamp-is-blisswisdom").length) {
        const utcIsBlisswisdom = Vue.createApp({
            components: {
                utcampIsBlisswisdom,
            },
        });
        utcIsBlisswisdom.mount("#utcamp-is-blisswisdom");
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
