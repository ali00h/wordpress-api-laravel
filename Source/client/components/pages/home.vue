<template>
  <v-card class="transparent elevation-0 overflow-hidden" width="100%" height="100vh" max-height="100vh">

  </v-card>
</template>

<script>
import FillingBtn from "../component/fillingBtn";
import {mapGetters} from 'vuex'

export default {
  name: "home",
  components: {FillingBtn},

  data: () => ({
    loading: false,
    info: null,
  }),

  created() {
    this.fetch();
  },

  computed: {
    req_page: function () {
      return this.page = this.$store.state.localstorage.page + '-' + this.$store.state.lang.locale;
    }
  },

  methods: {
    fetch() {
      this.loading = true;

    }
  }
}
</script>

<style scoped>
.color-block {
  background-color: var(--v-primary-base);
  position: fixed;
  height: 200%;
  width: 100%;
  transform: rotate(-15deg);
  left: -83%;
  top: -50%;
}

.theme--dark .v-avatar {
  border: 4px solid #252525;
}

.theme--light .v-avatar {
  border: 4px solid #eee;
}
</style>
