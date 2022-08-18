<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
<!--          <div v-infinite-scroll="loadMore" infinite-scroll-disabled="page_info.busy"
               infinite-scroll-distance="page_info.distance">-->
            <div>
            <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
              <div class="container d-flex align-items-center">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                    <router-link to="/">
                      {{ 'Home' }}
                    </router-link>
                  </li>
                  <li class="breadcrumb-item">
                    {{ 'Top Suppliers' }}
                  </li>
                  <li class="breadcrumb-item" aria-current="page">
<!--                    <a>({{ users?users.length:0 }} {{ $t("message.product_new.results") }})</a>-->
                  </li>
                </ol>
              </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="top-filter justify-content-end">
              <div class="filter-view-item justify-content-end">
                <span>{{ $t("message.product_new.view_by") }}</span>
                <a :class="type==='Suppliers'?'active':''" href="javascript:void(0)" @click.prevent="viewBySelect('Suppliers')"><i class="fas fa-th-list"></i></a>
                <a :class="type==='Products'?'active pro':'pro'" href="javascript:void(0)" @click.prevent="viewBySelect('Products')"><i class="fas fa-th-large"></i></a>
              </div>
            </div>

            <div v-if="users && users.length > 0 && type === 'Suppliers'">

              <div class="items-lest-wrapper" v-for="(product,key) in users" :key="key" v-if="product.company_basic_info">
                <div class="row">
                  <div class="col-sm-2">
                    <div class="img-wrapper">
                      <img :src="product.company_details?showImage(product.company_details.company_logo):''" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="col-sm-10">
                    <div class="item-key">
                      <div class="cat-wrapper">
                        <div class="row">
                          <div class="col-sm-7">
                            <div class="cat-1">
                              <h4><a href="javascript:void(0)" @click="companyProfilePage(product.company_basic_info)">{{ product.company_basic_info.name }}</a>
                              </h4>
                              <a href="javascript:void(0)" @click="companyProfilePage(product.company_basic_info)" class="view-link">{{ $t("message.product_new.view_details") }}</a>
                            </div>
                          </div>
                          <div class="col-sm-5" style="border-left: 1px solid #ddd;">
                            <div class="cat-2">
                              <img v-if="product.company_basic_info.is_verified===1" :src="showImage('verified.png')" width="50%" height="60px" alt="verified" />
                              <span style="color: #888"
                                    class="d-block">{{ product.company_basic_info?product.company_basic_info.office_space:'' }}</span>
                              <span style="color: #888" v-if="!!product.company_basic_info"
                                    class="d-block"><span v-for="(type,key) in businessTypes(product.company_basic_info)">{{ type.name }}{{(key+1) != businessTypes(product.company_basic_info).length?',':'' }}</span>
                              </span>
                              <div class="call-center" v-if="product.company_basic_info?product.company_basic_info.phone !=null:false">
                                <a href="javascript:void(0)" class="mobile">
                                  <i class="fas fa-phone-alt"></i> {{ $t("message.product_new.call") }}
                                  <span
                                      style="background: #fff;padding: 5px;margin-left: 8px;">{{
                                      product.company_basic_info?product.company_basic_info.phone:''
                                    }}</span>
                                </a>
                              </div>
<!--                              <a href="javascript:void(0)" @click="contactSupplier(product)" style="display: inline-block;
                                                    background: #f05931;
                                                    padding: 5px 10px;
                                                    margin-top: 25px;
                                                    font-size: 14px;
                                                    color: #fff;
                                                    border-radius: 3px;">
                                <i class="far fa-envelope mr-1"></i> {{ $t("message.product_new.contact_supplier") }}</a>
                            -->
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="item-container" v-if="type === 'Products'">
              <a class="product-item" title="Company name" v-for="(product,key) in users" :key="key"
                 href="javascript:void(0)" @click="companyProfilePage(product.company_basic_info)" v-if="product.company_basic_info">
                <div class="item-box">
                  <div class="img-box">
                    <img class="equalHeightWidth" :src="product.company_details?showImage(product.company_details.company_logo):''" alt="company logo">
                  </div>
                  <div class="caption">
                    <div class="product-title">
                      <h6 @click="companyProfilePage(product.company_basic_info)" title="company name">{{ product.company_basic_info.name }}<img v-if="product.company_basic_info.is_verified===1" :src="showImage('verified.png')" width="30%" height="25px" alt="verified" /></h6>
                    </div>
                    <p v-if="product.company_basic_info">
                      <span style="color: #888">Office Space :</span>
                      <span>{{ product.company_basic_info?product.company_basic_info.office_space:'' }}</span>
                    </p>
                    <span v-if="businessTypes(product.company_basic_info).length>0">
                      <span style="color: #888">Business Types: :</span>
                      <span v-for="(type,key) in businessTypes(product.company_basic_info)" :key="key">
                                  {{ type.name }}{{(key+1) != businessTypes(product.company_basic_info).length?',':'' }}
                                </span>
                    </span><br>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="col-sm-1"></div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import ApiService from "@/core/services/api.service";
import {api_base_url} from "@/core/config/app";
import {debounce} from "lodash";
import {GET_CONTACT_SUPPLIER_PRODUCT, GET_PRODUCT_LISTS,} from "../../core/services/store/module/product";
import {USER_FAVORITE_LIST} from "../../core/services/store/module/productFavorites";
import moment from "moment";
export default {
  name: "TopSupplier",
  data() {
    return {
      moment,
      page_info: {
        busy: false,
        total: '',
        current_page: 1,
        distance: 200,
      },
      min_value:null,//filtering minimum value store here
      max_value:null,//filtering maximum value store here
      brand_id:null,//selected brand is store here
      color_name:null,//selected color name is store here
      sort_by:'new',//selected store by info store here
      type: 'Suppliers',
      top_rated:false,
      top_review:false,
      top_sell:false,
      users:[],
    }
  },
  methods: {
    /*
    * method for contact supplier modal popup
    * */
    contactSupplier(product){
      if (!this.isAuthenticated){
        $('#reg').modal('hide');
        $(`#login`).modal('show');
      }else{
        this.$store.dispatch(GET_CONTACT_SUPPLIER_PRODUCT,product);
        $('#contact_modal').modal('show');
      }
    },
    /*
    * method for single product page redirection
    * */
    productPage(slug){
      this.$router.push({name:'single.product',params:{slug:slug}});
    },
    showImage(e) {
      return api_base_url + e;
    },
    /*
    * method for view company profile page
    * */
    companyProfilePage(profile){
      if (profile && profile.display_name){
        this.$router.push({name:'company-profile',params:{display_name:profile.display_name}})
      }else {
        this.$toaster.error('This company has no any page');
      }
    },
    /*
    * method for check user favorite products
    * */
    checkFavorite(product_id){
      if(!this.isAuthenticated) return false;
      return this.user_favorites.filter(item=>{
        if (item.product_id == product_id && item.user_id == this.user.id) return item;
      }).length >0;
    },
    /*
    * method for set/unset favorite product
    * */
    onFavorite(product) {
      if(!this.isAuthenticated) {
        swal.fire(this.$t("message.headers.login_now"), this.$t("message.common.please_login"), 'warning');
        return false;
      }else{
        ApiService.post('user-product-favorite/' + product.id)
            .then(() => {
              this.$store.dispatch(USER_FAVORITE_LIST);
              toast.fire({
                icon: 'success',
                title: this.$t("message.common.success"),
              });
            }).catch(() => {
          swal.fire(this.$t("message.headers.login_now"), this.$t("message.common.please_login"), 'warning')
        });
      }
    },

    viewBySelect(type) {
      this.type=type;
    },
    businessTypes(company){
      if (company.business_types) return company.business_types;
      else return [];
    },

    jsonDecode(e) {
      return JSON.parse(e);
    },
    /*
    * method for top sell top revews ,top rated product info change
    * */
    changeTopInfo(type){
      if(type===1){
        this.top_rated=true;
        this.top_review=false;
        this.top_sell=false;
      }else if(type===2){
        this.top_rated=false;
        this.top_review=true;
        this.top_sell=false;
      }else{
        this.top_rated=false;
        this.top_review=false;
        this.top_sell=true;
      }
    },
    parseTry(dt){
      try {
        return parseFloat(dt).toFixed(2);
      }catch(e){
        return dt;
      }
    },
    topSupplier(){
      ApiService.get('top/suppliers').then((response)=>{
        this.users=response.data;
      }).catch(e=>{
        this.users=[];
      });
    }
  },
  created() {
    this.topSupplier();
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
  }
}
</script>

<style scoped>
  .active{
    color: #f05931;
  }
</style>