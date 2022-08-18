<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="row">
        <h3>{{ $t("message.about.about_us") }}</h3>
        <div v-html="getAboutUs"></div>
      </div>
    </div>
  </div>
</template>

<script>
import {ABOUT_US} from "@/core/services/store/module/page_manage";
import {mapGetters} from "vuex";

export default {
  name: "About",
  created() {
    this.$store.dispatch(ABOUT_US)
  },
  computed: {
    ...mapGetters(["getAboutUs"]),
  },
}
</script>

<style scoped>

</style>