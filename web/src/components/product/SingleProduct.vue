<template xmlns="http://www.w3.org/1999/html">
  <div class="product-list-wrapper" style="min-height: 80vh">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
      <div class="container d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link to="/">
              {{ 'Home' }}
            </router-link>
          </li>
          <li class="breadcrumb-item" v-if="product.category">
            <router-link :to="{name: 'category', params: { cat: product.category.slug }}">
              {{ product.category.name }}
            </router-link>
          </li>
          <li class="breadcrumb-item" v-if="product.subcategory">
            <router-link
                :to="{name: 'category', params: { cat: product.category.slug, sub: product.subcategory.slug, }}">
              {{ product.subcategory.name }}
            </router-link>
          </li>
          <li class="breadcrumb-item" v-if="product.subsubcategory">
            <router-link
                :to="{name: 'category', params: { cat: product.category.slug, sub: product.subcategory.slug, sub_sub: product.subsubcategory.slug,}}">
              {{ product.subsubcategory.name }}
            </router-link>
          </li>
          <li class="breadcrumb-item" aria-current="page" v-if="product.name"><a> >{{ product.name }}</a></li>
        </ol>
      </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->
    <div class="container">
      <div class="row">
        <div class="col-md-9 p-md-4 bg-white">
          <div class="row">
            <div class="col-md-4">
              <image-magnifier :src="current_image?current_image:showImage(product.thumbnail_img)"
                               :zoom-src="current_image?current_image:showImage(product.thumbnail_img)"
                               width="100%"
                               height="320px"
                               zoom-width="500"
                               class="w-100 border"
                               zoom-height="320">
              </image-magnifier>

              <ul class="list-group list-group-horizontal">
                <li class="list-group-item p-1 mr-1 mt-1" v-for="image in jsonParse(product.photos)"
                    @click="imageChange(showImage(image))">
                  <img :src="showImage(image)" alt="" class="img-fluid rounded" width="40px" height="60px">
                </li>
              </ul>
            </div>
            <div class="col-md-8">
              <blockquote>
                <h5>{{ product.name }}</h5>
                <p class="text-muted">{{ product.sort_desc }}</p>
                <!--   rating and review count section         -->
                <img v-if="companyProfile.is_verified===1" :src="showImage('verified.png')" width="20%" height="20px" alt="verified" />
                <span class="text-danger" style="font-size: 18px; max-width: 5%">
                  <b-form-rating class="col-sm-2 transparent-input" size="sm"
                                 v-model="parseFloat(ratingCalculation()).toFixed(2)" readonly variant="danger" inline
                                 no-border></b-form-rating>
                                                     {{ parseFloat(ratingCalculation()).toFixed(2) }}
                </span>
                <span style="font-size: 18px;"> <span
                    class="text-danger">   {{ reviewCount() }}</span> {{ $t("message.single_product.review") }} <span
                    class="text-danger">{{ product.num_of_sale }}</span> {{ $t("message.single_product.buyer") }}</span>
                <!--    end review and rating count        -->
                <div class="des float-right" :style="(isAuthenticated && checkFavorite())?'color: red':''"
                     style="font-size: 18px" @click="favorite()">
                  <i class="fa fa-heart fav" style="float: right;"></i>
                </div>
                <div class="clearfix"></div>
                <hr>
                <table class="table table-borderless" v-if="flash_deal===null && product.discount_variation==1">
                  <template>
                    <tr>
                      <td style="text-align: center;padding: 2px" v-for="variation in product.discount_variation_data">
                        {{ ' >= ' + (variation.min_qty) }} {{ product.unit ? product.unit.name : null }}
                      </td>
                    </tr>
                    <tr>
                      <td style="text-align: center" v-for="(variation,key) in product.discount_variation_data">
                    <span
                        :style="variation.min_qty<=quantity?product.discount_variation_data.length==(key+1)?'color:red':quantity<product.discount_variation_data[(key+1)].min_qty?'color:red':'':''">
                      <strong> {{ variation.percent_off }} % off</strong></span></td>
                    </tr>
                  </template>
                </table>

                <p v-if="flash_deal">{{ flash_deal.flash_deal ? flash_deal.flash_deal.title : '' }}
                  {{ flash_deal.discount_type === 'Flat' ? product.currency ? product.currency.symbol : null : '' }}
                  {{ flash_deal.discount }} {{ flash_deal.discount_type === 'percent' ? '%' : '' }} discount</p>

                <p v-if="product.priceType==0">
                  <strong>{{ product.currency ? product.currency.symbol : null }} <span
                      v-html="(discount_price!=0 && discount_price<product.unit_price)?discount_price+' '+'<del>'+product.unit_price+'</del>':product.unit_price"></span></strong>
                  {{
                    product.orderQtyLimit ? ' ' + product.orderQtyLimitMin + product.unit.name + ' (Min. Order)' + '   ' + product.orderQtyLimitMax + product.unit.name + ' (Max. Order)' : ''
                  }}
                </p>
                <p v-else-if="product.priceType==1">
                  <strong>{{ product.currency ? product.currency.symbol : null }}
                    <span
                        v-html="(discount_price!=0 && discount_price<price)?discount_price+' '+'<del>'+price+'</del>':price"></span>
                  </strong>
                  {{
                    product.orderQtyLimit ? ' ' + product.orderQtyLimitMin + ' ' + product.unit.name + ' (Min. Order)' + '   ' + product.orderQtyLimitMax + ' ' + product.unit.name + ' (Max. Order)' : ''
                  }}
                </p>
                <div v-if="product.priceType==2">
                  <table class="table table-borderless">
                    <tr>
                      <td style="text-align: center;padding: 2px" v-for="variation in product.price_variation">
                        {{ variation.min_qty }} - {{ variation.max_qty }} {{ product.unit ? product.unit.name : null }}
                      </td>
                    </tr>
                    <tr>
                      <td style="text-align: center" v-for="variation in product.price_variation">
                    <span :style="(variation.min_qty<=quantity && variation.max_qty>=quantity)?'color:red':''">
                      <template
                          v-if="(variation.min_qty<=quantity && variation.max_qty>=quantity)">{{
                          priceSet(tearPricingDiscount(variation.off_price), variation.off_price)
                        }}</template>
                      <strong>{{ product.currency ? product.currency.symbol : null }}
                        <span v-if="product.discount_variation ==0 && product.discount">{{
                            tearPricingDiscount(variation.off_price)
                          }} </span>
                        <span v-if="product.discount_variation ==0 && product.discount"><del>{{
                            '   ' + variation.off_price
                          }}</del></span>
                        <span v-else>{{ '   ' + variation.off_price }}</span>
                      </strong>
                    </span>
                      </td>
                    </tr>

                  </table>
                  <template style="text-align: center">
                    {{
                      product.orderQtyLimit ? ' ' + product.orderQtyLimitMin + ' ' + product.unit.name + ' (Min. Order)' + '   ' + product.orderQtyLimitMax + ' ' + product.unit.name + ' (Max. Order)' : ''
                    }}
                  </template>
                </div>
                <hr>
                <!-- property section
              -->
                <div v-for="(property,key) in property_options" :key="key" v-if="property.label && property.is_show">
                  <span>{{ property.label + ': ' + property.value }}</span><br>
                </div>
                <!--    color section        -->
                <div v-if="colors.length>0 || color_images.length>0">
                  <p>Color: <span style="color: green">{{ variant[0] ? variant[0] : null }}</span></p>
                  <!--              normal color-->
                  <template v-if="product.color_type ==0">
                    <a class="custom_button_design" href="javascript:void(0)" v-for="(color,key) in colors"
                       :id="'color'+key" @click="variantChange(color,0)">{{ color }}</a>
                  </template>
                  <!--color is image-->
                  <ul v-else class="list-group list-group-horizontal">
                    <li v-for="(color,key) in color_images"
                        :class="variant[0]===color.name?'list-group-item list-active':'list-group-item'"
                        @click="variantChange(color.name,0)" :key="key">
                      <img :src="showImage(color.image)" alt=""
                           @click="imageChange(showImage(color.image))" class="img-thumbnail rounded" width="50px"
                           height="50px">
                    </li>
                  </ul>
                </div>
                <hr>
                <!-- attribute section
              -->
                <div v-for="(attribute,key) in attribute_options" :key="key">
                  <p>{{ attribute.name + ': ' }} <span
                      style="color: green">{{ variant[key + 1] ? variant[key + 1] : null }}</span></p>
                  <a class="custom_button_design" href="javascript:void(0)" v-for="value in attribute.value"
                     @click="variantChange(value.text,(key+1))">{{ value.text }}</a>
                </div>
                <hr v-if="attribute_options.length>0">
                <!--<input type="number" class="form-control" value="1">-->
              </blockquote>
              <h6>{{ $t("message.single_product.quantity") }}</h6>
              <div class="qty-box">
                <div class="input-group">
              <span class="input-group-prepend">
                <button type="button" @click="quantityChange(0)" data-type="minus" class="btn quantity-left-minus">
                  <i class="fa fa-minus-circle btn-outline-danger" aria-hidden="true"></i>
                </button>
              </span>
                  <input type="number" @change="qtyChange" name="quantity" v-model.number="quantity" min="1"
                         class="form-control input-number">
                  <span class="input-group-prepend">
                <button type="button" @click="quantityChange(1)" data-type="plus" class="btn quantity-right-plus">
                  <i class="fa fa-plus-circle btn-outline-danger" aria-hidden="true"></i>
                </button>
              </span>
                  <p style="margin-top: 3px; margin-left: 10px;"
                     v-if="product.stockManagement==1 && (product.priceType ==0 || product.priceType ==2)">
                    {{ product.quantity }} {{ product.unit ? product.unit.name : null }} available</p>
                  <p style="margin-top: 3px; margin-left: 10px;"
                     v-if="product.stockManagement==1 && product.priceType ==1">{{ available_product_qty }}
                    {{ product.unit ? product.unit.name : null }} available</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--Cart section start here-->
        <div class="col-md-3 mb-5">
          <div class="card">
            <article class="card-body">
              <p>
                <a href="javascript:void(0)" @click="contactSupplier" class="btn btn-block btn-outline-danger">
                  <i class="far fa-envelope mr-1"></i> {{ $t("message.cart_sidebar.contact_seller") }}
                </a>
                <a href="javascript:void(0)" class="btn btn-block btn-outline-danger">
                  <i class="fas fa-phone-alt"></i> {{ $t("message.product_new.call") }}
                  {{product.user ? product.user.company_basic_info ? product.user.company_basic_info.phone : '' : '' }}
                </a>
              </p>
              <div class="col-md-12" v-if="ecom_zone">
                <button class="btn btn-outline-primary button button-5 button-5a icon-cart btn-custom" @click="adCart">
                  <i class="fa fa-shopping-cart"></i><span>{{ $t("message.cart_sidebar.add_to_cart") }}</span>
                </button>
              </div>
              <hr>
              <div class="col-md-12" style="margin-top: 5px;">
                <div class="p-2 mb-3" style="background-color: #f5f6fc">
                  <img style="margin-left: 40%" v-lazy="showImage(company_details.company_logo)" class="rounded" alt="" width="50px"
                       height="50px"/>
                  <div class="text-center">
                    <h6 class="mt-2">{{ company_basic.name }}</h6>
                    <p class="text-muted mb-2">{{ company_basic.ownership_type }}</p>
                    <a href="javascript:void(0)" class="border rounded p-1 mb-2 text-white bg-secondary" @click="companyProfilePage()">{{ $t("message.cart_sidebar.visit_store") }}</a>
                  </div>
                </div>
              </div>
            </article>
          </div>
        </div>
        <!--     end Cart section here-->
        <!--     related product section   -->
        <div class="col-md-9" v-if="related_products.length>0">
          <div class="container-fluid">
            <div class="row justify-content-center">
              <div class="container">
                <h5>You may also like</h5>
                <div class="row">
                  <div class="col-sm-3" v-for="(product,key) in related_products" @click="productLoad(product.slug)"
                       :key="key">
                    <img :src="showImage(product.thumbnail_img)" :alt="product.name" class="img-thumbnail">
                    <p>{{ product.name }}</p>
                    <p>
                      <strong>{{ product.currency ? product.currency.symbol : null }} {{
                          relatedProductPrice(product)
                        }}</strong><br>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  end related product section  -->
        <!--  production details and company details start here  -->
        <div class="col-md-9">
          <div class="container-fluid">
            <div class="row justify-content-center">
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <div class="tab" role="tabpanel">
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" :class="profileType==='personal'?'active':''">
                          <a href="javascript:void(0)" aria-controls="personal" role="tab" data-toggle="tab"
                             @click.prevent="profileType = 'personal'">{{
                              $t("message.single_product.product_details")
                            }}</a>
                        </li>
                        <li role="presentation" :class="profileType==='company'?'active':''">
                          <a href="javascript:void(0)" aria-controls="company" role="tab" data-toggle="tab"
                             @click.prevent="profileType = 'company'">{{
                              $t("message.single_product.company_profile")
                            }}</a>
                        </li>
                        <li role="presentation" :class="profileType==='review'?'active':''">
                          <a href="javascript:void(0)" aria-controls="review" role="tab" data-toggle="tab"
                             @click.prevent="profileType = 'review'">{{
                              $t("message.single_product.product_review")
                            }}</a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content tabs">
                        <div v-if="profileType === 'personal'">
                          <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                              <div class="container">
                                <b-row class="p-2 border-bottom">
                                  <b-col cols="12">
                                    <h4>{{ $t("message.single_product.over_view") }}</h4>
                                  </b-col>
                                  <b-col cols="12">
                                    {{ $t("message.single_product.quick_details") }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2">
                                    {{ $t("message.single_product.title") }} {{ product.name }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2" v-if="product.category">
                                    {{ $t("message.single_product.category") }}
                                    {{ product.category ? product.category.name : '' }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2" v-if="product.subcategory">
                                    {{ $t("message.single_product.sub_category") }}
                                    {{ product.subcategory ? product.subcategory.name : '' }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2" v-if="product.subsubcategory">
                                    {{ $t("message.single_product.sub_sub_category") }}
                                    {{ product.subsubcategory ? product.subsubcategory.name : '' }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2" v-if="product.brand">
                                    {{ $t("message.single_product.brand") }}
                                    {{ product.brand ? product.brand.name : '' }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2" v-if="product.model">
                                    {{ $t("message.single_product.model") }} {{ product.model }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="jsonParse(product.colors) && jsonParse(product.colors).length>0">
                                    {{ $t("message.single_product.color") }} {{ jsonParse(product.colors) }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="jsonParse(product.attribute_options) && jsonParse(product.attribute_options).length>0"
                                         v-for="(attr,key) in jsonParse(product.attribute_options)" :key="key">
                                    {{ attr.name + ': ' }}
                                    <template v-for="value in attr.value">{{ value.text + ' ' }},</template>
                                  </b-col>
                                  <template
                                      v-if="jsonParse(product.property_options) && jsonParse(product.property_options).length>0">
                                    <b-col cols="12" md="6" class="my-2"
                                           v-for="(attribute,key) in jsonParse(product.property_options)" :key="key"
                                           v-if="attribute.label">
                                      {{ attribute.label + ': ' + attribute.value }}
                                    </b-col>
                                  </template>
                                </b-row>
                                <b-row class="p-2 border-bottom">
                                  <b-col cols="12">
                                    <h6>{{ $t("message.single_product.details") }}</h6>
                                    <hr>
                                  </b-col>
                                  <div class="col-md-12" v-html="product.description"></div>

                                  <b-col cols="12" v-if="product.video_link">
                                    <hr>
                                    <h6>{{ $t("message.single_product.video_description") }}</h6>
                                    <video-embed ref="youtube" :src="product.video_link"
                                                 style="height: 350px; border-radius: 15px"></video-embed>
                                  </b-col>
                                  <div class="col-md-12" v-for="(image,key) in jsonParse(product.photos)" :key="key">
                                    <img :src="showImage(image)" alt="" class="img-fluid rounded mx-auto d-block">
                                  </div>
                                </b-row>
                                <b-row class="p-2 border-bottom">
                                  <b-col cols="12">
                                    <h6>{{ $t("message.single_product.packaging_shipping") }}</h6>
                                    <hr>
                                  </b-col>
                                  <div class="col-md-12">
                                    <p>1. Inner Packing : Gift box or customized as your requirements</p>
                                    <p>2. Outer Packing : Master Carton</p>
                                    <p> 3. Packing Details:16* 1080P AHD Camera/ /1*16 Channel DVR</p>
                                  </div>
                                </b-row>
                                <b-row class="p-2 border-bottom">
                                  <b-col cols="12">
                                    <h6>{{ $t("message.single_product.our_service") }}</h6>
                                    <hr>
                                  </b-col>
                                  <div class="col-md-12">
                                    <p> 1. More than 10 years' experience in surveillance products manufacturing;</p>
                                    <p> 2. Own one 13 professional engineer team, capable for OEM/ODM business;</p>
                                    <p> 3. TQM ( Total quality control);</p>
                                    <p> 4. Professional sales team,satisfory technical support and after-sale
                                      service.</p>
                                    <p> 5. Delivery time: Sample order 2-3 working days, bulk order 7-15 working
                                      days.</p>
                                    <p> 6. Quality Warranty: 2 years and lifetime technical support;</p>
                                  </div>
                                </b-row>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div v-if="profileType === 'company'">
                          <div class="card">
                            <div class="card-header">
                            </div>
                            <div v-if="product.user && product.user.company_basic_info" class="card-body">
                              <div class="container">
                                <b-row class="p-2 border-bottom">
                                  <div class="col-md-4" v-if="product.user.company_details"
                                       v-for="image in jsonParse(product.user.company_details.company_photos)">
                                    <img :src="showImage(image)" :alt="product.user.company_basic_info.name"
                                         class="img-thumbnail">
                                  </div>
                                  <b-col cols="12">
                                    <h6>{{ $t("message.single_product.company_basic") }}</h6>
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2">
                                    {{ $t("message.company.company_name") }} {{ product.user.company_basic_info.name }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="product.user.company_basic_info.ownership_type">
                                    {{ $t("message.company.ownership_type") }}
                                    {{ product.user.company_basic_info.ownership_type }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="product.user.company_basic_info.main_product">
                                    {{ $t("message.company.main_product") }}
                                    {{ product.user.company_basic_info.main_product }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="product.user.company_basic_info.other_product">
                                    {{ $t("message.company.other_product") }}
                                    {{ product.user.company_basic_info.other_product }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="product.user.company_basic_info.number_of_employee">
                                    {{ $t("message.company.number_of_employee") }}
                                    {{ product.user.company_basic_info.number_of_employee }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="product.user.company_basic_info.establishment_date">
                                    {{ $t("message.company.establishment_date") }}
                                    {{ product.user.company_basic_info.establishment_date }}
                                  </b-col>
                                  <b-col cols="12" md="6" class="my-2"
                                         v-if="product.user.company_basic_info.office_space">
                                    {{ $t("message.company.office_space") }}
                                    {{ product.user.company_basic_info.office_space }}
                                  </b-col>
                                </b-row>
                                <b-row class="p-2"
                                       v-if="product.user.company_basic_info.company_certifications.length>0">
                                  <b-col cols="12">
                                    <h6>{{ $t("message.single_product.certification") }}</h6>
                                  </b-col>
                                  <template class="col-md-12"
                                            v-for="certification in product.user.company_basic_info.company_certifications">
                                    <b-col cols="12" md="4" class="my-2">
                                      {{ $t("message.certification.photo") }} <img
                                        :src="showImage(certification.certificate_photo_name)"
                                        :alt="product.user.company_basic_info.name" class="img-fluid rounded"
                                        width="50px" height="50px">
                                    </b-col>
                                    <b-col cols="12" md="4" class="my-2">
                                      {{ $t("message.certification.name") }} {{ certification.name }}
                                    </b-col>
                                    <b-col cols="12" md="4" class="my-2">
                                      {{ $t("message.certification.reference_number") }}
                                      {{ certification.reference_number }}
                                    </b-col>
                                  </template>

                                </b-row>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div v-if="profileType === 'review'">
                          <div class="card">
                            <div class="card-header">
                            </div>
                            <div v-if="product.user && product.user.company_basic_info" class="card-body">
                              <div class="container">
                                <b-row class="p-2 border-bottom">
                                  <b-col cols="12">
                                    <h6>{{ $t("message.single_product.product_quality") }}</h6>
                                  </b-col>
                                  <b-col cols="12">
                                    <b-row>
                                      <b-col cols="5" style="margin-top: 10%">
                                          <span class="text-danger">
                                            <b-input-group size="lg">
                                              <b-input-group-prepend is-text>
                                                <span class="text-danger">{{
                                                    parseFloat(ratingCalculation()).toFixed(2)
                                                  }} </span>
                                              </b-input-group-prepend>
                                              <b-form-rating class="col-sm-6" variant="danger" inline no-border
                                                             v-model="parseFloat(ratingCalculation()).toFixed(2)"
                                                             readonly></b-form-rating>
                                            </b-input-group>
                                          </span><br>
                                        <span style="font-size: 18px;"> {{ reviewCount() }} reviews</span>
                                      </b-col>
                                      <b-col cols="6">
                                        <tr class="table table-borderless">
                                          <td colspan="8"><span style="font-size: 18px;"> 5 starts </span><br></td>
                                          <td colspan="2"><span style="font-size: 18px;">({{
                                              ratingCount(5)
                                            }})</span><br></td>
                                        </tr>
                                        <tr class="table table-borderless">
                                          <td colspan="8"><span style="font-size: 18px;"> 4 starts </span><br></td>
                                          <td colspan="2"><span style="font-size: 18px;">({{
                                              ratingCount(4)
                                            }})</span><br></td>
                                        </tr>
                                        <tr class="table table-borderless">
                                          <td colspan="8"><span style="font-size: 18px;"> 3 starts </span><br></td>
                                          <td colspan="2"><span style="font-size: 18px;">({{
                                              ratingCount(3)
                                            }})</span><br></td>
                                        </tr>
                                        <tr class="table table-borderless">
                                          <td colspan="8"><span style="font-size: 18px;"> 2 starts </span><br></td>
                                          <td colspan="2"><span style="font-size: 18px;">({{
                                              ratingCount(2)
                                            }})</span><br></td>
                                        </tr>
                                        <tr class="table table-borderless">
                                          <td colspan="8"><span style="font-size: 18px;"> 1 starts </span><br></td>
                                          <td colspan="2"><span style="font-size: 18px;">({{
                                              ratingCount(1)
                                            }})</span><br></td>
                                        </tr>
                                      </b-col>
                                    </b-row>
                                    <hr>
                                  </b-col>
                                  <b-col cols="12" v-if="reviewCount()>0" v-for="(review,key) in reviews" :key="key">
                                    <b-row>
                                      <b-col cols="3" style="margin-top: 5%">
                                        <span style="font-size: 20px;">{{
                                            review.user ? review.user.first_name + ' ' + review.user.last_name : ''
                                          }}</span><br>
                                        <span style="font-size: 16px;"></span>
                                      </b-col>
                                      <b-col cols="9">
                                          <span class="text-danger">
                                            <b-form-rating class="col-sm-2 mb-0" variant="danger" inline no-border
                                                           v-model="parseFloat(ratingFindByuser(review.user_id)).toFixed(2)"
                                                           readonly></b-form-rating>
                                          </span>
                                        <span
                                            style="font-size: 16px; float: right">{{
                                            moment(review.created_at).format('Y-MM-DD')
                                          }} </span><br>
                                        <span style="font-size: 18px;">
                                             {{ review.comment }}
                                          </span><br>
                                      </b-col>
                                    </b-row>
                                    <hr>
                                  </b-col>
                                  <b-col v-if="isAuthenticated && user.id !=product.user_id" cols="12">
                                    <h6>{{ $t("message.single_product.review_rating") }}</h6>
                                    <b-row>
                                      <b-col cols="1" style="margin-top: 5%"></b-col>
                                      <b-col cols="11">
                                        <div class="col-12 col-md-8 my-3">
                                          <form method="post" action="javascript:void(0)" ref="review_form"
                                                @submit="reviewSubmit()">
                                            <b-form-group id="input-group-2" class="col-md-6" label-for="rating"
                                                          label="Rating">
                                              <b-input-group size="lg">
                                                <b-form-rating id="rating" name="rating" v-model="form.rating"
                                                               variant="danger" inline no-border class="mb-2"
                                                               required></b-form-rating>
                                              </b-input-group>
                                            </b-form-group>
                                            <b-form-group id="input-group-2" label-for="review" label="Review">
                                              <b-input-group size="sm">
                                                <b-form-textarea id="review" name="review" size="sm"
                                                                 v-model="form.review" rows="5"
                                                                 required></b-form-textarea>
                                              </b-input-group>
                                            </b-form-group>
                                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                          </form>
                                        </div>
                                      </b-col>
                                    </b-row>
                                  </b-col>
                                </b-row>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  production details and company details end here  -->
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import {api_base_url} from "@/core/config/app";
import {mapGetters} from "vuex";
import moment from "moment";
import {ImageMagnifier} from 'vue-image-magnifier'

import {
  PRODUCT_QUANTITY_CHANGE, PRODUCT_VARIANT, SET_PRODUCT_PRICE,
  SINGLE_PRODUCT_DETAILS,
} from "../../core/services/store/module/singleProduct";
import {REVIEW_RATING_LIST} from "../../core/services/store/module/reviews";
import {GET_CONTACT_SUPPLIER_PRODUCT} from "../../core/services/store/module/product";
import {CART_LIST} from "../../core/services/store/module/cart";

export default {
  name: "SingleProduct",
  props: {
    slug: {
      required: true,
    }
  },
  components: {
    ImageMagnifier
  },
  data() {
    return {
      moment,
      profileType: 'personal',
      current_image: null,
      quantity: 1,//selected quantity store here
      local_price: 0,
      local_discount_price: 0,
      form: new Form({
        'rating': null,
        'review': null,
      }),
      companyProfile: {},
    }
  },
  created() {
    this.$store.dispatch(SINGLE_PRODUCT_DETAILS, this.slug);
    if (this.product) this.companyBasicInfo();
    //this.getProduct();
  },
  methods: {
    /*json data parse*/
    jsonParse(data) {
      try {
        return JSON.parse(data);
      } catch (e) {
        return [];
      }
    },
    priceSet(dis_price, price) {
      this.$store.commit(SET_PRODUCT_PRICE, price);
    },
    /*
    * method for contact supplier modal popup
    * */
    contactSupplier() {
      if (!this.isAuthenticated) {
        $('#reg').modal('hide');
        $(`#login`).modal('show');
      } else {
        this.$store.dispatch(GET_CONTACT_SUPPLIER_PRODUCT, this.product);
        $('#contact_modal').modal('show');
      }
    },
    /*
    * method for view company profile page
    * */
    companyProfilePage() {
      if (this.companyProfile && this.companyProfile.display_name) {
        this.$router.push({name: 'company-profile', params: {display_name: this.companyProfile.display_name}})
      }
    },
    companyBasicInfo() {
      ApiService.get(`get/company/basic/info/${this.product.user_id}`).then((response) => {
        this.companyProfile = response.data;
      }).catch(() => {
        this.companyProfile = {};
      })
    },
    adCart() {
      let data = {
        product: this.product,
        variant: this.variant,
        quantity: this.quantity,
        price: this.price,
        discount_price: this.discount_price,
        available_product_qty: this.available_product_qty,
        flash_deal: this.flash_deal
      };
      this.$store.dispatch(CART_LIST, data);
      this.$toaster.success('Successfully added to cart');
    },
    /*
    * method for review submit
    * */
    reviewSubmit() {
      this.form.post(`user/review/store${this.product.id}`).then((response) => {
        this.form.review = null;
        swal.fire('Success', response.data, 'success');
        this.$store.dispatch(REVIEW_RATING_LIST, this.product.id);
      }).catch((error) => {
        swal.fire('Success', 'Invalid request try again', 'warning')
      });
    },
    /*
    * method for rating calculation
    * */
    ratingCalculation() {
      if (this.ratings) {
        let rat = this.ratings.reduce(function (sum, item) {
          return sum + Number(item.rating);
        }, 0);
        if (rat > 0) return (rat / this.ratings.length);
        return 0;
      }
      return 0;
    },
    /*
    * method for rating count by star
    * */
    ratingCount(rat) {
      if (this.ratings) {
        return this.ratings.filter((item) => {
          if (item.rating == rat) return item;
        }).length;
      }
      return 0;
    },
    /*
    * method for rating find by user id
    * */
    ratingFindByuser(user_id) {
      if (this.ratings) {
        let rat = this.ratings.filter((item) => {
          if (item.user_id == user_id) return item;
        });
        if (rat.length > 0) return rat[0].rating;
        else return 0;
      }
      return 0;
    },
    /*
    * method for reveiw count
    * */
    reviewCount() {
      if (this.reviews) {
        return this.reviews.length;
      }
      return 0;
    },
    /*
    * method for supplier phone number show
    * */
    phoneNumberShow() {
      swal.fire('Phone Number', this.company_basic.phone, 'success')
      return false;
    },
    /*
    * method for product favorite
    * */
    favorite() {
      if (!this.isAuthenticated) return;
      ApiService.post(`user-product-favorite/${this.product.id}`).then((response) => {
        this.$store.dispatch(SINGLE_PRODUCT_DETAILS, this.slug);
      });
    },
    /*
    * check favorite
    * */
    checkFavorite() {
      if (!this.isAuthenticated) return false;
      return this.product?.product_favorites?.length>0 && this.product.product_favorites.filter(item => {
        if (item.user_id == this.user.id) return item;
      }).length > 0;
    },
    /*
    * method for image url set
    * */
    showImage(e) {
      return api_base_url + e;
    },
    /*
    * method for current image change
    * */
    imageChange(img) {
      this.current_image = img;
    },
    /*
    * method for selected variant set
    * */
    variantChange(variant, index) {
      this.$set(this.variant, index, variant);
      let data = {
        'index': index,
        'variant': variant,
      };
      this.$store.dispatch(PRODUCT_VARIANT, data);
      //this.discountPriceCalculate();
      this.quantity = 1;//quantity reset for variant change
    },
    /*
    * method for quantity change
    * type =0 minus
    * type =1 plus
    * */
    quantityChange(type) {
      /*
      * if order quantity limit is enable
      * */
      if (this.product.orderQtyLimit && (this.quantity <= this.product.orderQtyLimitMin || this.quantity >= this.product.orderQtyLimitMax)) {
        if (type == 0 && (this.quantity - 1) < this.product.orderQtyLimitMin) {
          this.quantity = parseInt(this.product.orderQtyLimitMin);
          return;
        } else if (type == 1 && (this.quantity + 1) > this.product.orderQtyLimitMax) {
          this.quantity = parseInt(this.product.orderQtyLimitMax);
          return;
        }
      }
      if (type == 0 && this.quantity > 1) {
        this.quantity -= 1;
      } else if (type == 1) {
        if (this.product.stockManagement == 1 && (this.product.priceType == 0 || this.product.priceType == 2)) {
          if (this.product.quantity > this.quantity) this.quantity += 1;
        } else if (this.product.stockManagement == 1 && this.product.priceType == 1) {
          if (this.available_product_qty > this.quantity) this.quantity += 1;
        } else this.quantity += 1;
      }
      this.$store.dispatch(PRODUCT_QUANTITY_CHANGE, this.quantity);
    },
    /*
    * method for on change product qty check
    * */
    qtyChange() {
      /*
      * if order quantity limit is enable
      * */
      if (this.product.orderQtyLimit && (this.quantity < this.product.orderQtyLimitMin || this.quantity > this.product.orderQtyLimitMax)) {
        if (this.quantity < this.product.orderQtyLimitMin) {
          this.quantity = parseInt(this.product.orderQtyLimitMin);
          //return;
        } else if (this.quantity > this.product.orderQtyLimitMax) {
          this.quantity = parseInt(this.product.orderQtyLimitMax);
          //return;
        }
      }
      if (this.product.stockManagement == 1 && (this.product.priceType == 0 || this.product.priceType == 2)) {
        if (this.product.quantity < this.quantity) this.quantity = parseInt(this.product.quantity);
      } else if (this.product.stockManagement == 1 && this.product.priceType == 1) {
        if (this.available_product_qty < this.quantity) this.quantity = parseInt(this.available_product_qty);
      }
      this.$store.dispatch(PRODUCT_QUANTITY_CHANGE, this.quantity);
    },
    /*
    * method for load clicked product information
    * */
    productLoad(slug) {
      this.$router.push({name: 'single.product', params: {slug: slug}})
    },
    /*
    * method for related product price rand calculate
    * */
    relatedProductPrice(product) {
      /*if (product.flash_deal_products.length>0){
        let flash = product.flash_deal_products.filter(item=>{
          if (item.flash_deal) return item;
        });
        if (flash.length>0) {
          return flash[flash.length - 1];
        }
      }else*/
      if (product.priceType == 0) {
        return product.unit_price;
      } else if (product.priceType == 1 && product.product_stock.length > 0) {
        let max = Math.max(...product.product_stock.map(d => d.price));
        let min = Math.min(...product.product_stock.map(d => d.price));
        return min + '-' + max;
      } else if (product.priceType == 2 && product.price_variation.length > 0) {
        let max = Math.max(...product.price_variation.map(d => d.off_price));
        let min = Math.min(...product.price_variation.map(d => d.off_price));
        return min + '-' + max;
      }
    },
    /*
    * method for tear pricing and discount calculation
    * */
    tearPricingDiscount(price) {
      if (this.flash_deal) {
        if (this.flash_deal.discount_type === 'Flat') {
          return (price - this.flash_deal.discount);
        } else {
          return (price - (price * (this.flash_deal.discount / 100)));
        }
      } else if (this.product.discount_variation == 0 && this.product.discount) {
        if (this.product.discount_type === 'Flat') {
          return (price - this.product.discount) < 0 ? 0 : (price - this.product.discount);
        } else {
          return price - (price * (this.product.discount / 100));
        }
      }
    },
    handleScroll() {
      if ($(window).scrollTop() > 10) {
        $(".sidebar-filter-wrapper").css({"top": "71px", "height": "88vh"});
      } else {
        $(".sidebar-filter-wrapper").css({"top": "109px", "height": "80vh"});
      }
    },
  },
  computed: {
    ...mapGetters(["isAuthenticated", "user", "product", "variant",
      "related_products", "flash_deal","ecom_zone", "price", "discount_price", "available_product_qty",
      "company_basic", "company_details", "property_options", "attribute_options", "colors",
      "color_images", "ratings", "reviews"]),
  },
  watch: {
    product: {
      handler(newProduct) {
        this.$store.dispatch(REVIEW_RATING_LIST, newProduct.id);
        this.companyBasicInfo();
      }
    }
  }
}
</script>

<style scoped>
@media (min-width: 900px) {
  .sticky-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .make-me-sticky {

    position: -webkit-sticky;
    position: sticky;
    top: 0;

    padding: 0 15px;
  }
}

.list-group-item {
  /*height: 4px;*/
  box-shadow: inset 0 0 0 1px #ccc;
  border-radius: 3px;
  cursor: pointer;
  margin-right: 12px;
  margin-bottom: 12px;
  box-sizing: border-box;
}

.list-group-text {
  height: 20px;
  line-height: 22px;
  padding: 0 12px;
  white-space: nowrap;
}

.list-active {
  box-shadow: inset 0 0 0 2px #ff4747;
}

.list-group-item:hover {
  box-shadow: inset 0 0 0 2px #ff4747;
}

a:hover,
a:focus {
  text-decoration: none;
  outline: none;
}

.custom_button_design {
  display: inline-block;
  background: #f05931;
  padding: 5px 10px;
  margin-right: 2px;
  cursor: pointer;
  font-size: 14px;
  color: #fff;
  border-radius: 3px;
}

.tab {
  font-family: 'Barlow', sans-serif;
}

.tab .nav-tabs {
  background: linear-gradient(#e9e9e9, #fff);
  padding: 8px;
  margin: 0 0 8px;
  border: none;
  border-radius: 20px 20px 0 0;
}

.tab .nav-tabs li a {
  color: #555;
  background: #fff;
  font-size: 18px;
  font-weight: 600;
  text-transform: capitalize;
  padding: 6px 20px 8px;
  margin: 0 5px 0 0;
  border: none;
  border-radius: 10px;
  overflow: hidden;
  position: relative;
  z-index: 1;
  transition: all 0.3s ease 0.3s;
}

.tab .nav-tabs li.active a,
.tab .nav-tabs li a:hover,
.tab .nav-tabs li.active a:hover {
  color: #fff;
  background: gray;
  border: none;
}

.tab .nav-tabs li a:before {
  content: "";
  background-color: gray;
  width: 100%;
  height: 100%;
  border-radius: 10px;
  opacity: 0;
  transform: perspective(350px) rotateY(-70deg) scale(0.5);
  position: absolute;
  top: 0;
  left: 50px;
  z-index: -1;
  transition: left 0.3s ease 0s, opacity 0.3s ease 0s, transform 0.3s ease 0.2s;
}

.tab .nav-tabs li.active a:before,
.tab .nav-tabs li a:hover:before {
  opacity: 1;
  transform: perspective(350px) rotateY(0) scale(1);
  left: 0;
}

.tab .tab-content {
  color: #555;
  background: linear-gradient(#fff, #e9e9e9);
  font-size: 16px;
  letter-spacing: 1px;
  line-height: 25px;
  padding: 20px;
  border-radius: 0 0 20px 20px;
  position: relative;
}

@media only screen and (max-width: 479px) {
  .tab .nav-tabs li {
    width: 100%;
    text-align: center;
    margin: 0 0 10px;
  }

  .tab .nav-tabs li:last-child {
    margin-bottom: 0;
  }

  .tab .nav-tabs li a {
    margin: 0;
  }
}

.qty-box .input-group .form-control {
  text-align: center;
  width: 80px;
  -webkit-box-flex: unset;
  -ms-flex: unset;
  flex: unset;
}

.fav {
  border-radius: 50%;
  border: solid white;
  padding: 5px;
}

.des:hover {
  color: red;
  cursor: pointer;
}

.btn-custom {
  alignment: center;
  margin-bottom: 10px;
  text-align: center;
}

.btn-custom:hover {
  background-color: #6e6a80;
}

.btn-custom:active {
  background-color: #6e6a80;
}
</style>
