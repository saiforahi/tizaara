<template>
  <div class="weekly-market-wrapper our-brand-wrapper">
    <div class="container">
      <div class="grid-bg-pad">
        <div class="row">
          <h2 class="page-title">{{ $t("message.brands.products_premium_brands") }}</h2>
        </div>
        <div class="row">
          <div class="col-sm-2 mb-3" v-for="brand in getBrandListing" :key="brand.id">
            <div class="items-brand-wrapper">
              <a href=""><img v-lazy="showImage(brand.logo)" class="img-fluid" alt=""></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";

export default {
  name: "Brands",
  computed: {
    ...mapGetters(["getBrandListing"])
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
  }
}
</script>

<style scoped>

</style>