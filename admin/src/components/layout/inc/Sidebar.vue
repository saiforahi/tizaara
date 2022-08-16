<template>
  <CSidebar
      fixed
      :minimize="minimize"
      :show="show"
      @update:show="(value) => $store.commit('set', ['sidebarShow', value])"
  >
    <CSidebarBrand class="d-md-down-none" to="/">
      <img v-lazy="showImage($store.getters.generalList.admin_logo)" height="35" class="c-sidebar-brand-full">

      <img v-lazy="showImage($store.getters.generalList.favicon)" height="35" class="c-sidebar-brand-minimized">
    </CSidebarBrand>

    <CRenderFunction flat :content-to-render="$options.nav"/>
    <CSidebarMinimizer
        class="d-md-down-none"
        @click.native="$store.commit('set', ['sidebarMinimize', !minimize])"
    />
  </CSidebar>
</template>

<script>
import nav from './_nav'
import {api_base_url} from "@/core/config/app";

export default {
  name: 'TheSidebar',
  nav,
  computed: {
    show() {
      return this.$store.state.sidebarShow
    },
    minimize() {
      return this.$store.state.sidebarMinimize
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    }
  }
}
</script>
