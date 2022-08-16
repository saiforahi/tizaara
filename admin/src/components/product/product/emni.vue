<template>
  <div>
    <b-overlay :show="show" rounded="sm">
      <CCard class="">
        <CCardBody>
          <form-wizard @on-complete="formSubmit" title="Add New Products" subtitle="Insert product information"
                       color="#3c4b64">
            <!-- Part - 1 -->
            <tab-content title="Product Information" icon="ti-package"
                         :before-change="validateFirstStep">

              <CInput label="Product Name :" v-model="$v.form.name.$model"
                      horizontal placeholder="Enter product name"
                      :invalidFeedback="!$v.form.name.required?  this.$t('message.emni.product_name_required'): $v.form.name.maxLength?'': this.$t('message.emni.product_name_character') "
                      :isValid="validateState('name')"/>
              <CTextarea label="Product sort description : " v-model="$v.form.sort_desc.$model"
                         horizontal placeholder="Product sort description"
                         :invalidFeedback="$v.form.sort_desc.maxLength?'': this.$t('message.emni.product_sort_description_character')"
                         :isValid="validateState('sort_desc')"
                         rows="3"/>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3">  {{$t('message.emni.select_category')}}</label>
                <div class="col-sm-9">
                  <v-select v-model="$v.form.category_id.$model" :options="category" label="name"
                            placeholder="Select product category"
                            :reduce="name => name.id" @input="loadSubcategory"
                            :state="validateState('category_id')">
                    <template #option="{ name, icon }">
                      <img :src="showImage(icon)" class="mx-2" width="18px" height="18px" alt="Category">
                      <em>{{ name }}</em>
                    </template>
                  </v-select>
                  <div class="category_error invalid_error"></div>
                </div>
              </div>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3"> {{$t('message.emni.select_subcategory')}} </label>
                <div class="col-sm-9">
                  <v-select v-model="$v.form.subcategory_id.$model" :options="subcategory" label="name"
                            placeholder="Select product sub-category"
                            :reduce="name => name.id" @input="loadSubSubcategory"
                            :state="validateState('subcategory_id')"></v-select>
                  <div class="subcategory_error invalid_error"></div>
                </div>
              </div>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3"> {{$t('message.emni.select_sub_subcategory')}} </label>
                <div class="col-sm-9">
                  <v-select v-model="$v.form.subsubcategory_id.$model" :options="subsubcategory" label="name"
                            placeholder="Select product sub-subcategory"
                            :reduce="name => name.id" @input="loadProperty"
                            :state="validateState('subsubcategory_id')"></v-select>
                  <div class="subsubcategory_error invalid_error"></div>
                </div>
              </div>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3"> {{$t('message.emni.select_brand')}} </label>
                <div class="col-sm-9">
                  <v-select v-model="form.brand_id" :options="brand" label="name"
                            placeholder="Select product brand"
                            :reduce="name => name.id">
                    <template #option="{ name, logo }">
                      <img :src="showImage(logo)" class="mx-2" width="18px" height="18px" alt="Category">
                      <em>{{ name }}</em>
                    </template>
                  </v-select>
                </div>
              </div>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3"> {{$t('message.emni.select_unit')}} </label>
                <div class="col-sm-9">
                  <v-select v-model="form.unit" :options="unit" label="name"
                            placeholder="Select product unit"
                            :reduce="name => name.id"></v-select>
                </div>
              </div>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3"> {{$t('message.emni.select_properties')}} </label>
                <div class="col-sm-9">
                  <v-select v-model="form.property" :options="property" label="name"
                            placeholder="Select Properties" @input="addNewProperty"
                            :reduce="name => name.id" multiple></v-select>
                </div>
              </div>
              <CInput v-for="addproperties in form.property_options" :label="addproperties.name+' :'"
                      v-model="addproperties.value"
                      horizontal :placeholder="'Enter '+addproperties.name+' information'"/>

            </tab-content>
            <!-- End Part - 1 -->
            <!-- Part - 2 -->
            <tab-content title="Product Images" icon="ti-image"
                         :before-change="validateSecondStep">
              <b-alert
                  variant="danger"
                  dismissible
                  fade
                  :show="secondStepAlert"
                  @dismissed="secondStepAlert=false">
                {{$t('message.emni.insert_multiple_product_thumbnail_image')}}
              </b-alert>
              <CRow class="my-3">
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t('message.emni.select_product_images')}}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.photos = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.photos = fileList}"
                          :data-images="images" popupText="Product image, you can add only 9 image"
                          idUpload="myIdUpload" editUpload="myIdEdit" idEdit="myIdEdited"
                          dragText="Drag images (many)." browseText="Select multiple image"
                          primaryText="Product Image" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          :maxImage="9" markIsPrimaryText=""
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t('message.emni.select_thumbnail_image')}}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.thumbnail_img = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.thumbnail_img = fileList[0].path}"
                          :data-images="thumbnail" popupText="Product thumbnail image, you can edit and delete"
                          idUpload="myIdUpload1" editUpload="myIdEdit1" idEdit="myIdEdited1"
                          dragText="Drag images (290x300)." browseText="Select single image"
                          primaryText="Thumbnail" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
              </CRow>
              <CRow class="my-5">
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t('message.emni.select_featured_images')}}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.featured_img = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.featured_img = fileList[0].path}"
                          :data-images="featured" popupText="Product Featured image, you can edit and delete"
                          idUpload="myIdUpload2" editUpload="myIdEdit2" idEdit="myIdEdited2"
                          dragText="Drag images (290x300)." browseText="Select single image"
                          primaryText="Featured" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t('message.emni.select_flash_deal_image')}}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.flash_deal_img = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.flash_deal_img = fileList[0].path}"
                          :data-images="flash_deal" popupText="Product Flash Deal image, you can edit and delete"
                          idUpload="myIdUpload3" editUpload="myIdEdit3" idEdit="myIdEdited3"
                          dragText="Drag images (290x300)." browseText="Select single image"
                          primaryText="Flash Deal" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
              </CRow>

              <CRow class="my-4">
                <CCol md="6">
                  <CInput
                      type="url" label="Product Videos Url:" v-model="form.video_link" max="100"
                      placeholder="Youtube / Vimeo / DailyMotion">
                    <template #append>
                      <CButton type="button" color="primary" @click="productVideo">
                        <font-awesome-icon icon="search"/>
                        {{$t('message.emni.search')}}
                      </CButton>
                    </template>
                  </CInput>
                </CCol>
                <CCol md="6">
                  <video-embed ref="youtube" src=""></video-embed>
                </CCol>
              </CRow>

            </tab-content>
            <!-- End Part - 2 -->
            <!-- Part - 3 -->
            <tab-content title="Product Price" icon="ti-money" :before-change="validateThirdStep">
              {{$t('message.emni.product_attribute')}}
              <hr>
              <CRow>
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <CButton block color="dark" disabled>
                    {{$t('message.emni.colour')}}
                  </CButton>
                </CCol>
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <v-select v-model="form.color" :options="color" label="name" @input="addNewColor"
                            placeholder="Select product colour"
                            :reduce="name => name.name" multiple>
                    <template #option="{ name, code }">
                      <div class="d-inline-block">
                        <div class="float-left mr-2" v-bind:style="{ backgroundColor:  code }"
                             style="width: 18px; height: 18px;"></div>
                        <div>{{ name }}</div>
                      </div>
                    </template>
                  </v-select>
                </CCol>
                <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <b-form-checkbox
                      id="checkbox-1"
                      v-model="color_image"
                      name="checkbox-1"
                      value="yes"
                      unchecked-value="no">
                    {{$t('message.emni.colour_with_image')}}
                  </b-form-checkbox>
                </CCol>
              </CRow>
              <CRow>
                <CCol col="0" sm="0" md="2">
                </CCol>
                <CCol col="12" sm="12" md="6">
                  <CRow>
                    <CCol col="6" sm="6" md="4" class="mb-3 mb-xl-0 mt-3" v-for="(image, index) in form.color_image"
                          :key="index">
                      <vue-upload-multiple-image class="colorImage"
                                                 @before-remove="(index, done, fileList) =>{ done(); image.image = fileList}"
                                                 @upload-success="(formData, index, fileList) =>{ image.image = fileList[0].path}"
                                                 v-bind:idUpload="'myIdUploads'+index"
                                                 v-bind:editUpload="'myIdEdits'+index"
                                                 v-bind:idEdit="'myIdEditeds'+index"
                                                 v-bind:dragText="image.name"
                                                 accept="image/jpeg,image/png,image/bmp,image/jpg"
                                                 browseText="" :multiple="false"
                                                 :showPrimary="false"
                                                 :data-images="image.imageAlfa"
                      ></vue-upload-multiple-image>
                    </CCol>
                  </CRow>
                </CCol>
              </CRow>
              <CRow class="mt-3">
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <CButton block color="dark" disabled>
                    {{$t('message.emni.attributes')}}
                  </CButton>
                </CCol>
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <v-select v-model="form.attribute" :options="attribute" label="name" @input="addNewAttribute"
                            :reduce="name => name.id" placeholder="Select product Attribute" multiple>
                  </v-select>
                </CCol>
              </CRow>
              <p class="text-info">{{$t('message.emni.note')}}</p>
              <CRow class="mt-3" v-for="addAttributes in addAttribute">
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <CButton block color="dark" disabled>{{ addAttributes.name }}</CButton>
                </CCol>
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <vue-tags-input
                      v-model="addAttributes.tag" class="w-100"
                      :tags="addAttributes.value"
                      :allow-edit-tags="true" :separators="[';', ',']"
                      @tags-changed="newTags => addAttributes.value = newTags"/>
                </CCol>
              </CRow>

              <br><br><br>{{$t('message.emni.product_discount')}}
              <hr>
              <CRow class="mb-3">
                <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  {{$t('message.emni.order_quantity_limit')}}
                </CCol>
                <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <CSwitch class="mx-1" color="primary" :checked.sync="orderQtyLimit" variant="3d"/>
                </CCol>
                <CCol v-if="orderQtyLimit" col="6" sm="4" md="3">
                  <CInput
                      placeholder="Enter maximum Order Quantity"
                      value="0"
                      type="number"
                      v-model="form.orderQtyLimitValue"
                  />
                </CCol>
              </CRow>

              <CRow>
                <CCol col="6" sm="4" md="7" class="mb-3 mb-xl-0">
                  <CSelect
                      label="Select Currency:"
                      horizontal
                      :value.sync="$v.form.currency_id.$model"
                      :options="currency"
                      placeholder="Please select Currency"
                      :invalidFeedback="$v.form.currency_id.required?'':'Currency required.' "
                      :isValid="validateState('currency_id')"
                  />
                </CCol>
              </CRow>
              <CRow>
                <CCol col="6" sm="4" md="7" class="mb-3 mb-xl-0">
                  <CInput
                      label="Tax : "
                      horizontal
                      placeholder="Enter number"
                      value="0"
                      type="number"
                      v-model="form.tax"
                  />
                </CCol>
                <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <CSelect
                      :value.sync="form.tax_type"
                      placeholder="Select tax type"
                      :options="['Flat','Percent']"
                  />
                </CCol>
              </CRow>
              <CRow v-if="!discountMethod">
                <CCol col="6" sm="4" md="7" class="mb-3 mb-xl-0">
                  <CInput
                      label="Discount : "
                      horizontal
                      placeholder="Enter number"
                      value="0"
                      type="number"
                      v-model="form.discount"
                  />
                </CCol>
                <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <CSelect
                      :value.sync="form.discount_type"
                      placeholder="Select Discount type"
                      :options="['Flat','Percent']"
                  />
                </CCol>
              </CRow>
              <CRow class="my-4">
                <CCol col="6" sm="4" md="12" class="mb-3 mb-xl-0">
                  <b-form-checkbox
                      id="checkbox-4"
                      v-model="tier_select"
                      value="discount">
                     {{$t('message.emni.enable_discount')}}
                  </b-form-checkbox>
                </CCol>
              </CRow>
              <CRow v-if="discountMethod" class="my-4">
                <CCol col="12" sm="12" md="8" class="mb-3 mb-xl-0">
                  <p class="text-right text-info font-weight-bold my-0" style="font-size: 12px;cursor: pointer"
                     @click="addTierDiscount">
                    {{$t('message.emni.add_volume_tier')}}</p>
                  <table class="table table-bordered">
                    <thead>
                    <tr>
                      <th>{{$t('message.emni.minimum_quantity')}}</th>
                      <th></th>
                      <th>{{$t('message.emni.additional_off')}}</th>
                      <th>{{$t('message.emni.action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(volume, k) in form.tierDiscount" :key="k">
                      <td>
                        <CInput
                            placeholder="Enter minimum unit"
                            value="0"
                            type="number"
                            v-model="volume.unit"
                        />
                      </td>
                      <td>
                        {{$t('message.emni.or_more')}}
                      </td>
                      <td>
                        <CInput
                            placeholder="Discount rate"
                            value="0"
                            type="number"
                            append="%"
                            v-model="volume.value"
                        />
                      </td>
                      <td>
                        <CButton color="secondary" @click="removeTierDiscount(k, volume)">
                          <font-awesome-icon icon="trash-alt"/>
                        </CButton>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </CCol>
              </CRow>


              <br><br><br>{{$t('message.emni.product_price_stock')}}
              <hr>
              <b-alert
                  variant="danger"
                  dismissible
                  fade
                  :show="showDismissibleAlert"
                  @dismissed="showDismissibleAlert=false">
                {{$t('message.emni.select_attribute')}}
              </b-alert>
              <CRow class="mt-4">
                <CCol col="12">
                  <b-form-group>
                    <b-form-radio-group id="radio-group-2" v-model="priceType" name="radio-sub-component">
                      <b-form-radio value="0">{{$t('message.emni.simple_product')}}</b-form-radio>
                      <b-form-radio value="1">{{$t('message.emni.variable_product')}}</b-form-radio>
                      <b-form-radio value="2">{{$t('message.emni.volume_tier_pricing')}}</b-form-radio>
                    </b-form-radio-group>
                  </b-form-group>
                </CCol>
              </CRow>
              <CRow class="mb-4">
                <CCol col="6" sm="4" md="12" class="mb-3 mb-xl-0">
                  <b-form-checkbox
                      id="checkbox-7"
                      v-model="form.stockManagement"
                      value="1"
                      unchecked-value="0">
                    {{$t('message.emni.stock_management')}}
                  </b-form-checkbox>
                </CCol>
              </CRow>
              <CRow v-if="(form.priceType == 0 || form.priceType == 2) && form.stockManagement == 1">
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <CInput
                      label="Quantity : "
                      horizontal
                      placeholder="Enter product quantity"
                      value="0"
                      type="number"
                      v-model="form.quantity"
                  />
                </CCol>
              </CRow>
              <CRow v-if="form.priceType == 0">
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <CInput
                      label="Price : "
                      horizontal
                      placeholder="Enter per unit price"
                      value="0"
                      type="number"
                      v-model="form.unit_price"
                  />
                </CCol>
                </CCol>
              </CRow>
              <CRow v-if="form.priceType == 0">
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <CInput
                      label="SKU : "
                      horizontal
                      placeholder="Enter product sku"
                      v-model="form.sku"
                  />
                </CCol>
                </CCol>
              </CRow>


              <table v-if="form.priceType == 1" class="table table-bordered">
                <thead>
                <tr>
                  <th>{{$t('message.emni.variant')}}</th>
                  <th>{{$t('message.emni.variant_price')}}</th>
                  <th v-if="form.stockManagement == 1">{{$t('message.emni.quantity')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="volume in form.priceMenu">
                  <td>
                    <b-badge class="p-1 mx-1" v-for="(variant, variantKey) in volume.variant" :key="variantKey" variant="secondary">{{ variant }}
                    </b-badge>
                  </td>
                  <td>
                    <CInput
                        placeholder="Enter maximum unit"
                        value="0"
                        type="number"
                        v-model="volume.variant_price"
                    />
                  </td>
                  <td v-if="form.stockManagement == 1">
                    <CInput
                        placeholder="Per unit price off"
                        value="0"
                        type="number"
                        v-model="volume.quantity"
                    />
                  </td>
                </tr>
                </tbody>
              </table>

              <CRow v-if="form.priceType == 2" class="my-4">
                <CCol col="12" sm="12" md="8" class="mb-3 mb-xl-0">
                  <p class="text-right text-info font-weight-bold my-0" style="font-size: 12px;cursor: pointer"
                     @click="addTierPrice">
                    {{$t('message.emni.add_volume_tier')}}</p>
                  <table class="table table-bordered">
                    <thead>
                    <tr>
                      <th>{{$t('message.emni.minimum_quantity')}}</th>
                      <th>{{$t('message.emni.maximum_muantity')}}</th>
                      <th>{{$t('message.emni.total_qrice')}}</th>
                      <th>{{$t('message.emni.action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(volume, k) in form.tierPrice" :key="k">
                      <td>
                        <CInput
                            placeholder="Enter minimum unit"
                            value="0"
                            type="number"
                            v-model="volume.min_unit"
                        />
                      </td>
                      <td>
                        <CInput
                            placeholder="Enter maximum unit"
                            value="0"
                            type="number"
                            v-model="volume.max_unit"
                        />
                      </td>
                      <td>
                        <CInput
                            placeholder="Per unit price off"
                            value="0"
                            type="number"
                            v-model="volume.value"
                        />
                      </td>
                      <td>
                        <CButton color="secondary" @click="removeTierPrice(k, volume)">
                          <font-awesome-icon icon="trash-alt"/>
                        </CButton>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </CCol>
              </CRow>

            </tab-content>
            <!-- End Part - 3 -->
            <!-- Part - 4 -->
            <tab-content title="Product Description" icon="ti-receipt">
              {{$t('message.emni.product_description')}}
              <hr>
              <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
              <br><br>

              <CInput label="Product Weight :" v-model="$v.form.weight.$model"
                      horizontal placeholder="Enter product weight"
                      :invalidFeedback="$v.form.weight.maxLength?'': this.$t('message.emni.product_weight_character') "
                      :isValid="validateState('weight')"/>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3"> {{$t('message.emni.tags')}} </label>
                <div class="col-sm-9">
                  <vue-tags-input
                      v-model="tag" class="w-100"
                      :tags="tags" placeholder="Enter product tags"
                      :allow-edit-tags="true" :separators="[';', ',']"
                      @tags-changed="newTags => tags = newTags"/>
                </div>
              </div>
              <div role="group" class="form-group form-row">
                <label class="col-form-label col-sm-3">{{$t('message.emni.dimension')}} </label>
                <div class="col-sm-3">
                  <CInput v-model="$v.form.length.$model"
                          placeholder="Length"
                          :invalidFeedback="$v.form.length.maxLength?'': this.$t('message.emni.not_more_character') "
                          :isValid="validateState('length')"/>
                </div>
                <div class="col-sm-3">
                  <CInput v-model="$v.form.width.$model"
                          placeholder="Width"
                          :invalidFeedback="$v.form.width.maxLength?'': this.$t('message.emni.not_more_character') "
                          :isValid="validateState('width')"/>
                </div>
                <div class="col-sm-3">
                  <CInput v-model="$v.form.height.$model"
                          placeholder="Height"
                          :invalidFeedback="$v.form.height.maxLength?'': this.$t('message.emni.not_more_character') "
                          :isValid="validateState('height')"/>
                </div>
              </div>
              <CRow form class="form-group">
                <CCol sm="3">
                  Product Type :
                </CCol>
                <CCol sm="9">
                  <b-form-group>
                    <b-form-radio-group id="radio-group-1" v-model="form.product_type" name="radio-sub">
                      <b-form-radio value="New">{{$t('message.emni.new')}}</b-form-radio>
                      <b-form-radio value="Used">{{$t('message.emni.used')}}</b-form-radio>
                    </b-form-radio-group>
                  </b-form-group>
                </CCol>
              </CRow>

              <br><br><br>{{$t('message.emni.link_product')}}
              <hr>

              <v-select label="name" :filterable="false" :options="options" @search="onSearch" @input="addNewProduct"
                        :reduce="name => name.id" placeholder="Search Product by name and sku" multiple>
                <template slot="no-options">
                  {{$t('message.emni.type_search_product_link')}}
                </template>
                <template #option="{ name, thumbnail_img }">
                  <div class="d-center">
                    <img :src="showImage(thumbnail_img)" class="mx-2" width="18px" height="18px">
                    {{ name }}
                  </div>
                </template>
                <template slot="selected-option" slot-scope="option">
                  <div class="selected d-center">
                    <img :src="showImage(option.thumbnail_img)" class="mx-2" width="18px" height="18px">
                    {{ option.name }}
                  </div>
                </template>
              </v-select>

              <br><br><br>
            </tab-content>
            <!-- End Part - 4 -->
            <!-- Part - 5 -->
            <tab-content title="Product Shipping and SEO" icon="ti-truck">
              {{$t('message.emni.product_shipping_cost')}}
              <hr>
              <CRow>
                <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <CInput
                      label="Shipping cost : "
                      horizontal
                      placeholder="Shipping cost"
                      value="0"
                      type="number"
                      v-model="form.shipping_cost"
                  />
                </CCol>
              </CRow>
              <CRow>
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  {{$t('message.emni.free_shipping')}}
                </CCol>
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <CSwitch class="mx-1" color="primary" :checked.sync="shippingSwitch" variant="3d"/>
                </CCol>
              </CRow>
              <br><br><br>{{$t('message.emni.seo_meta_tags')}}
              <hr>
              <CRow>
                <CCol col="12" sm="12" md="6" class="mb-3 mb-xl-0">
                  <CInput
                      label="Meta Title : "
                      horizontal
                      placeholder="Meta Title"
                      type="text"
                      v-model="form.meta_title"
                  />
                </CCol>
              </CRow>
              <CRow>
                <CCol col="12" sm="12" md="6" class="mb-3 mb-xl-0">
                  <CTextarea
                      v-model="form.meta_description"
                      label="Description"
                      placeholder="Product Description..."
                      horizontal
                      rows="5"
                  />
                </CCol>
              </CRow>
              <CRow class="mb-5">
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  {{$t('message.emni.meta_image')}}
                </CCol>
                <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <vue-upload-multiple-image
                      @before-remove="(index, done, fileList) =>{ done(); form.meta_img = fileList}"
                      @upload-success="(formData, index, fileList) =>{ form.meta_img = fileList[0].path}"
                      :data-images="meta_img" popupText="Product meta image, you can edit and delete"
                      idUpload="myIdUpload5" editUpload="myIdEdit5" idEdit="myIdEdited5"
                      dragText="Drag images (290x300)." browseText="Select single image"
                      primaryText="Meta Image" accept="image/jpeg,image/png,image/bmp,image/jpg"
                      markIsPrimaryText="" :multiple="false"
                  ></vue-upload-multiple-image>
                </CCol>
              </CRow>


            </tab-content>
            <!-- End Part - 5 -->
          </form-wizard>
        </CCardBody>
      </CCard>
    </b-overlay>

  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ApiService from "@/core/services/api.service";
import {api_base_url} from "@/core/config/app";
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import _ from 'lodash';

export default {
  mixins: [validationMixin],
  name: "ProductCreate",
  data() {
    return {
      form: new Form({
        name: '',
        sort_desc: '',
        added_by: 'admin',
        category_id: '',
        subcategory_id: '',
        subsubcategory_id: '',
        property: '',
        brand_id: '',
        unit: '',
        weight: '',
        length: '',
        width: '',
        height: '',
        tags: '',
        product_type: 'New',
        photos: [],
        thumbnail_img: [],
        featured_img: '',
        flash_deal_img: '',
        video_link: '',
        color: '',
        color_image: [],
        attribute: '',
        attribute_options: [],
        property_options: [],
        tax: 0,
        tax_type: 'Flat',
        discount: 0,
        discount_type: 'Flat',
        discount_variation: 0,
        tierPrice: [{
          min_unit: 1,
          max_unit: 1,
          value: 1,
        }],
        tierDiscount: [{
          unit: 1,
          value: 1,
        }],
        orderQtyLimit: 0,
        orderQtyLimitValue: 0,
        priceType: 0,
        stockManagement: 1,
        unit_price: 0,
        currency_id: '',
        quantity: 0,
        sku: '',
        variant_product: 0,
        priceMenu: [],
        description: '',
        linkProduct: '',
        meta_title: '',
        meta_description: '',
        meta_img: '',
        shipping_type: 'paid',
        shipping_cost: '',

      }),
      tier_select: '',
      tags: [],
      tag: '',
      category: [],
      color: [],
      attribute: [],
      property: [],
      unit: [],
      brand: [],
      currency: [],
      color_image: 'no',
      subcategory: [],
      subsubcategory: [],
      images: [],
      thumbnail: [],
      featured: [],
      flash_deal: [],
      meta_img: [],
      video: '',
      shippingSwitch: false,
      discountMethod: false,
      addAttribute: [],
      editor: ClassicEditor,
      editorConfig: {},
      orderQtyLimit: false,
      showDismissibleAlert: false,
      priceType: 0,
      secondStepAlert: false,
      options: [],
      show: false
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(200)
      },
      sort_desc: {
        maxLength: maxLength(500)
      },
      weight: {
        maxLength: maxLength(100)
      },
      length: {
        maxLength: maxLength(10)
      },
      width: {
        maxLength: maxLength(10)
      },
      height: {
        maxLength: maxLength(10)
      },
      category_id: {
        required,
      },
      subcategory_id: {
        required,
      },
      subsubcategory_id: {
        required,
      },
      photos: {
        required,
      },
      thumbnail_img: {
        required,
      },
      currency_id: {
        required,
      },
    }
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    showImage(e) {
      return api_base_url + e;
    },
    loadCategory() {
      let that = this;
      ApiService.get('category')
          .then(function (data) {
            that.category = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadColor() {
      let that = this;
      ApiService.get('color')
          .then(function (data) {
            that.color = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadAttribute() {
      let that = this;
      ApiService.get('attribute')
          .then(function (data) {
            that.attribute = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadUnit() {
      let that = this;
      ApiService.get('unit')
          .then(function (data) {
            that.unit = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadBrand() {
      let that = this;
      ApiService.get('brand')
          .then(function (data) {
            that.brand = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadCurrency() {
      let that = this;
      ApiService.get('currency')
          .then(function (data) {
            that.currency = _.map(data.data, function (data) {
              var pick = _.pick(data, 'id', 'name', 'symbol');
              var object = {
                value: pick.id,
                label: pick.name + ' ( ' + pick.symbol + ' ) '
              };
              return object;
            })
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadSubcategory(e) {
      let that = this;
      that.subcategory = [];
      that.form.subcategory_id = '';
      that.subsubcategory = [];
      that.form.subsubcategory_id = '';
      $('.category_error').hide();
      if (e === null) return;
      ApiService.get('subcategory/', e)
          .then(function (data) {
            that.subcategory = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadSubSubcategory(e) {
      let that = this;
      that.subsubcategory = [];
      that.form.subsubcategory_id = '';
      $('.subcategory_error').hide();
      if (e === null) return;
      ApiService.get('subsubcategory/', e)
          .then(function (data) {
            that.subsubcategory = data.data;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    loadProperty(e) {
      $('.subsubcategory_error').hide()
      let that = this;
      that.property = [];
      that.form.property = '';
      that.addproperty = [];
      if (e === null) return;
      ApiService.get('property/', e)
          .then(function (data) {
            that.property = data.data.property;
          })
          .catch(({response}) => {
            console.log(response);
          });
    },
    addNewProperty: function () {
      this.form.property_options = [];
      for (let prop in this.property) {
        if (this.form.property.includes(this.property[prop].id)) {
          this.form.property_options.push({
            name: this.property[prop].name,
            value: ''
          })
        }
      }
    },
    validateFirstStep() {
      this.$v.form.$touch();
      if (this.$v.form.name.$anyError || this.$v.form.category_id.$anyError || this.$v.form.subcategory_id.$anyError || this.$v.form.subsubcategory_id.$anyError) {
        this.$v.form.category_id.$anyError ? $('.category_error').show().text('Category name required') : '';
        this.$v.form.subcategory_id.$anyError ? $('.subcategory_error').show().text('Subcategory name required') : '';
        this.$v.form.subsubcategory_id.$anyError ? $('.subsubcategory_error').show().text('Sub Subcategory name required') : '';
        return false
      }
      this.form.tags = '';
      for (let i = 0; i < this.tags.length; i++) {
        if (i === this.tags.length - 1)
          this.form.tags += this.tags[i].text;
        else
          this.form.tags += this.tags[i].text + ",";
      }
      return true;
    },
    validateSecondStep() {
      this.$v.form.$touch();
      if (this.$v.form.photos.$anyError || this.$v.form.thumbnail_img.$anyError) {
        this.secondStepAlert = true;
        return false;
      } else {
        this.secondStepAlert = false;
        return true;
      }
    },
    validateThirdStep() {
      this.$v.form.$touch();
      if (this.$v.form.currency_id.$anyError) {
        return false;
      } else {
        return true;
      }
    },
    productVideo() {
      this.$refs.youtube.src = this.form.video_link;
    },
    addNewAttribute: function () {
      this.addAttribute = [];
      for (let prop in this.attribute) {
        if (this.form.attribute.includes(this.attribute[prop].id)) {
          this.addAttribute.push({
            name: this.attribute[prop].name,
            tag: '',
            value: []
          })
        }
      }
    },
    addNewColor: function () {
      this.form.color_image = [];
      let that = this;
      setTimeout(() => {
        if (this.color_image === 'yes') {
          for (let prop in that.form.color) {
            that.form.color_image.push({
              name: that.form.color[prop],
              image: '',
              imageAlfa: [],
            })
          }
        }
      }, 1000);
    },
    addTierPrice() {
      this.form.tierPrice.push({
        min_unit: 1,
        max_unit: 1,
        value: 1,
      })
    },
    removeTierPrice(index, invoice_product) {
      var idx = this.form.tierPrice.indexOf(invoice_product);
      if (idx > -1) {
        this.form.tierPrice.splice(idx, 1);
      }
    },
    addTierDiscount() {
      this.form.tierDiscount.push({
        unit: 1,
        value: 1,
      })
    },
    removeTierDiscount(index, invoice_product) {
      var idx = this.form.tierDiscount.indexOf(invoice_product);
      if (idx > -1) {
        this.form.tierDiscount.splice(idx, 1);
      }
    },
    priceList() {
      let data = [];
      this.form.attribute_options = [];
      if (this.form.color.length > 0) data.push(this.form.color);
      for (let prop in this.addAttribute) {
        if (this.addAttribute[prop].value.length > 0) {
          var value = [];
          for (let id in this.addAttribute[prop].value) {
            value.push(this.addAttribute[prop].value[id].text);
          }
          this.form.attribute_options.push({
            'name': this.addAttribute[prop].name,
            'value': value
          })
          data.push(value);
        }
      }

      let result = [];
      let finalResult = [];
      if (data.length > 0) {
        result = this.getCombn(data);
      }
      if (result.length > 0) {
        for (var original_result of result) {
          var makeArray = original_result.split(",");
          var arrFiltered = makeArray.filter(el => {
            return el != null && el != '';
          });
          finalResult.push({
            'variant': arrFiltered,
            'variant_price': this.form.unit_price,
            'quantity': this.form.quantity,
          })
        }
      }
      this.form.priceMenu = finalResult;
      if (this.form.priceMenu.length > 0) this.form.variant_product = 1;
      else this.form.variant_product = 0;

    },
    getCombn(data) {
      var result = [];
      data.forEach(function (item, index) {
        if (index === 0) {
          for (let color of item) {
            result.push(color);
          }
        } else {
          var tmp = [];
          for (let k = 0; k < result.length; k++) {
            for (let color of item) {
              var newArray = [];
              newArray[index] = color;
              tmp.push(result[k].concat(newArray));
            }
          }
          result = tmp;
        }
      });
      return result;
    },
    formSubmit: function () {
      this.show = true;
      this.form.post('product')
          .then((e) => {
            this.show = false;
            swal.fire({
              title: this.$t('message.emni.product_upload_successfully'),
              text: this.$t('message.emni.product_upload_successfully'),
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Ok'
            }).then((result) => {
              this.$router.push({name: "In House Products"});
            })
          })
          .catch((error) => {
            this.show = false;
            let data = error.response;
            if (data.status === 422) {
              let allData = '', mainData = '';
              $.each(data.data.errors, function (key, value) {
                if ($.isPlainObject(value)) {
                  $.each(value, function (key, value) {
                    allData += value + "<br/>";
                  });
                } else {
                  mainData += value + "<br/>";
                }
              });
              swal.fire({
                title: 'Warning',
                html: mainData,
                type: 'error'
              })
            } else {
              swal.fire({
                title: this.$t("message.common.some_wrong"),
                text: this.$t("message.emni.input_data_problem"),
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              }).then((result) => {
                this.$router.push({name: "In House Products"});
              })
            }
          })
    },
    addNewProduct(e) {
      this.form.linkProduct = e;
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      let url = 'product-search?searchProduct=' + search;
      ApiService.get(url)
          .then(res => {
            //console.log(res.data);
            vm.options = res.data;
            loading(false);
          });
    }, 350)
  },

  created() {
    this.loadCategory();
    this.loadBrand();
    this.loadColor();
    this.loadAttribute();
    this.loadUnit();
    this.loadCurrency();
    this.form.sku = btoa(Date.now() + Math.floor(Math.random() * 999)).replace(/[^a-zA-Z ]/g, "").toUpperCase();
  },
  components: {
    VueUploadMultipleImage
  },
  computed: {
    price() {
      return this.form.color;
    }
    ,

  },
  watch: {
    tier_select: function (val) {
      if (val === 'discount') {
        this.form.discount_variation = 1;
        this.discountMethod = true;
      } else {
        this.form.discount_variation = 0;
        this.discountMethod = false;
      }
    },
    shippingSwitch: function (val) {
      val ? this.form.shipping_type = 'free' : this.form.shipping_type = 'paid';
    },
    orderQtyLimit: function (val) {
      val ? this.form.orderQtyLimit = 1 : this.form.orderQtyLimit = 0;
    },
    priceType: function (val) {
      if (val == 1) {
        if (this.form.priceMenu.length < 1) {
          this.showDismissibleAlert = true;
        }
        this.form.priceType = 1;
      } else if (val == 2) {
        this.form.priceType = 2;
      } else {
        this.form.priceType = 0;
      }
    },
    color_image: function (val) {
      switch (val) {
        case 'no':
          this.form.color_image = [];
          break;
        default:
          this.form.color_image = [];
          this.addNewColor();
          break;
      }
    },
    addAttribute: {
      deep: true,
      handler() {
        this.priceList();
      }
    },
    price() {
      this.priceList();
    }

  },
  mounted() {

  }
}
</script>

<style scoped>

</style>
