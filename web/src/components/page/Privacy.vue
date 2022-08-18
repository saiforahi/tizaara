<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="row">
        <h3>{{ $t("message.privacy.privacy_policy")}}</h3>
        <div v-html="getPrivacyPolicy"></div>
      </div>
    </div>
  </div>
</template>

<script>
import {PRIVACY_POLICY} from "@/core/services/store/module/page_manage";
import {mapGetters} from "vuex";

export default {
name: "Privacy",
  created() {
    this.$store.dispatch(PRIVACY_POLICY)
  },
  computed: {
    ...mapGetters(["getPrivacyPolicy"]),
  },
}
</script>

<style scoped>

</style>