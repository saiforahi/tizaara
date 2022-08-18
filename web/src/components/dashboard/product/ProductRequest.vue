<template>
  <div class="content pr-md-5">
    <!-- Page Header -->
    <div class="row mb-3">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.product_request.product") }}
        </span>
        <h3 class="page-title" v-if="type === 2">{{ $t("message.product_request.request_for_flash_deal") }}</h3>
        <h3 class="page-title" v-if="type === 1">{{ $t("message.product_request.request_for_ecommerce_zone") }}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <b-button variant="success"
                    @click="$router.push({name: 'product-list'})" size="sm"
                    class="text-white"><i class="fas fa-hand-point-left"></i> {{ $t("message.product_request.back_to_s_page") }}
          </b-button>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <div v-if="type === 1" class="card card-body">
      <b-table :items="getEcommerceList">
        <template #cell(image)="data">
          <img v-lazy="showImage(data.value)" class="img-thumbnail" width="60px">
        </template>
        <template #cell(status)="data">
          <b-badge>{{ data.value }}</b-badge>
        </template>
      </b-table>
    </div>
    <div v-if="type === 2" class="card card-body">
      <b-table :items="getFlashDealList">
        <template #cell(image)="data">
          <img v-lazy="showImage(data.value)" class="img-thumbnail" width="60px">
        </template>
        <template #cell(status)="data">
          <b-badge>{{ data.value }}</b-badge>
        </template>
      </b-table>
    </div>


  </div>
</template>

<script>
import {
  PRODUCT_REQUEST_ECOMMERCE,
  PRODUCT_REQUEST_FLASH_DEAL,
} from "@/core/services/store/module/product_request";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";

export default {
  name: "ProductRequest",
  data() {
    return {
      type: '',
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadType() {
      if (this.$route.params.type === 'flash-deal') {
        this.type = 2;
        this.$store.dispatch(PRODUCT_REQUEST_FLASH_DEAL);
      } else if (this.$route.params.type === 'ecommerce-zone') {
        this.type = 1;
        this.$store.dispatch(PRODUCT_REQUEST_ECOMMERCE);
      } else {
        this.$router.push({name: "error"});
      }
    }
  },
  created() {
    this.loadType();
  },
  computed: {
    ...mapGetters(["getEcommerceList", "getFlashDealList"])
  },
}
</script>

<style scoped>

</style>