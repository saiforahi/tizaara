const path = require("path");

module.exports = {
  //publicPath: 'https://cdn.jsdelivr.net/gh/rofequl/cdn/tizaara/frontend/',
  lintOnSave: false,
  configureWebpack: {
    resolve: {
      alias: {
        vue$: "vue/dist/vue.runtime.esm.js",
      },
    },
  },
  // devServer: {
  //   open: process.platform === 'darwin',
  //   host: '0.0.0.0',
  //   port: 8080,
  //   https: true,
  //   hotOnly: false,
  // },
  css: {
    loaderOptions: {
      postcss: {
        config: {
          path: __dirname,
        },
      },
    },
  },
};
