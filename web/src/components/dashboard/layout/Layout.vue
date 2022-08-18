<template>
  <div>
    <Sidebar/>
    <div id="right-panel" class="right-panel">
      <Header/>
      <router-view :key="$route.path"></router-view>
    </div>
  </div>
</template>

<script>
import Header from './inc/Header'
import Sidebar from './inc/Sidebar'
import Vue from 'vue'

import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'
Vue.use(Toaster, {timeout: 5000})
export default {
  name: "Layout",
  components: {
    Header, Sidebar
  },
  methods: {
    loadResize: () => {
      let windowWidth = $(window).width();
      if (windowWidth < 1010) {
        $('body').addClass('small-device');
      } else {
        $('body').removeClass('small-device');
      }
    },
    userStatus(val) {
      if (this.currentRouteName !== 'verify-admin' && val.status != 2) {
        this.$router.push({name: "verify-admin"});
      }
    }
  },
  created() {
    this.loadResize();
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    },
    currentRouteName() {
      return this.$route.name;
    }
  },
  watch: {
    user(val) {
      this.userStatus(val);
    }
  }
}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>

