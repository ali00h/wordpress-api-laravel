<template>
  <!--  <div class="layout">-->
  <!--    <navbar />-->

  <!--    <div class="container mt-4">-->
  <!--      <nuxt />-->
  <!--    </div>-->
  <!--  </div>-->

  <v-app dark>

    <v-main>
      <v-container fluid class="pl-0 py-0"
                   :style="`padding-right: ${isMobile ? '0' : '120px'};`">

        <!--    <bigger-cursor-fragment/>-->

        <custom-cursor/>

        <toggle-btns/>

        <transition name="overlay" origin="center">
          <div class="transition-overlay" v-if="loading"></div>
        </transition>

        <transition name="pages">
          <Nuxt v-if="!loading"/>
        </transition>

        <navigation-menu-fragment :is-mobile="isMobile"/>

      </v-container>
    </v-main>

  </v-app>
</template>

<script>
// import Navbar from '~/components/Navbar'

import ToggleBtns from "../components/toggleBtns";
import NavigationMenuFragment from "../components/fragments/navigationMenuFragment";
import CustomCursor from "../components/fragments/customCursor";


export default {
  components: {CustomCursor, NavigationMenuFragment, ToggleBtns},
  // components: {
  //   Navbar
  // },

  transitions: 'pages',

  head() {
    return {
      title: this.title,
    }
  },

  data: () => ({
    page: '',
    locale: null,
    loading: false,
    isMobile: false,
  }),

  created() {
    this.onResize();
    window.addEventListener('resize', this.onResize);
    this.$root.$on('locale-change', this.changed);
    this.locale = this.$store.state.lang.locale;
    try {
      this.toggleLocale(this.getCookie("locale", "fa"));
      setTimeout(() => {
        this.$vuetify.theme.dark = this.getCookie('theme', 'true') === 'true';
      }, 100)
    } catch (e) {
      console.log(e);
    }

    this.$router.afterEach((to, from) => {
    if (to.name !== from.name)
      this.changed(to.name);
    })

    this.$nextTick(() => {
      setTimeout(() => {
    this.changed(this.$route.name);
    }, 100);
      console.log('default title name: ', this.$route.name);
    })
    // this.$vuetify.rtl = this.$i18n.locale === 'fa';
  },

  watch: {
    page: function (val) {
      this.$store.commit('localstorage/updatePage', val);
    },
    isMobile: function () {
    }
  },

  methods: {
    onResize() {
      this.isMobile = window.innerWidth < 960;
      // console.log('resizing: ', window.innerWidth)
    },

    changed(name) {
      this.loading = true;
      // this.title = this.$t('appName') + ' - ' + this.$t(`headTitle.${name}`);
      // document.title = this.$t('appName') + ' - ' + this.$t(`headTitle.${name}`);
      this.$nextTick(() => {
        setTimeout(() => {
          this.loading = false;
        }, 200)
      })
    }
  },

  beforeDestroy() {
    window.removeEventListener('resize', this.onResize);
  }
}
</script>

<style>
.pages-enter-active, .pages-leave-active {
  transition: opacity .5s, transform 1s;
  transform: translateX(0);
}

.pages-enter, .pages-leave-active {
  opacity: 0;
  transform: translateX(-30%);
}

.overlay-active, .overlay-leave-active {
  transition: transform 1s;
  transform: translateX(50%);
}

.overlay-enter, .overlay-leave-active {
  transform: translateX(-100%);
}

.transition-overlay {
  left: 0;
  right: 0;
  display: block;
  width: 100vw;
  height: 100vh;
  position: absolute;
  z-index: 20;
  background-color: rgba(34, 34, 34, 1);
}

.theme--light .transition-overlay {
  background-color: rgba(238, 238, 238, 1);
}
</style>
