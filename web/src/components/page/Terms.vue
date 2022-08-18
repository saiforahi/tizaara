<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="row">
        <h3>{{ $t("message.terms.terms_conditions")}}</h3>
        <div v-html="getTermCondition"></div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {TERM_CONDITIONS} from "@/core/services/store/module/page_manage";


export default {
  name: "Terms",
  created() {
    this.$store.dispatch(TERM_CONDITIONS)
  },
  computed: {
    ...mapGetters(["getTermCondition"]),
  },
}
</script>

<style scoped>

</style>