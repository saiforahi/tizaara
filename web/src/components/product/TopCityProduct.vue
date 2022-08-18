<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-2">
          <div class="sidebar-filter-wrapper">
            <div class="sidebar-item-wrapper">
              <h3><a data-toggle="collapse" href="#related-category" role="button" aria-expanded="true"
                     aria-controls="related-category">{{ "Related City" }}</a></h3>
              <div class="collapse show" id="related-category">
                <ul>
                  <li v-for="city in cities.slice(0,20)" :key="city.id">
                    <a href="javascript:void(0)" :class="top_city_id==city.id?'active':''" @click.prevent="changeURL(city.id)">
                      {{ city.name }}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-9">
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
                    {{ 'Top City' }}
                  </li>
                  <li class="breadcrumb-item">
                    {{ city?city.name:'' }}
                  </li>
                  <li class="breadcrumb-item" aria-current="page"><a>({{ product_list?product_list.length:0 }} {{ $t("message.product_new.results") }})</a></li>
                </ol>
              </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="top-filter">
              <div class="filters">
                <div class="filter-item mr-4">
                  <span class="filter-label">{{ $t("message.product_new.price") }} </span>
                  <input type="text" v-model.number="min_value" class="form-control text-center" style="width: 60px" :placeholder= "$t('message.product_created.min')">
                  <span> - </span>
                  <input type="text" v-model.number="max_value" class="form-control text-center" style="width: 60px" :placeholder= "$t('message.product_created.max')">
                </div>
                <div class="filter-item mr-4" v-if="product_brands.length>0">
                  <span class="filter-label">{{ $t("message.product_new.brand") }}</span>
                  <select class="form-control" title="Brand" v-model="brand_id">
                    <option :value="null">{{ $t("message.product_new.select_one") }}</option>
                    <option v-for="brand in product_brands" :value="brand.id">{{ brand.name}}</option>
                  </select>
                </div>
                <div class="filter-item mr-4" v-if="product_colors.length>0">
                  <span class="filter-label">{{ $t("message.product_new.color") }}</span>
                  <select class="form-control" title="Brand" v-model="color_name">
                    <option :value="null">{{ $t("message.product_new.select_one") }}</option>
                    <option v-for="color in product_colors" :value="color.name">{{ color.name}}</option>
                  </select>
                </div>
                <div class="filter-item mr-4">
                  <span class="filter-label">{{ $t("message.product_new.sort_by") }}</span>
                  <select class="form-control" title="Sort by" v-model="sort_by">
                    <option value="new">{{ $t("message.product_new.new_item") }}</option>
                    <option value="old">{{ $t("message.product_new.old_items") }}</option>
                  </select>
                </div>
              </div>
              <div class="filter-view-item">
                <span>{{ $t("message.product_new.view_by") }}</span>
                <a :class="type==='Suppliers'?'active':''" href="javascript:void(0)" @click.prevent="viewBySelect('Suppliers')"><i class="fas fa-th-list"></i></a>
                <a :class="type==='Products'?'active pro':'pro'" href="javascript:void(0)" @click.prevent="viewBySelect('Products')"><i class="fas fa-th-large"></i></a>
              </div>
            </div>

            <div v-if="product_list && product_list.length > 0 && type === 'Suppliers'">

              <div class="items-lest-wrapper" v-for="(product,key) in product_list" :key="key" v-if="product.user">
                <div class="row">
                  <div class="col-sm-2">
                    <div class="img-wrapper">
                      <img :src="showImage(product.thumbnail_img)" class="img-fluid" alt="">
                    </div>
                  </div>
                  <div class="col-sm-10">
                    <div class="item-key">
                      <div class="cat-wrapper">
                        <div class="row">
                          <div class="col-sm-7">
                            <div class="cat-1">
                              <h4><a href="javascript:void(0)" @click="productPage(product.slug)">{{ product.name }}</a>
                              </h4>
                              <p v-if="product.property_options" v-for="(property, k) in jsonDecode(product.property_options)"  :key="k">
                                <span v-if="property.label && property.is_show">
                                    <span style="color: #888" >{{ property.label }}:</span>
                                {{ property.value }}
                                </span>
                              </p>
                              <p v-if="jsonDecode(product.colors).length>0">
                                <span style="color: #888">Colors :</span>
                                <span v-for="(color, k) in jsonDecode(product.colors)">
                                  {{ color }}{{(k+1) != jsonDecode(product.colors).length?',':'' }}
                                </span>
                              </p>
                              <p v-if="product.brand">
                                <span style="color: #888">Brand :</span>
                                <span>{{ product.brand.name }}</span>
                              </p>
                              <div class="price-cat">
                                <div class="price">
                                  <h5 class="hot-price">{{product.currency?product.currency.symbol:null }}
                                    {{ totalPrice(product) }}
                                  </h5>
                                </div>

<!--                                <div class="price-verified-wrapper">
                                  <a target="_blank" v-if="product.video_link"
                                     :href="product.video_link"><i class="fab fa-youtube"></i> {{ $t("message.product_new.company_video") }}</a>
                                </div>-->
                              </div>
                              <a href="javascript:void(0)" @click="productPage(product.slug)" class="view-link">{{ $t("message.product_new.view_details") }}</a>
                            </div>
                          </div>
                          <div class="col-sm-5" style="border-left: 1px solid #ddd;">
                            <div class="cat-2">
                              <h4><a href="">{{ product.user?product.user.company_basic_info?product.user.company_basic_info.name:'':'' }}</a></h4>
                              <span style="color: #888"
                                    class="d-block">{{ product.user?product.user.company_basic_info?product.user.company_basic_info.office_space:'':'' }}</span>
                              <span style="color: #888" v-if="product.user?!!product.user.company_basic_info:false"
                                    class="d-block"><span v-for="(type,key) in businessTypes(product.user.company_basic_info)">{{ type.name }}{{(key+1) != businessTypes(product.user.company_basic_info).length?',':'' }}</span></span>
                              <div class="call-center" v-if="product.user?product.user.company_basic_info?product.user.company_basic_info.phone !=null:false:false">
                                <a href="javascript:void(0)" class="mobile">
                                  <i class="fas fa-phone-alt"></i> {{ $t("message.product_new.call") }}
                                  <span
                                      style="background: #fff;padding: 5px;margin-left: 8px;">{{
                                      product.user?product.user.company_basic_info?product.user.company_basic_info.phone:'':''
                                    }}</span>
                                </a>
                              </div>
                              <a href="javascript:void(0)" @click="contactSupplier(product)" style="display: inline-block;
                                                    background: #f05931;
                                                    padding: 5px 10px;
                                                    margin-top: 25px;
                                                    font-size: 14px;
                                                    color: #fff;
                                                    border-radius: 3px;">
                                <i class="far fa-envelope mr-1"></i> {{ $t("message.product_new.contact_supplier") }}</a>
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
              <a class="product-item" title="Fila Exolize 2" v-for="(product,key) in product_list" :key="key"
                 href="javascript:void(0)" @click="productPage(product.slug)" v-if="product.user">
                <div class="item-box">
                  <div class="img-box">
                    <div class="pdp-mod-wishlist" :style="(isAuthenticated && checkFavorite(product.id))?'float: right;color: red':'float: right'" @click.stop="onFavorite(product)">
                      <i class="fa fa-heart"></i>
                    </div>
                    <img class="equalHeightWidth" :src="showImage(product.thumbnail_img)" alt="Fila Exolize 2">
                  </div>
                  <div class="caption">
                    <div class="product-title">
                      <h6 @click="productPage(product.slug)" title="Fila Exolize 2">{{ product.name }}</h6>
                    </div>
                    <span v-if="jsonDecode(product.colors).length>0">
                      <span style="color: #888">Colors :</span>
                      <span v-for="(color, k) in jsonDecode(product.colors)">
                                  {{ color }}{{(k+1) != jsonDecode(product.colors).length?',':'' }}
                                </span>
                    </span><br>
                    <p v-if="product.brand">
                      <span style="color: #888">Brand :</span>
                      <span>{{ product.brand.name }}</span>
                    </p>
                    <div class="pdp-block">
                      <h5 class="price">{{product.currency?product.currency.symbol:null }}
                        {{ totalPrice(product) }}
                      </h5>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
<!--        <div class="col-sm-2">
          <div class="w-100 h-100" style="background: #e3e3e3"></div>
        </div>-->
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
import {CITY_LIST} from "../../core/services/store/module/city";
export default {
  name: "TopCityProduct",
  props:{
    top_city_id:{
      required:true,
    },
  },
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
      city:'',
      cities:[],
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
    changeURL(city) {
      if(this.top_city_id != city) this.$router.push({name:'top.city.product',params:{top_city_id:city}});
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
    loadProduct() {
      let url = `top/city/products${this.top_city_id}`;
      this.$store.dispatch(GET_PRODUCT_LISTS,url);
    },
    /*
    * method for price calculation
    * */
    totalPrice: function (product) {
      //console.log(product);
      /*if (product.flash_deal_products.length>0){
        let flash = product.flash_deal_products.filter(item=>{
          if (item.flash_deal) return item;
        });
        if (flash.length>0) {
          return flash[flash.length - 1];
        }
      }else*/
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
    businessTypes(company){
      if (company.business_types) return company.business_types;
      else return [];
    },

    jsonDecode(e) {
      return JSON.parse(e);
    },
  },
  created() {
    //this.debounceName = debounce(this.loadProduct, 1000)
    if (this.isAuthenticated) this.$store.dispatch(USER_FAVORITE_LIST);//dispatch user product favorite list
    if (this.cityList.length <1) this.$store.dispatch(CITY_LIST);//dispatch user product favorite list
    this.city=this.$store.getters.getCityNameById(parseInt(this.top_city_id));
    if(this.city) this.cities=this.$store.getters.getCityById(parseInt(this.city.division_id));
    this.loadProduct();
  },
  computed: {
    ...mapGetters(["isAuthenticated","user","user_favorites","product_brands",
    "product_colors","cityList"]),
    product_list(){
      let pd_list=this.$store.getters.product_lists;

      /*if product user not found then it not shows*/
      pd_list = pd_list.map((product)=>{
        if (product.user){
          return product;
        }
      }).filter(Boolean);
      /*minimum price filtering*/
      if (this.min_value){
        pd_list = pd_list.map((product)=>{
          if (product.priceType ==0){
            if (product.unit_price>=this.min_value) return product;
          }else if(product.priceType ==1 && product.product_stock.length>0){
            let min = Math.min(...product.product_stock.map(d=>d.price));
            if (min>=this.min_value) return product;
          }else if(product.priceType ==2 && product.price_variation.length>0){
            let min = Math.min(...product.price_variation.map(d=>d.off_price));
            if (min>=this.min_value) return product;
          }
        }).filter(Boolean);
      }
      /*maximum price filtering*/
      if (this.max_value){
        pd_list = pd_list.map((product)=>{
          if (product.priceType ==0){
            if (product.unit_price<=this.max_value) return product;
          }else if(product.priceType ==1 && product.product_stock.length>0){
            let max = Math.max(...product.product_stock.map(d=>d.price));
            if (max <= this.max_value) return product;
          }else if(product.priceType ==2 && product.price_variation.length>0){
            let max = Math.max(...product.price_variation.map(d=>d.off_price));
            if (max <= this.max_value) return product;
          }
        }).filter(Boolean);
      }
      /*brand filtering*/
      if (this.brand_id){
        pd_list = pd_list.map((product)=>{
          if (product.brand_id ==this.brand_id) return product;
        }).filter(Boolean);
      }
      /*color filtering*/
      if (this.color_name){
        pd_list = pd_list.map((product)=>{
          if (product.colors && JSON.parse(product.colors).includes(this.color_name)) return product;
        }).filter(Boolean);
      }
      /*product sorting*/
      if (this.sort_by == 'new' || this.sort_by == 'old'){
        let sort_by=this.sort_by;
        pd_list = pd_list.sort(function (a,b){
          if (sort_by == 'new') return moment(a.updated_at).format('YMMDD')<moment(b.updated_at).format('YMMDD')?1:-1;
          else if (sort_by == 'old') return moment(a.updated_at).format('YMMDD')>moment(b.updated_at).format('YMMDD')?1:-1;
        });
      }
      return pd_list;
    }
  },
  watch: {
    top_city_id(){
      this.city=this.$store.getters.getCityNameById(this.top_city_id);
    },
    cityList(){
      this.city=this.$store.getters.getCityNameById(parseInt(this.top_city_id));
    },
    city(){
      this.city=this.$store.getters.getCityNameById(parseInt(this.top_city_id));
      if(this.city) this.cities=this.$store.getters.getCityById(parseInt(this.city.division_id));
    }
  }
}
</script>

<style scoped>
  .active{
    color: #f05931;
  }
</style>