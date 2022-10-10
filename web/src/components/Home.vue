<template>
  <div style="min-height: 90vh">
    <Loader v-if="loading"/>
    <div v-if="!loading">
      <CategoryMenu/>
      <advertisement/>
      <EcomZone/>
      <Deal/>
      <Cat_product/>
      <Testimonial/>
      <Brands/>
      <FindSupplierFromTopCity/>
      <Quotation/>
      <Newsletter/>
    </div>
  </div>
</template>

<script>
import CategoryMenu from './home/Category_menu'
import Deal from './home/Deal'
import EcomZone from './home/EcomZone'
import Brands from "@/components/home/Brands";
import Quotation from "@/components/home/Quotation";
import Cat_product from "@/components/home/Cat_product";
import Newsletter from "@/components/home/Newsletter";
import Testimonial from "@/components/home/Testimonial";
import Loader from "@/components/home/Loader";
import {SUBCATEGORY_LIST} from "@/core/services/store/module/subcategory";
import {CATEGORY_LIST} from "@/core/services/store/module/category";
import {SUBSUBCATEGORY_LIST} from "@/core/services/store/module/subsubcategory";
import {HOMEBANNER_LIST} from "@/core/services/store/module/homeslider";
import {HOME_CATEGORY_PRODUCT} from "@/core/services/store/module/category";
import {BRAND_LIST} from "@/core/services/store/module/brand";
import FindSupplierFromTopCity from "./home/FindSupplierFromTopCity";
import {mapGetters} from "vuex";
import {CITY_LIST, TOP_CITY_LIST} from "../core/services/store/module/city";
import Advertisement from "@/components/home/Advertisement";
import {ADVERTISEMENT_LIST} from "@/core/services/store/module/advertisement";

export default {
  name: "Home",
  data() {
    return {
      loading: false,
    }
  },
  async mounted() {
    this.loading = true
    this.categoryList.length < 1 ? await this.$store.dispatch(CATEGORY_LIST) : '';
    this.loading = false
  },
  created() {
    this.getHomeBanner.length < 1 ? this.$store.dispatch(HOMEBANNER_LIST) : '';
    this.subsubcategoryList.length < 1 ? this.$store.dispatch(SUBSUBCATEGORY_LIST) : '';
    this.subcategoryList.length < 1 ? this.$store.dispatch(SUBCATEGORY_LIST) : '';
    this.categoryList.length < 1 ? this.$store.dispatch(CATEGORY_LIST) : '';
    this.homeCategoryProduct.length < 1 ? this.$store.dispatch(HOME_CATEGORY_PRODUCT) : '';
    this.brandList.length < 1 ? this.$store.dispatch(BRAND_LIST) : '';
    this.brandList.length < 1 ? this.$store.dispatch(BRAND_LIST) : '';
    this.brandList.length < 1 ? this.$store.dispatch(BRAND_LIST) : '';
    this.cityList.length < 1 ? this.$store.dispatch(CITY_LIST) : '';
    this.advertisementList.length < 1 ? this.$store.dispatch(ADVERTISEMENT_LIST) : '';
    this.$store.dispatch(TOP_CITY_LIST);
  },
  components: {
    Advertisement,
    CategoryMenu,
    Deal,
    EcomZone,
    Brands,
    Quotation,
    Cat_product,
    Newsletter,
    Testimonial,
    FindSupplierFromTopCity,
    Loader
  },
  computed: {
    ...mapGetters(["categoryList", "subcategoryList", "cityList", "subsubcategoryList", "brandList", "homeCategoryProduct", "getHomeBanner", "advertisementList"])
  },
}
</script>

<style scoped>

</style>
