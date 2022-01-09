const colors = require("vuetify/es5/util/colors");
require('dotenv').config()
const {join} = require('path')
const {copySync, removeSync} = require('fs-extra')

module.exports = {
  ssr: false,

  srcDir: __dirname,

  env: {
    apiUrl: process.env.API_URL || process.env.APP_URL + '/api',
    appName: process.env.APP_NAME || 'RabinVira',
    appLocale: process.env.APP_LOCALE || 'fa',
    githubAuth: !!process.env.GITHUB_CLIENT_ID
  },

  head: {
    // title: 'رابین ویرا همراه - گارانتی 6 ماهه محصولات بیسوس | رابین ویرا همراه',
    // titleTemplate: process.env.APP_NAME + ' - %s',
    // meta: [
    //   {charset: 'utf-8'},
    //   {name: 'viewport', content: 'width=device-width, initial-scale=1'},
    //   {
    //     hid: 'description',
    //     name: 'description',
    //     content: 'نمایندگی فروش محصولات باسئوس,گارانتی رابین ویرا همراه,گارانتی رسمی محصولات بیسوس در ایران,رابین ویرا ,خدمات پس از فروش باسئوس,رابین ویرا گارانتی 6 ماهه بیسوس'
    //   }
    // ],
    link: [
      {
        rel: 'apple-touch-icon',
        sizes: '180x180',
        href: 'https://admin.rabinvira.com/wp-content/uploads/2021/11/apple-touch-icon.png'
      },
      {
        rel: 'icon',
        type: 'image/png',
        sizes: '32x32',
        href: 'https://admin.rabinvira.com/wp-content/uploads/2021/11/favicon-32x32-1.png'
      },
      {
        rel: 'icon',
        type: 'image/png',
        sizes: '16x16',
        href: 'https://admin.rabinvira.com/wp-content/uploads/2021/11/favicon-16x16-1.png'
      },
      {name: 'msapplication-TileColor', content: '#2d89ef'},
      {name: 'theme-color', content: '#ffffff'},
    ]
  },

  loading: {color: '#007bff'},

  router: {
    middleware: ['locale', 'check-auth']
  },

  css: [
    {src: '~assets/sass/app.scss', lang: 'scss'}
  ],

  plugins: [
    '~components/global',
    '~plugins/i18n',
    // '~plugins/vform',
    '~plugins/axios',
    '~plugins/fontawesome',
    '~plugins/nuxt-client-init',
    // {src: '~plugins/bootstrap', mode: 'client'},
    '~plugins/mixin',
  ],

  modules: [
    '@nuxtjs/router',
    '@nuxtjs/vuetify',
    '@nuxtjs/axios',
    '@nuxtjs/sitemap',
  ],
  sitemap: {
    hostname: 'https://rabinvira.com',
    gzip: true,
    routes: [
      '/',
      '/blogs',
      '/contactus',
    ]
  },
  buildModules: [
    '@nuxtjs/google-analytics'
  ],
  googleAnalytics: {
    id: 'G-XYRP9SG60J'
  },
  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // baseURL: process.env.BASE_URL
    baseURL: process.env.BASE_URL
  },


  vuetify: {
    rtl: true,
    customVariables: ['~/assets/sass/variables.scss'],
    theme: {
      options: {customProperties: true},
      dark: false,
      themes: {
        dark: {
          primary: "#1976d2",
          accent: "#2b2a2a",
          secondary: "#82b1ff",
          // primary: "#61892f",
          // accent: "#222629",
          // secondary: "#86c232",
          third: "#474b4f",
          fourth: "#6b6e70"
          // info: colors.teal.lighten1,
          // warning: colors.amber.base,
          // error: colors.deepOrange.accent4,
          // success: colors.green.accent3,
        },
        light: {
          primary: "#1976d2",
          accent: "#2b2a2a",
          secondary: "#82b1ff",
          // info: colors.teal.lighten1,
          // warning: colors.amber.base,
          // error: colors.deepOrange.accent4,
          // success: colors.green.accent3,
        }
      }
    }
  },

  build: {
    extractCSS: true
  },

  hooks: {
    generate: {
      done(generator) {
        // Copy dist files to public/_nuxt
        if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === 'spa') {
          const publicDir = join(generator.nuxt.options.rootDir, 'public', '_nuxt')
          removeSync(publicDir)
          copySync(join(generator.nuxt.options.generate.dir, '_nuxt'), publicDir)
          copySync(join(generator.nuxt.options.generate.dir, '200.html'), join(publicDir, 'index.html'))
          removeSync(generator.nuxt.options.generate.dir)
        }
      }
    }
  }
}
