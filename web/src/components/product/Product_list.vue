<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-2">
          <div class="sidebar-filter-wrapper">
            <div class="sidebar-item-wrapper">
              <h3><a data-toggle="collapse" href="#related-category" role="button" aria-expanded="true"
                     aria-controls="related-category">{{ $t("message.product_lists.related_category")}}</a></h3>
              <div class="collapse show" id="related-category">

                <ul>
                  <li v-for="subcategories in subcategory_list" :key="subcategories.id">
                    <router-link
                        :class="subcategory.length !== 0 && subcategory.id === subcategories.id?'font-weight-bold':''"
                        :to="{name: 'category-sub', params: { cat: category.slug, sub: subcategories.slug }}">
                      {{ subcategories.name }}
                    </router-link>
                    <ul v-if="subcategory.length !== 0 && subcategory.id === subcategories.id">
                      <li v-for="subsubcategories in subsubcategory_list" :key="subsubcategories.id">
                        <router-link
                            :class="subsubcategory.id === subsubcategories.id?'font-weight-bold':''"
                            :to="{name: 'category', params: { cat: category.slug, sub: subcategories.slug, subcat: subsubcategories.slug }}">
                          {{ subsubcategories.name }}
                        </router-link>
                      </li>
                    </ul>
                  </li>
                </ul>

              </div>
            </div>
            <div class="sidebar-item-wrapper">
              <h3><a data-toggle="collapse" href="#business-type" role="button" aria-expanded="true"
                     aria-controls="business-type">{{ $t("message.product_lists.business_type")}}</a></h3>
              <div class="collapse show" id="business-type">
                <ul>
                  <li v-for="businessType in business_type" :key="businessType.id">
                    <a href="">{{ businessType.name }}</a>
                  </li>
                </ul>
              </div>
            </div>
            <b-overlay :show="cat_show" opacity="1" spinner-type="grow" no-wrap></b-overlay>
          </div>
        </div>
        <div class="col-sm-8" style="min-height: 75vh">
          <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container d-flex align-items-center">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <router-link :to="{name: 'category'}">{{ category.name }}</router-link>
                </li>
                <li v-if="subcategory.length !== 0" class="breadcrumb-item"
                    :class="subsubcategory.length !== 0?'':'active'">
                  <router-link :to="{name: 'category-sub'}">{{ subcategory.name }}</router-link>
                </li>
                <li v-if="subsubcategory.length !== 0" class="breadcrumb-item active">{{ subsubcategory.name }}</li>
                <li class="breadcrumb-item"><a>{{ $t("message.product_lists.12222_results")}}</a></li>
              </ol>
            </div><!-- End .container -->
          </nav><!-- End .breadcrumb-nav -->

          <div class="top-filter">
            <div class="filters">
              <div class="filter-item mr-4">
                <span class="filter-label"> {{ $t("message.product_lists.price")}}</span>
                <input type="text" class="form-control text-center" style="width: 60px" :placeholder= "$t('message.product_created.min')">
                <span> - </span>
                <input type="text" class="form-control text-center" style="width: 60px" :placeholder= "$t('message.product_created.max')">
              </div>
              <div class="filter-item mr-4">
                <span class="filter-label">{{ $t("message.product_lists.sort_by")}}</span>
                <select class="form-control" title="Sort by">
                  <option>{{ $t("message.product_lists.new_items")}}</option>
                  <option value="1">{{ $t("message.product_lists.price_high_to_low")}}</option>
                  <option value="2">{{ $t("message.product_lists.price_low_to_high")}}</option>
                  <option value="3">{{ $t("message.product_lists.old_items")}}</option>
                </select>
              </div>
              <div class="filter-item mr-4">
                <span class="filter-label">{{ $t("message.product_lists.items_page")}}</span>
                <select class="form-control" id="" title="Items per page">
                  <option value="1">25</option>
                  <option value="2">50</option>
                  <option value="3">100</option>
                </select>
              </div>
            </div>
            <div class="filter-view-item">
              <label>{{ $t("message.product_lists.view")}}</label>
              <svg viewBox="0 0 1024 1024">
                <path
                    d="M409.6 411.733333a2.133333 2.133333 0 0 0 2.133333-2.133333V204.8a2.133333 2.133333 0 0 0-2.133333-2.133333H204.8a2.133333 2.133333 0 0 0-2.133333 2.133333v204.8c0 1.152 0.981333 2.133333 2.133333 2.133333h204.8z m0 64H204.8A66.133333 66.133333 0 0 1 138.666667 409.6V204.8c0-36.522667 29.610667-66.133333 66.133333-66.133333h204.8c36.522667 0 66.133333 29.610667 66.133333 66.133333v204.8a66.133333 66.133333 0 0 1-66.133333 66.133333zM819.2 411.733333a2.133333 2.133333 0 0 0 2.133333-2.133333V204.8a2.133333 2.133333 0 0 0-2.133333-2.133333h-204.8a2.133333 2.133333 0 0 0-2.133333 2.133333v204.8c0 1.152 0.981333 2.133333 2.133333 2.133333h204.8z m0 64h-204.8a66.133333 66.133333 0 0 1-66.133333-66.133333V204.8c0-36.522667 29.610667-66.133333 66.133333-66.133333h204.8c36.522667 0 66.133333 29.610667 66.133333 66.133333v204.8a66.133333 66.133333 0 0 1-66.133333 66.133333zM409.6 821.333333a2.133333 2.133333 0 0 0 2.133333-2.133333v-204.8a2.133333 2.133333 0 0 0-2.133333-2.133333H204.8a2.133333 2.133333 0 0 0-2.133333 2.133333v204.8c0 1.152 0.981333 2.133333 2.133333 2.133333h204.8z m0 64H204.8a66.133333 66.133333 0 0 1-66.133333-66.133333v-204.8c0-36.522667 29.610667-66.133333 66.133333-66.133333h204.8c36.522667 0 66.133333 29.610667 66.133333 66.133333v204.8a66.133333 66.133333 0 0 1-66.133333 66.133333zM819.2 821.333333a2.133333 2.133333 0 0 0 2.133333-2.133333v-204.8a2.133333 2.133333 0 0 0-2.133333-2.133333h-204.8a2.133333 2.133333 0 0 0-2.133333 2.133333v204.8c0 1.152 0.981333 2.133333 2.133333 2.133333h204.8z m0 64h-204.8a66.133333 66.133333 0 0 1-66.133333-66.133333v-204.8c0-36.522667 29.610667-66.133333 66.133333-66.133333h204.8c36.522667 0 66.133333 29.610667 66.133333 66.133333v204.8a66.133333 66.133333 0 0 1-66.133333 66.133333z"></path>
              </svg>
              <svg class="active" viewBox="0 0 1024 1024">
                <path d="M128 236.8v-64h768v64zM128 544v-64h768v64zM128 851.2v-64h768v64z"></path>
              </svg>
            </div>
          </div>

          <div class="top-filter">
            <div class="filters">
              <div class="filter-item mr-4">
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-filter">{{ $t("message.product_lists.all_listings")}}</button>
                  <button type="button" class="btn btn-filter">{{ $t("message.product_lists.buy_it_now")}}</button>
                </div>
              </div>
            </div>
            <div class="filter-view-item">
              <span>{{ $t("message.product_lists.view_by")}}</span>
              <a href="" class="pro">{{ $t("message.product_lists.products")}}</a>
              <span> | </span>
              <a href="">{{ $t("message.product_lists.suppliers")}}</a>
            </div>
          </div>

          <div class="product-item-categoryHeader">
            <hr class="first">
            <div class="name" v-if="subsubcategory.length != 0"> {{ subsubcategory.name }}</div>
            <div class="name" v-if="subsubcategory.length == 0 && subcategory.length != 0"> {{ subcategory.name }}
            </div>
            <div class="name" v-if="subsubcategory.length == 0 && subcategory.length == 0"> {{ category.name }}</div>
            <hr class="last">
          </div>

          <div class="items-lest-wrapper" v-for="products in product" :key="products.id">
            <div class="row">
              <div class="col-sm-2">
                <div class="img-wrapper">
                  <img :src="showImage(products.thumbnail_img)" class="img-fluid" alt="">
                </div>
              </div>
              <div class="col-sm-10">
                <div class="item-key">
                  <div class="cat-wrapper">
                    <div class="row">
                      <div class="col-sm-7">
                        <div class="cat-1">
                          <h4><a href="">{{ products.name }}</a>
                          </h4>
                          <div class="price-cat">
                            <div class="price">
                              <h5 class="hot-price">{{ $t("message.product_lists.৳")}}
                                {{ products.priceType == 1 ? totalPrice(products.product_stock) : products.unit_price }}
                              </h5>
                              <p style="color: #888">{{ $t("message.product_lists.1_piece_mqq")}}</p>
                            </div>
                            <div class="price-verified-wrapper">
                              <p>{{ $t("message.product_lists.verified_seller")}}</p>
                              <a href=""><i class="fab fa-youtube"></i> {{ $t("message.product_lists.company_video")}}</a>
                            </div>
                          </div>
                          <p v-for="property in jsonDecode(products.property_options)">
                            <span style="color: #888">{{ property.name }}:</span> {{ property.value }}</p>
                          <a href="" class="view-link">{{ $t("message.product_lists.view_details")}}</a>
                        </div>
                      </div>
                      <div class="col-sm-5" style="border-left: 1px solid #ddd;">
                        <div class="cat-2">
                          <h4><a href="">{{ $t("message.product_lists.derma_medicine_point")}}</a></h4>
                          <span style="color: #888" class="d-block">{{ $t("message.product_lists.dhaka_bangladesh")}}</span>
                          <span style="color: #888"
                                class="d-block">{{ $t("message.product_lists.manufacturer_trading_company")}}</span>
                          <div class="call-center">
                            <a href="" class="mobile">
                              <i class="fas fa-phone-alt"></i> {{ $t("message.product_lists.call")}}
                              <span style="background: #fff;padding: 5px;margin-left: 8px;">{{ $t("message.product_lists.phone_number")}}</span>
                            </a>
                          </div>
                          <a href="" style="display: inline-block;
                                                    background: #f05931;
                                                    padding: 5px 10px;
                                                    margin-top: 25px;
                                                    font-size: 14px;
                                                    color: #fff;
                                                    border-radius: 3px;"><i class="far fa-envelope mr-1"></i> {{ $t("message.product_lists.contact_supplier")}}</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col-sm-2">
          <div class="w-100 h-100" style="background: #e3e3e3"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import {api_base_url} from "@/core/config/app";

export default {
  name: "Product_list",
  data() {
    return {
      items: [],
      category: [],
      subcategory: [],
      subsubcategory: [],
      subcategory_list: [],
      subsubcategory_list: [],
      business_type: [],
      product: [],
      cat_show: true
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    jsonDecode(e) {
      return JSON.parse(e);
    },
    handleScroll() {
      if ($(window).scrollTop() > 10) {
        $(".sidebar-filter-wrapper").css({"top": "71px", "height": "88vh"});
      } else {
        $(".sidebar-filter-wrapper").css({"top": "109px", "height": "80vh"});
      }
    },
    breadcrumb: function () {
      for (let i = 0; i < 3; i++) {
        if (this.$route.params.cat && i === 0) {
          ApiService.get('category/', this.$route.params.cat)
              .then(({data}) => {
                this.category = data;
                this.items.push({
                  text: data.name,
                  to: {name: 'category'}
                })
                ApiService.get('subcategory/', data.id)
                    .then(({data}) => {
                      this.subcategory_list = data;
                    })
              })
        }
        if (this.$route.params.sub && i === 1) {
          ApiService.get('subcategory-slug/', this.$route.params.sub)
              .then(({data}) => {
                this.subcategory = data;
                this.items.push({
                  text: data.name,
                  to: {name: 'category-sub'}
                })
                ApiService.get('subsubcategory/', data.id)
                    .then(({data}) => {
                      this.subsubcategory_list = data;
                    })
              })
        }
        if (this.$route.params.subcat && i === 2) {
          ApiService.get('subsubcategory-slug/', this.$route.params.subcat)
              .then(({data}) => {
                this.subsubcategory = data;
                this.items.push({
                  text: data.name,
                  href: '#'
                })
              })
        }
        i === 2 ? setTimeout(() => {
          this.cat_show = false
        }, 2000) : '';
      }
      if (this.$route.params.subcat) {

      } else {

      }
    },
    loadBusinessType() {
      ApiService.get('business_type')
          .then(({data}) => {
            this.business_type = data;
          })
    },
    getProduct: function () {
      let url = '';
      if (this.$route.params.cat) {
        url += 'category=' + this.$route.params.cat;
      }
      if (this.$route.params.sub) {
        url += '&subcategory=' + this.$route.params.sub;
      }
      if (this.$route.params.subcat) {
        url += '&subsubcategory=' + this.$route.params.subcat;
      }
      url = 'product-category?' + url;
      ApiService.get(url)
          .then(({data}) => {
            this.product = data;
            this.show = false;
          })
    },
    totalPrice: function (e) {
      if (e.length == 0) return;
      let max = e.reduce((a, b) => Number(a.price) > Number(b.price) ? a : b)
      let min = e.reduce((a, b) => Number(a.price) < Number(b.price) && 0 !== Number(a.price) ? a : b)
      return max === min ? max.price : min.price + ' - ' + max.price;
    }
  },
  created() {
    window.addEventListener('scroll', this.handleScroll);
    this.breadcrumb();
    this.loadBusinessType();
    this.getProduct();
  },
}
</script>

<style scoped>

</style>
