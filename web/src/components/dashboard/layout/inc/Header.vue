<template>
  <header id="header" class="header">
    <div class="top-left">
      <div class="navbar-header">
        <router-link class="navbar-brand" to="/">
          <img v-lazy="showImage($store.getters.generalList.logo)" height="44px" alt="Logo">
        </router-link>
        <router-link class="navbar-brand hidden" to="/">
          <img v-lazy="showImage($store.getters.generalList.logo)" alt="Logo">
        </router-link>
        <a @click="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
      </div>
    </div>
    <div class="top-right">
      <div class="header-menu">

        <div class="user-area dropdown float-right">
          <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="user-avatar rounded-circle" v-lazy="user.photo_type == 0? showImage(user.photo):user.photo"
                 alt="User Avatar">
          </a>
          <div class="user-menu dropdown-menu">
            <a class="nav-link" @click.prevent="onLogout" href="javascript:void(0)">
              <i class="fa fa-power -off"></i>{{ $t("message.header.sign_out") }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </header>
</template>

<script>
import {api_base_url} from "@/core/config/app";
import {LOGOUT} from "@/core/services/store/auth.module";

export default {
  name: "Header",
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    menuToggle: () => {
      let windowWidth = $(window).width();
      if (windowWidth < 1010) {
        $('body').removeClass('open');
        if (windowWidth < 760) {
          $('#left-panel').slideToggle();
        } else {
          $('#left-panel').toggleClass('open-menu');
        }
      } else {
        $('body').toggleClass('open');
        $('#left-panel').removeClass('open-menu');
      }
    },
    onLogout() {
      this.$store.dispatch(LOGOUT)
      this.$router.push({name: "home"});
    },
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    },
  },
  created() {
    Fire.$on('menuToggle', () => {
      this.menuToggle();
    });
  }
}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>
