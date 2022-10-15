<template>
  <div>
    <div v-for="(flash, k) in flash_dealList" class="weekly-market-wrapper" :key="k">
      <div class="container">
        <div class="row">
          <div class="col-md-11">
            <h2 class="page-title"><a href="javascript:void(0);">{{ flash.title }}</a></h2>
          </div>
          <div class="col-md-1" align="center">
            <router-link :to="'flash-deal/'+flash.slug" class="btn btn-sm btn-outline-dark btn-more" style="font-size:12px; font-weight: bold;">View all</router-link>
            <!-- <button class="btn btn-sm btn-outline-dark btn-more" style="font-size:12px; font-weight: bold;">View all</button> -->
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 mb-2">
            <div class="custom-feature-grid weekly-deals rounded" style="min-height: 100%;">
              <p>{{ $t("message.deal.deals_end_in") }}</p>
              <Timer
                  :starttime="new Date().getTime()"
                  :endtime="flash.end_date"
                  trans='{"status": {}}'></Timer>
              <router-link :to="'flash-deal/'+flash.slug" class="btn-more">{{ $t("message.deal.view_more") }}</router-link>
            </div>
          </div>
          <div v-for="(flash_deal, i) in flash.flash_deal_products.slice(0,4)" class="col col-md-2 mb-2" :key="i">
            <div class="card-wrapper h-100">
                <div class="card" style="cursor: pointer"  @click="productPage(flash_deal.product.slug)">
                  <img class=" img-fluid zoom-in" style="padding: 5px 5px 5px 5px" v-lazy="showImage(flash_deal.product.thumbnail_img)"
                       alt="Card image cap">
                  <div class="card-body">
                    <p class="card-text price">{{ $t("message.deal.product_price") }} {{ productDiscountPrice(flash_deal.product,flash_deal) }}</p>
                    <p class="discount"><span>{{ $t("message.deal.product_price") }} {{ productPrice(flash_deal.product) }}</span> <span
                        class="badge badge-pill badge-warning">-{{ flash_deal.discount }} {{flash_deal.discount_type === 'percent'?'%':flash_deal.product.currency?flash_deal.product.currency.symbol:'' }}</span></p>
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
  name: "Deal",
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    /*
    * method for single product page redirection
    * */
    productPage(slug){
      this.$router.push({name:'single.product',params:{slug:slug}});
    },
    /*
    * method for product price rand calculate
    * */
    productPrice(product){
      if (product.priceType ==0){
        return product.unit_price;
      }else if(product.priceType ==1 && product.product_stock.length>0){
        let max = Math.max(...product.product_stock.map(d=>d.price));
        let min = Math.min(...product.product_stock.map(d=>d.price));
        return min+'-'+max;
      }else if(product.priceType ==2 && product.price_variation.length>0){
        let max = Math.max(...product.price_variation.map(d=>d.off_price));
        let min = Math.min(...product.price_variation.map(d=>d.off_price));
        return min+'-'+max;
      }
    },
    /*
    * method for product after discount price rand calculate
    * */
    productDiscountPrice(product,flash_deal){
      if (product.priceType ==0){
        if (flash_deal.discount_type ==='percent'){
          return product.unit_price - (product.unit_price *(flash_deal.discount/100))
        }else return (product.unit_price - flash_deal.discount);
      }else if(product.priceType ==1 && product.product_stock.length>0){
        let max = Math.max(...product.product_stock.map(d=>d.price));
        let min = Math.min(...product.product_stock.map(d=>d.price));
        if (flash_deal.discount_type ==='percent'){
          min= min - (min *(flash_deal.discount/100));
          max= max - (max *(flash_deal.discount/100));
        }else {
          min= (min - flash_deal.discount);
          max= (max - flash_deal.discount);
        }
        return min+'-'+max;
      }else if(product.priceType ==2 && product.price_variation.length>0){
        let max = Math.max(...product.price_variation.map(d=>d.off_price));
        let min = Math.min(...product.price_variation.map(d=>d.off_price));
        if (flash_deal.discount_type ==='percent'){
          min= min - (min *(flash_deal.discount/100));
          max= max - (max *(flash_deal.discount/100));
        }else {
          min= (min - flash_deal.discount);
          max= (max - flash_deal.discount);
        }
        return min+'-'+max;
      }
    },

  },
  created() {
    this.flash_dealList.length < 1 ? this.$store.dispatch(FLASH_DEALS_LIST) : '';
  },
  computed: {
    ...mapGetters(["flash_dealList"])
  },
  components: {Timer}
}
</script>

<style scoped>

</style>
