<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <!-- ======= Hero Section ======= -->
    <section id="hero">
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div v-for="(slider,key) in jsonParse(company_details.company_photos)" :class="key==0?'carousel-item active':'carousel-item'" :key="key">
            <img class="d-block w-100 img-fluid" style="max-height: 400px" :src="imgShow(slider)" alt="First slide">
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </section><!-- End Hero -->

    <main id="main">

      <!-- ======= About Section ======= -->
      <section id="about" class="about">
        <div class="container" data-aos="fade-up">
          <div class="row justify-content-end">
            <div class="col-lg-11">
              <div class="row justify-content-center">
                <div v-if="company_basic.website" class="col-lg-2 col-md-5 col-2 d-md-flex align-items-md-stretch">
                  <div class="count-box py-5">
                    <i class="fa fa-globe fa-4x" aria-hidden="true"></i>
                    <span data-toggle="counter-up"></span>
                    <p>{{ company_basic.website }}</p>
                  </div>
                </div>
                <div class="col-lg-2 col-md-5 col-2 d-md-flex align-items-md-stretch">
                  <div class="count-box py-5">
                    <i class="fa fa-envelope fa-4x" aria-hidden="true" style="color: #3b5998"></i>
                    <span data-toggle="counter-up"></span>
                    <p>{{ company_basic.email }}</p>
                  </div>
                </div>

                <div class="col-lg-2 col-md-5 col-2 d-md-flex align-items-md-stretch">
                  <div class="count-box py-5">
                    <i class="fa fa-mobile fa-4x" style="color: #49a342" aria-hidden="true"></i>
                    <p>{{ company_basic.phone }}</p>
                  </div>
                </div>

                <div class="col-lg-2 col-md-5 col-2 d-md-flex align-items-md-stretch">
                  <div class="count-box pb-5 pt-0 pt-lg-5">
                    <i class="fa fa-clock-o fa-4x" aria-hidden="true" style="color: #833ab4"></i>
                    <span data-toggle="counter-up"></span>
                    <p>{{ moment(moment().format()).diff(company_basic.establishment_date,"year") }} year of experience</p>
                  </div>
                </div>

                <div class="col-lg-2 col-md-5 col-2 d-md-flex align-items-md-stretch">
                  <div class="count-box pb-5 pt-0 pt-lg-5">
                    <i class="fa fa-map-marker fa-4x" style="color: #58C9F3" aria-hidden="true"></i>
                    <span data-toggle="counter-up"></span>
                    <p>{{ company_basic.office_space }}</p>
                  </div>
                </div>
                <div class="col-lg-2 col-md-5 col-2 d-md-flex align-items-md-stretch" v-if="company_basic.is_verified===1">
                  <div class="count-box pb-5 pt-0 pt-lg-5">
                    <img :src="showImage('verified.png')" width="80%" height="80px" alt="verified" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div v-if="company_details.company_video" class="col-lg-6 video-box align-self-baseline">
              <LazyYoutube :src="company_details.company_video"/>
<!--              <img :src="'/images/img.png'" class="img-fluid" alt="">
              <a href="https://www.youtube.com/watch?v=jDDaplaOz7Q" class="venobox play-btn mb-4" data-vbtype="video" data-autoplay="true"></a>
            -->
            </div>
            <div class="col-lg-6 pt-3 pt-lg-0 content" :class="company_details.company_video?'':'col-md-12 col-lg-12'">
              <h3>About Us</h3>
              <div v-html="company_details.about_us"></div>
            </div>
          </div>
          <hr>
        </div>
      </section><!-- End About Section -->

      <!-- ======= Cta Section ======= -->
      <section id="cta" class="cta">
        <div class="container" data-aos="fade-in">
          <div class="text-center">
            <h3>Some Products</h3>
            <div class="col-md-12" v-if="products.length>0">
              <div class="container-fluid">
                <div class="row justify-content-center">
                  <div class="container">
                    <div class="row">
                      <div style="cursor: pointer" class="col-sm-3" v-for="(product,key) in products" @click="productLoad(product.slug)" :key="key">
                        <img :src="showImage(product.thumbnail_img)" :alt="product.name" class="img-thumbnail">
                        <p>{{product.name}}</p>
                        <p>
                          <strong>{{product.currency?product.currency.symbol:null }} {{ productPrice(product) }}</strong><br>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
        </div>
      </section><!-- End Cta Section -->
      <!-- ======= Services Section ======= -->
      <section id="services" class="services  section-bg ">
        <div class="container">

          <div class="row">
            <div class="col-md-6">
              <div class="icon-box" data-aos="fade-up">
                <div class="icon"><i class="las la-book" style="color: #e9bf06;"></i></div>
                <h4 class="title"><a href="javascript:void(0)">Our Mission</a></h4>
                <p class="description">{{company_details.mission}}</p>
              </div>
            </div>

            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div class="icon-box">
                <div class="icon"><i class="las la-file-alt" style="color: #3fcdc7;"></i></div>
                <h4 class="title"><a href="javascript:void(0)">Our Vision</a></h4>
                <p class="description">{{company_details.vision}}</p>
              </div>
            </div>
          </div>

        </div>
      </section><!-- End Services Section -->

    </main><!-- End #main -->
  </div>
</template>

<script>

import ApiService from "../../core/services/api.service";
import {api_base_url} from "../../core/config/app";
import { LazyYoutube } from "vue-lazytube";
import moment from "moment";
export default {
  name: "CompanyProfile",
  components:{
    LazyYoutube,
  },
  props:{
    display_name:{
      required:true,
    }
  },
  data(){
    return{
      moment,
      company_basic:{},
      company_details:{},
      products:{},
    }
  },
  created() {
    this.companyBasic();
    if (this.companyBasic) this.companyDetails();
    if (this.companyBasic) this.getProducts();
  },
  methods:{
    /*
    * method for image url set
    * */
    showImage(e) {
      return api_base_url + e;
    },
    /*
    * method for load clicked product information
    * */
    productLoad(slug){
      this.$router.push({name:'single.product',params:{slug:slug}})
    },
    /*
    * method for related product price rand calculate
    * */
    productPrice(product){
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
    jsonParse(data){
      try {
        return JSON.parse(data);
      } catch (e) {
        return [];
      }
    },
    imgShow(path){
      return api_base_url+path;
    },
    async companyBasic(){
      await ApiService.get(`get/company/basic/info/by/display/name${this.display_name}`).then((response)=>{
        this.company_basic=response.data;
      }).catch(()=>{
        this.company_basic={};
      })
    },
   async companyDetails(){
      await ApiService.get(`get/company/details/by/user${this.company_basic.user_id}`).then((response)=>{
        this.company_details=response.data;
      }).catch(()=>{
        this.company_details={};
      })
    },
  async getProducts(){
    await ApiService.get(`company/product/by${this.company_basic.user_id}`).then((response)=>{
      this.products=response.data;
    }).catch(()=>{
      this.products={};
    })
  }
  },
  watch: {
    company_basic(){
      if(this.company_basic.user_id) this.companyDetails();
      if(this.company_basic.user_id) this.getProducts();
    }
  },
}
</script>

<style scoped>

</style>