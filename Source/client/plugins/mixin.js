import Vue from 'vue'
import {loadMessages} from "./i18n";

Vue.mixin({
  data: () => ({}),

  methods: {
    getCookie(cname, def = "") {
      let name = cname + "=";
      let decodedCookie = decodeURIComponent(document.cookie);
      let ca = decodedCookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
          return c.substring(name.length, c.length);
        }
      }
      return def;
    },

    goBack() {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    },

    goto(route) {
      this.$router.push(route);
    },

    setCookie(cname, cvalue, exdays) {
      let d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      let expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    },

    toggleDarkMode() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
      this.setCookie('theme', this.$vuetify.theme.dark, '60');
    },

    toggleLocale(locale) {
      if (this.$i18n.locale !== locale) {
        loadMessages(locale)

        this.$store.dispatch('lang/setLocale', {locale});
        this.$root.$emit('locale-change', this.$route.name);
        // this.$vuetify.rtl = this.$i18n.locale === 'fa';
      }
    },

    objectLength(obj) {
      try {
        return Object.keys(obj).length;
      } catch (e) {
        console.log(e)
      }
    },
  }
})
