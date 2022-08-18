<template>
  <div>
    <div class="weekly-market-wrapper">
      <div class="container">
        <div class="grid-bg-pad">
          <div class="row">
            <h2 class="page-title"><a href="javascript:void(0);">{{
                $t("message.home.find_supplier_from_top_city")
              }} </a></h2>
          </div>
          <div class="row">
            <div v-for="(city, i) in top_cities" class="col-sm-2" :key="i">
              <div class="card-wrapper" @click="productList(city.id)">
                <div class="card" style="cursor: pointer">
                  <img class="card-img-top img-fluid zoom-in" v-lazy="showImage('city_img.png')"
                       alt="Card image cap">
                  <div class="card-body">
                    <p class="card-text price">{{ city.name }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {FLASH_DEALS_LIST} from "@/core/services/store/module/flash_deals";
import {api_base_url} from "@/core/config/app";
import Timer from "@/components/helper/Timer";

export default {
  name: "FindTopCity",
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    productList(city) {
      this.$router.push({name: 'top.city.product', params: {top_city_id: city}});
    }
  },
  computed: {
    ...mapGetters(["top_cities"])
  },
}
</script>

<style scoped>

</style>
