<template>
  <v-card width="100%" height="100vh" color="transparent" v-if="!loading"
          :style="!isMobile ? 'padding-right: 120px' : ''">


  </v-card>
</template>
<script>

export default {

  data: () => ({
    page: 'home',
    locale: null,
    loading: false,
    isMobile: false,
  }),

  created() {
    this.onResize();
    window.addEventListener('resize', this.onResize);
    this.locale = this.$store.state.lang.locale;
  },

  watch: {
    page: function (val) {
      this.$store.commit('localstorage/updatePage', val);
    },
    isMobile: function () {
      this.expanded = false;
      this.$nextTick(() => {
        this.expanded = true;
      })
    }
  },

  methods: {
    onResize() {
      this.isMobile = window.innerWidth < 960;
      console.log('resizing: ', window.innerWidth)
    },
  },

  beforeDestroy() {
    window.removeEventListener('resize', this.onResize);
  }

}
</script>
