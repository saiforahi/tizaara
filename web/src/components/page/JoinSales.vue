<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="row">
        <h3>{{ $t("message.join_sales.join_our_sales_team") }}</h3>
        <div v-html="getJoinSales"></div>
      </div>
    </div>
  </div>
</template>

<script>
import {JOIN_SALES} from "@/core/services/store/module/page_manage";
import {mapGetters} from "vuex";

export default {
  name: "JoinSales",
  created() {
    this.$store.dispatch(JOIN_SALES)
  },
  computed: {
    ...mapGetters(["getJoinSales"]),
  },
}
</script>

<style scoped>

</style>