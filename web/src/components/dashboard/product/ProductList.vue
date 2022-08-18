<template>
  <div class="content pr-md-5">
    <!-- Page Header -->
    <div class="row mb-3">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.product_list.product") }}
        </span>
        <h3 class="page-title">{{ $t("message.product_list.product_list") }}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <b-button variant="success"
                    @click="$router.push({name: 'product-request', params: { type: 'flash-deal' }})"
                    size="sm" class="text-white mr-2">{{ $t("message.product_list.flash_deal_request") }}</b-button>
          <b-button variant="success"
                    @click="$router.push({name: 'product-request', params: { type: 'ecommerce-zone' }})" size="sm"
                    class="text-white">{{ $t("message.product_list.ecommerce_zone_request") }}
          </b-button>
        </div>
      </div>
    </div>
    <hr>
    <!-- End Page Header -->

    <div class="row">
      <div class="col">
        <b-form-select class="mb-3" v-model="category_id">
          <b-form-select-option selected :value="null">{{ $t("message.product_list.select_category") }}</b-form-select-option>
          <b-form-select-option v-for="(category,key) in categories" :value="category.id" :key="key">{{ category.name }}</b-form-select-option>
        </b-form-select>
      </div>
      <div class="col">
        <b-form inline>
          <label class="mr-sm-2" for="inline-form-custom-select-pref">{{ $t("message.product_list.sort_by") }}</label>
          <b-form-select id="inline-form-custom-select-pref" v-model="sort_by"
                         class="mb-2 mr-sm-2 mb-sm-0">
            <b-form-select-option selected :value="null">{{ $t("message.product_list.select_sort_by") }}</b-form-select-option>
            <b-form-select-option :value="1">{{ $t("message.product_lists.new_items") }}</b-form-select-option>
            <b-form-select-option :value="2">{{ $t("message.product_list.old_items") }}</b-form-select-option>
          </b-form-select>
        </b-form>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-4 col-sm-6 col-12" v-for="(product, index) in show_products" :key="index">
        <section class="card" style="padding: 5px">
          <div class="corner-ribon black-ribon">
            <div class="dropdown">
                <span type="button" style="font-size: 12px"
                      data-toggle="dropdown" aria-haspopup="true"
                      aria-expanded="false">
                  <i class="fas fa-bars" style="color: #333"></i>
                </span>
              <div class="dropdown-menu dropdown-menu-right" style="font-size: 12px">
                <a class="dropdown-item" href="javascript:void(0)" @click="loadSingleProduct(product)">{{ $t("message.product_list.view_details") }}</a>
                <a class="dropdown-item" href="Request-Flash-Deal" @click.prevent="addFlashDeal(product.id)">{{ $t("message.product_list.request_flash_deal") }}</a>
                <a class="dropdown-item" href="Ecommerce-Zone" @click.prevent="addEcommerceZone(product.id)">{{ $t("message.product_list.request_ecommerce_zone") }}</a>
                <router-link class="dropdown-item" :to="{name: 'product-edit', params: { id: product.id }}">{{ $t("message.product_list.product_edit") }}</router-link>
                <a class="dropdown-item" href="javascript:void(0)" @click="removeProduct(product.id)">{{ $t("message.product_list.product_delete") }}</a>
              </div>
            </div>
          </div>
          <img class="card-img-top border" :src="showImage(product.thumbnail_img)" alt="Card image cap"
               height="150px">
          <h4 style="font-size: 12px">{{ product.name }}</h4>
          <p class="mb-0" style="font-size: 10px">{{ $t("message.product_list.sku") }} {{ product.sku }}</p>
          <hr class="my-1">
          <div class="weather-category twt-category mt-0 pt-0">
            <ul>
              <li class="active">
                <h5>0</h5>
                {{ $t("message.product_list.order") }}
              </li>
              <li>
                <h5>0</h5>
                {{ $t("message.product_list.sales") }}
              </li>
              <li class="border-right-0">
                <h5>0</h5>
                {{ $t("message.product_list.return") }} 
              </li>
            </ul>
          </div>
        </section>
      </div>
    </div>

    <!-- Modal -->
    <b-modal id="brandModal" title="Please enter product discount" hide-footer>
      <b-form @submit.prevent="submit()">
        <b-row class="my-3">
          <b-col sm="8">
            <b-form-input id="input-small" size="sm" :placeholder= "$t('message.product_list.enter_product_discount')" type="number"
                          v-model="$v.form.discount.$model" :state="validateState('discount')" min="0"
                          aria-describedby="input-1-live-feedback">
            </b-form-input>
            <b-form-invalid-feedback v-if="!$v.form.discount.required">
              {{ $t("message.product_list.product_discount_required") }} 
            </b-form-invalid-feedback>
            <b-form-invalid-feedback v-if="!$v.form.discount.maxLength">
              {{ $t("message.product_list.product_discount_5_character") }} 
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="4">
            <b-form-select v-model="form.discount_type" size="sm">
              <b-form-select-option value="flat">{{ $t("message.product_list.flat") }} </b-form-select-option>
              <b-form-select-option value="percent">{{ $t("message.product_list.percent") }}</b-form-select-option>
            </b-form-select>
          </b-col>
        </b-row>
        <b-row class="my-3 justify-content-end">
          <b-col cols="8" sm="6" md="4" class="mb-3 mb-xl-0">
            <b-button variant="primary"
                      type="submit" size="sm"
                      class="text-white">{{ $t("message.product_list.submit") }}
            </b-button>

            <b-button variant="secondary"
                      type="button" size="sm" @click="$bvModal.hide('brandModal')"
                      class="text-white float-right">{{ $t("message.product_list.close") }}
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-modal>
    <!-- End Modal -->

  </div>
</template>

<script>
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";
import {PRODUCT_REQUEST} from "@/core/services/store/module/product_request";
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import moment from "moment";

export default {
  mixins: [validationMixin],
  name: "ProductList",
  data() {
    return {
      form: {
        'product_id': '',
        'discount': '',
        'discount_type': 'flat',
      },
      sort_by:null,
      category_id:null,
      products:[],
      show_products: {},
      categories:[],
    }
  },
  validations: {
    form: {
      discount: {
        required,
        maxLength: maxLength(5)
      },
    }
  },
  created() {
    this.loadData();
    this.getCategories();
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    showImage(e) {
      return api_base_url + e;
    },
    /*
    * method for single product load in new tab
    * */
    loadSingleProduct(product) {
      if (product.is_approved) window.open("/single/product/" + product.slug, "_blank");
      else {
        toast.fire({
          icon: 'error',
          title: this.$t("message.product_list.this_product_is_not_approved_yer")
        });
      }
    },

    async loadData() {
      await ApiService.get('user/product')
          .then(({data}) => {
            this.products = data;
          })
          .catch(({response}) => {
            swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning');
          });
    },
    /*
    * method for get all categories related to this user product
    * */
    async getCategories(){
      await ApiService.get('user/product/related/categories').then((response)=>{
          this.categories=response.data;
      }).catch((error)=>{
        this.categories=[];
      });
    },

    /*
    * method for remove product
    * */
    async removeProduct(id){
      await ApiService.delete(`user/product/remove/byId${id}`).then((response)=>{
        this.$toaster.success(response.data.message);
        this.loadData();
      }).catch((error)=>{
        this.$toaster.error(error);
      });
    },
    addEcommerceZone(id) {
      const product_id = id;
      const user_id = this.$store.getters.currentUser.id;
      const request_type = 1;
      this.$store.dispatch(PRODUCT_REQUEST, {product_id, user_id, request_type})
          .then(() => toast.fire({
            icon: 'success',
            title: this.$t("message.product_list.product_add_ecommerce_zone")
          }))
          .catch((data) => console.log(data));
    },
    addFlashDeal(id) {
      this.form.product_id = id;
      this.$bvModal.show('brandModal');
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      const product_id = this.form.product_id;
      const discount = this.form.discount;
      const discount_type = this.form.discount_type;
      const user_id = this.$store.getters.currentUser.id;
      const request_type = 2;
      this.$bvModal.hide('brandModal');
      this.$store.dispatch(PRODUCT_REQUEST, {product_id, user_id, request_type, discount, discount_type})
          .then(() => toast.fire({
            icon: 'success',
            title: this.$t("message.product_list.product_add_flash_deal")
          }))
          .catch((data) => console.log(data));
    }
  },
  watch:{
    products(){
      this.show_products=this.products;
    },
    category_id(){
      if (this.category_id){
        this.show_products = this.products.map(item=>{
          if(item.category_id == this.category_id) return item;
        }).filter(Boolean);
      }else{
        this.show_products=this.products;
      }
    },
    sort_by(){
      if (this.sort_by){
        let sort_by=this.sort_by;
        this.show_products = this.show_products.sort(function (a,b){
          if (sort_by == 1) return moment(a.updated_at).format('YMMDD')<moment(b.updated_at).format('YMMDD')?1:-1;
          else if (sort_by == 2) return moment(a.updated_at).format('YMMDD')>moment(b.updated_at).format('YMMDD')?1:-1;
        });
      }else {
        this.show_products = this.show_products.sort(function (a,b){
          return moment(a.updated_at).format('YMMDD')<moment(b.updated_at).format('YMMDD')?1:-1;
        });
      }
    }
  }

}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';

.dropdown-menu:focus {
  outline: 0 !important;
}
</style>
