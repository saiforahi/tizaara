<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container-fluid">
      <div class="row" v-if="flash_details.banner">
        <img :src="showImage(flash_details.banner)" width="100%" height="100px">
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h3 class="text-center my-2">{{ flash_details.title }}</h3>
          <div class="row justify-content-center">
            <div class="weekly-deals col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
              <Timer
                  :starttime="new Date().getTime()"
                  :endtime="flash_details.end_date"
                  trans='{"status": {}}'></Timer>
            </div>
          </div>
        </div>
        <div class="col-md-12 mt-5">
          <div class="item row">
            <div v-for="(flash, i) in flash_details.flash_deal_products" class="col-6 col-sm-6 col-md-3 mb-5 box-shadow"
                 :key="i">
              <div class="card-wrapper h-100">
                <div class="card" style="cursor: pointer"  @click="productPage(flash.product.slug)">
                  <img class=" img-fluid zoom-in" style="padding: 5px 5px 5px 5px" v-lazy="showImage(flash.product.thumbnail_img)"
                       alt="Card image cap">
                  <div class="card-body">
                    <p class="text-center font-weight-bold pt-2" style="font-size: 12px">{{ flash.product.name }}</p>
                    <p>
                      <span>{{ flash.product.currency ? flash.product.currency.symbol : '' }} {{ productPrice(flash.product) }}</span><br>
                      <del>{{ flash.product.currency?flash.product.currency.symbol:'' }} {{ productDiscountPrice(flash.product,flash) }}</del>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- .row end -->
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import {FLASH_DEALS_LIST} from "@/core/services/store/module/flash_deals";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import Timer from "@/components/helper/Timer";

export default {
  name: "FlashDeal",
  data() {
    return {
      flash_details: [],
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadFlash() {
      if (this.flash_dealList.length > 0) {
        let flash = this.getFlashDealBySlug(this.$route.params.slug);
        if (flash) {
          this.flash_details = flash;
        } else this.$router.push({name: "error"});
      }
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
    this.loadFlash();
  },
  computed: {
    ...mapGetters(["flash_dealList", "getFlashDealBySlug"])
  },
  watch: {
    flash_dealList() {
      this.loadFlash();
    }
  },
  components: {Timer}
}
</script>

<style scoped>
.weekly-deals >>> span {
  color: yellow;
  padding: 4px;
  background-color: red;
  border-radius: 3px;
}
</style>