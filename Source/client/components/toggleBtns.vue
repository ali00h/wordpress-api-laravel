<template>
  <v-card color="transparent" elevation="0" class="toggle-btn-wrapper rounded" v-click-outside="onClickOutside">

    <v-slide-x-reverse-transition>
      <v-btn v-show="model[0]" fab depressed class="mx-1" @click="toggleDarkMode" width="50" height="50">
        <v-icon>mdi-lightbulb-variant{{ $vuetify.theme.dark ? '' : '-outline' }}</v-icon>
      </v-btn>
    </v-slide-x-reverse-transition>

    <transition name="slide-x-reverse-transition" mode="out-in">
      <v-btn v-if="model[1] && !toggle_lang" fab class="mx-1" @click="toggle_lang = true" depressed width="50"
             height="50">
        {{ locales[locale] }}
      </v-btn>

      <v-btn-toggle v-else-if="toggle_lang" class="mx-1 rounded-circle">
        <v-btn v-for="(value, key) in locales" :key="key" @click="setLocale(key)" width="50" height="50">
          {{ value }}
        </v-btn>
      </v-btn-toggle>
    </transition>

  </v-card>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
  name: "toggleBtns",

  data: () => ({
    model: {
      0: false,
      1: false,
    },
    timeFunc: undefined,
    index: 0,
    toggle_lang: false,
  }),

  created() {
    this.$nextTick(() => {
      this.setTimer();
    })
  },

  computed: mapGetters({
    locale: 'lang/locale',
    locales: 'lang/locales'
  }),

  methods: {
    setTimer() {
      let countdown = 0;
      this.timeFunc = setInterval(() => {

        countdown += 100;

        if (countdown >= 50) {
          this.model[this.index] = true;
          this.index += 1;
          if (this.index >= 2) {
            this.clearTime();
          }
        }
      }, 150);
    },

    setLocale(locale) {
      this.toggle_lang = false;
      this.toggleLocale(locale);
    },

    clearTime() {
      clearInterval(this.timeFunc);
      this.timeFunc = undefined;
    },

    onClickOutside() {
      if (this.toggle_lang) this.toggle_lang = false;
    },
  },

  beforeDestroy() {
    this.clearTime();
  }
}
</script>

<style scoped>
.toggle-btn-wrapper {
  position: fixed;
  top: 20px;
  right: 30px;
  z-index: 99;
}
</style>
