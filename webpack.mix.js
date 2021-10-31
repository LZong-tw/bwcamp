const mix = require("laravel-mix");
const path = require("path");
const webpack = require("webpack");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css")
    .override((config) => {
        delete config.watchOptions;
        if (config.compilerOptions) {
            config.compilerOptions.whitespace;
        }
    })
    .webpackConfig({
        resolve: {
            alias: {
                "@": path.resolve(__dirname, "resources/sass"),
                vue: "@vue/compat",
            },
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: "vue-loader",
                    options: {
                        compilerOptions: {
                            compatConfig: {
                                MODE: 2,
                            },
                        },
                    },
                },
            ],
        },
        // Not working.
        // plugins: [
        //     new webpack.DefinePlugin({
        //         __VUE_PROD_DEVTOOLS__: true,
        //     }),
        // ],
    })
    .disableNotifications();