<template>
  <div class="row justify-content-center">
    <div class="col-12 col-md-11 m-4">
      <b-card>
        <b-card-body>
          <form-wizard @on-complete="formSubmit" :title="`Edit ${form.name}`" subtitle="Update product information"
                       color="#3c4b64">
            <!-- Part - 1 -->
            <tab-content title="Product Information" icon="ti-package" :before-change="validateFirstStep">

              <div class="card" style="background-color: #dae2ed;">
                <div class="card-body" style="padding: 12px">
                  <b-row>
                    <b-col>
                      <b-form-select v-model="form.category_id" :options="Object.values(categoryList)" value-field="id"
                                     text-field="name" :select-size="18" class="cat-select-design"></b-form-select>
                    </b-col>
                    <b-col>
                      <b-form-select v-if="subcategory.length > 0" v-model="form.subcategory_id" :options="subcategory"
                                     :select-size="18" value-field="id" text-field="name" class="cat-select-design"></b-form-select>
                    </b-col>
                    <b-col>
                      <b-form-select v-if="subsubcategory.length > 0" v-model="form.subsubcategory_id"
                                     :options="subsubcategory" :select-size="18" value-field="id"
                                     text-field="name" class="cat-select-design"></b-form-select>
                    </b-col>
                  </b-row>
                </div>
                <div class="card-footer" style="font-size: 13px">
                  {{ $t("message.product_created.categories_selected") }}
                  {{ catNameShow(form.category_id, 'category') }} {{ catNameShow(form.subcategory_id, 'subcategory') }}
                  {{ catNameShow(form.subsubcategory_id, 'sub-subcategory') }}
                  <i v-if="cat_valid" class="fas fa-check-circle text-success" style="font-size: 15px"></i>
                </div>
              </div>
              <b-row class="my-3">
                <b-col sm="3">
                  <label for="input-small">{{ $t("message.product_created.product_name") }}</label>
                </b-col>
                <b-col sm="9">
                  <b-form-input id="input-small" size="sm" :placeholder= "$t('message.product_created.enter_product_name')"
                                v-model="$v.form.name.$model"
                                :state="validateState('name')" aria-describedby="input-1-live-feedback">
                  </b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.name.required" id="input-1-live-feedback">{{ $t("message.product_created.product_name_required") }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-if="!$v.form.name.maxLength" id="input-1-live-feedback">{{ $t("message.product_created.product_name_character") }}
                  </b-form-invalid-feedback>
                </b-col>
              </b-row>
              <b-row class="my-3">
                <b-col sm="3">
                  <label for="sort-desc">{{ $t("message.product_created.product_sort_description") }} :</label>
                </b-col>
                <b-col sm="9">
                  <b-form-textarea id="sort-desc" size="sm" :placeholder= "$t('message.product_created.product_sort_description')"
                                   v-model="$v.form.sort_desc.$model" rows="3"
                                   :state="validateState('sort_desc')" aria-describedby="input-2-live-feedback">
                  </b-form-textarea>
                  <b-form-invalid-feedback v-if="!$v.form.sort_desc.required" id="input-2-live-feedback">
                    {{ $t("message.product_created.product_sort_500_character") }}
                  </b-form-invalid-feedback>
                </b-col>
              </b-row>
              <b-row class="my-3">
                <b-col sm="3">
                  <label for="brand"> {{ $t("message.product_created.select_brand") }} </label>
                </b-col>
                <b-col sm="9">
                  <v-select v-model="form.brand_id" :options="Object.values(brandList)" label="name"
                            :placeholder= "$t('message.product_created.select_product_brand')" id="brand"
                            :reduce="name => name.id">
                    <template #option="{ name, logo }">
                      <img :src="showImage(logo)" class="mx-2" width="18px" height="18px" alt="Category">
                      <em>{{ name }}</em>
                    </template>
                  </v-select>
                </b-col>
              </b-row>
              <b-row class="my-3">
                <b-col sm="3">
                  <label for="unit">{{ $t("message.product_created.select_unit") }} </label>
                </b-col>
                <b-col sm="9">
                  <v-select v-model="form.unit_id" :options="Object.values(unitList)" label="name"
                            :placeholder= "$t('message.product_created.select_product_unit')"
                            :reduce="name => name.id"></v-select>
                </b-col>
              </b-row>
              <b-row v-for="(keyword, j) in form.product_keywords" :key="`keyword-${j}`">
                <b-col sm="3">
                  <label v-if="j === 0"> Keyword : </label>
                </b-col>
                <b-col sm="8">
                  <b-form-input class="mb-3" v-model="keyword.key_name" :placeholder= "$t('message.product_created.enter_product_keyword')"
                                required></b-form-input>
                </b-col>
                <b-col sm="1">
                  <b-button size="sm" color="secondary" @click="removeKeyword(j, keyword)">
                    <font-awesome-icon icon="trash-alt"/>
                  </b-button>
                </b-col>
              </b-row>
              <p class="text-info font-weight-bold my-2" style="font-size: 12px;cursor: pointer"
                 @click="addMoreKeyword">
                {{ $t("message.product_created.add_more_keyword") }} </p>
              <b-row v-for="(property, k) in form.property_options" :key="k">
                <b-col sm="3">
                  <b-form-input v-model="property.label" :placeholder= "$t('message.product_created.enter_property_label')"
                                :disabled="property.type != 0" required></b-form-input>
                </b-col>
                <b-col sm="8">
                  <b-form-input class="mb-3" v-model="property.value" :placeholder= "$t('message.product_created.enter_property_value')"
                                required></b-form-input>
                </b-col>
                <b-col sm="1">
                  <b-button size="sm" color="secondary" @click="removeProperty(k, property)">
                    <font-awesome-icon icon="trash-alt"/>
                  </b-button>
                </b-col>
              </b-row>
              <p class="text-info font-weight-bold my-2" style="font-size: 12px;cursor: pointer"
                 @click="addMoreProperty">
                {{ $t("message.product_created.add_more_property") }}</p>

            </tab-content>
            <!-- End Part - 1 -->
            <!-- Part - 2 -->
            <tab-content title="Product Images" icon="ti-image" :before-change="validateSecondStep">
              <b-alert
                  variant="danger"
                  dismissible
                  fade
                  :show="secondStepAlert"
                  @dismissed="secondStepAlert=false">
                {{ $t("message.product_created.insert_multiple_product") }}
              </b-alert>

              <b-row class="my-3">
                <b-col md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{ $t("message.product_created.select_product_images") }}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.photos = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.photos = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.photos = fileList}"
                          :data-images="images" popupText="Product image, you can add only 9 image"
                          idUpload="myIdUpload" editUpload="myIdEdit" idEdit="myIdEdited"
                          dragText="Drag images (many)." browseText="Select multiple image"
                          primaryText="Product Image" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          :maxImage="9" markIsPrimaryText=""
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </b-col>
                <b-col md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{ $t("message.product_created.select_thumbnail_image") }}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.thumbnail_img = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.thumbnail_img = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.thumbnail_img = fileList}"
                          :data-images="thumbnail" popupText="Product thumbnail image, you can edit and delete"
                          idUpload="myIdUpload1" editUpload="myIdEdit1" idEdit="myIdEdited1"
                          dragText="Drag images (290x300)." browseText="Select single image"
                          primaryText="Thumbnail" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </b-col>
              </b-row>
              <b-row class="my-5">
                <b-col md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{ $t("message.product_created.select_featured_images") }}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.featured_img = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.featured_img = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.featured_img = fileList}"
                          :data-images="featured" popupText="Product Featured image, you can edit and delete"
                          idUpload="myIdUpload2" editUpload="myIdEdit2" idEdit="myIdEdited2"
                          dragText="Drag images (290x300)." browseText="Select single image"
                          primaryText="Featured" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </b-col>
                <b-col md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{ $t("message.product_created.select_flash_deal_image") }}</label>
                      <vue-upload-multiple-image
                          @before-remove="(index, done, fileList) =>{ done(); form.flash_deal_img = fileList}"
                          @upload-success="(formData, index, fileList) =>{ form.flash_deal_img = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.flash_deal_img = fileList}"
                          :data-images="flash_deal" popupText="Product Flash Deal image, you can edit and delete"
                          idUpload="myIdUpload3" editUpload="myIdEdit3" idEdit="myIdEdited3"
                          dragText="Drag images (290x300)." browseText="Select single image"
                          primaryText="Flash Deal" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </b-col>
              </b-row>

              <b-row class="my-4">
                <b-col md="6">
                  <label for="video-upload">{{ $t("message.product_created.product_videos_url") }}</label>
                  <b-input-group>
                    <b-form-input
                        id="video-upload" type="url"
                        v-model="form.video_link" max="100"
                        :placeholder= "$t('message.product_created.youtube_vimeo_dailyMotion')"
                    ></b-form-input>
                    <b-input-group-append>
                      <b-button variant="primary" class="btn-sm" @click="productVideo">
                        <font-awesome-icon icon="search"/>
                        Search
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                </b-col>
                <b-col md="6">
                  <video-embed ref="youtube" src=""></video-embed>
                </b-col>
              </b-row>

            </tab-content>
            <!-- End Part - 2 -->
            <!-- Part - 3 -->
            <tab-content title="Product Price" icon="ti-money" :before-change="validateThirdStep">
              {{ $t("message.product_created.product_attribute") }}
              <hr>
              <b-row>
                <b-col cols="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <b-button block color="dark" size="sm" disabled>
                    {{ $t("message.product_created.colour") }}
                  </b-button>
                </b-col>
                <b-col cols="6" size="sm" sm="4" md="6" class="mb-3 mb-xl-0">
                  <v-select v-model="form.color" :options="Object.values(colorList)" label="name"
                            @input="selectColour"
                            :placeholder= "$t('message.product_created.select_product_colour')"
                            :reduce="name => name.name" multiple>
                    <template #option="{ name, code }">
                      <div class="d-inline-block">
                        <div class="float-left mr-2" v-bind:style="{ backgroundColor:  code }"
                             style="width: 18px; height: 18px;"></div>
                        <div>{{ name }}</div>
                      </div>
                    </template>
                  </v-select>
                </b-col>
                <b-col cols="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <b-form-checkbox
                      id="checkbox-1"
                      v-model="form.color_type"
                      name="checkbox-1"
                      value="1"
                      unchecked-value="0">
                    {{ $t("message.product_created.colour_with_image") }}
                  </b-form-checkbox>
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="0" sm="0" md="2">
                </b-col>
                <b-col cols="12" sm="12" md="6">
                  <b-row>
                    <b-col cols="6" sm="6" md="4" class="mb-3 mb-xl-0 mt-3" v-for="(image, index) in form.color_image"
                           :key="index">
                      <vue-upload-multiple-image class="colorImage"
                                                 @before-remove="(index, done, fileList) =>{ done(); image.image = fileList}"
                                                 @upload-success="(formData, index, fileList) =>{ image.image = fileList}"
                                                 @edit-image="(formData, index, fileList) =>{ image.image = fileList}"
                                                 v-bind:idUpload="'myIdUploads'+index"
                                                 v-bind:editUpload="'myIdEdits'+index"
                                                 v-bind:idEdit="'myIdEditeds'+index"
                                                 v-bind:dragText="image.name"
                                                 accept="image/jpeg,image/png,image/bmp,image/jpg"
                                                 browseText="" :multiple="false"
                                                 :showPrimary="false"
                                                 :data-images="image.imageAlfa"
                      ></vue-upload-multiple-image>
                    </b-col>
                  </b-row>
                </b-col>
              </b-row>

              <b-row class="mt-3">
                <b-col cols="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <b-button size="sm" block color="dark" disabled>
                    {{ $t("message.product_created.attributes") }}
                  </b-button>
                </b-col>
                <b-col cols="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <v-select v-model="form.attribute" :options="Object.values(attributeList)" label="name"
                            @input="selectAttribute"
                            :reduce="name => name.name" :placeholder= "$t('message.product_created.select_product_attribute')" multiple>
                  </v-select>
                </b-col>
              </b-row>

              <p class="text-info">{{ $t("message.product_created.note_attributes") }}</p>
              <b-row class="mt-3" v-for="(addAttributes, index) in form.attribute_options" :key="index">
                <b-col cols="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <b-button block color="dark" disabled>{{ addAttributes.name }}</b-button>
                </b-col>
                <b-col cols="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <vue-tags-input
                      v-model="addAttributes.tag || ''" class="w-100"
                      :tags="addAttributes.value"
                      :allow-edit-tags="true" :separators="[';', ',']"
                      @tags-changed="(newTags) => {addAttributes.value = newTags; priceList()}"/>
                </b-col>
              </b-row>

              <br><br><br>{{ $t("message.product_created.product_tax") }}
              <hr>
              <b-row v-if="form.priceType == 0">
                <b-col cols="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="SKU : "
                      label-for="sku">
                    <b-form-input v-model="form.sku" size="sm"
                                  :placeholder= "$t('message.product_created.enter_product_sku')" id="sku" readonly disabled></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row class="mb-3">
                <b-col cols="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <b-form-checkbox v-model="orderQtyLimit" name="check-button" switch>
                    {{ $t("message.product_created.order_quantity_limit") }}
                  </b-form-checkbox>
                </b-col>
                <b-col v-if="form.orderQtyLimit === 1" cols="6" sm="4" md="3">
                  <b-form-input v-model="form.orderQtyLimitMin" :placeholder= "$t('message.product_created.enter_minimum_order_quantity')"
                                type="number" min="1"></b-form-input>
                </b-col>
                <b-col v-if="form.orderQtyLimit === 1" cols="6" sm="4" md="3">
                  <b-form-input v-model="form.orderQtyLimitMax" :placeholder= "$t('message.product_created.enter_maximum_order_quantity')"
                                type="number" min="1"></b-form-input>
                </b-col>
              </b-row>

<!--              <b-row class="my-2">
                <b-col cols="6" sm="4" md="7" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Select Currency* :"
                      label-for="select-currency">
                    <b-form-select v-model="$v.form.currency_id.$model" :options="Object.values(currencyList)" size="sm"
                                   :placeholder= "$t('message.product_created.please_select_currency')" value-field="id"
                                   text-field="name" id="select-currency"
                                   :state="validateState('currency_id')"></b-form-select>
                    <b-form-invalid-feedback v-if="!$v.form.currency_id.required">
                      {{ $t("message.product_created.currency_required") }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </b-col>
              </b-row>-->
              <b-row class="my-2">
                <b-col cols="6" sm="4" md="7" class="mb-3 mb-xl-0">
                  <b-form-group
                      id="fieldset-horizontal"
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Tax :"
                      label-for="select-tax">
                    <b-form-input v-model="form.tax" size="sm" type="number" min="0"
                                  value="0" :placeholder= "$t('message.product_created.enter_number')" id="select-tax"></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col cols="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <b-form-select v-model="form.tax_type" :options="['Flat','Percent']" size="sm"
                                 :placeholder= "$t('message.product_created.select_tax_type')"></b-form-select>
                </b-col>
              </b-row>
              <b-row class="my-2" v-if="form.discount_variation == 0">
                <b-col cols="6" sm="4" md="7" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Discount : "
                      label-for="select-tax">
                    <b-form-input v-model="form.discount" size="sm" type="number" min="0"
                                  value="0" :placeholder= "$t('message.product_created.enter_number')" id="select-tax"></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col cols="6" sm="4" md="3" class="mb-3 mb-xl-0">
                  <b-form-select v-model="form.discount_type" :options="['Flat','Percent']" size="sm"
                                 :placeholder= "$t('message.product_created.select_discount_type')"></b-form-select>
                </b-col>
              </b-row>
              <b-row class="my-4">
                <b-col cols="6" sm="4" md="12" class="mb-3 mb-xl-0">
                  <b-form-checkbox
                      id="checkbox-4"
                      v-model="form.discount_variation"
                      value="1" unchecked-value="0">
                    {{ $t("message.product_created.enable_volume_tier_discount") }}
                  </b-form-checkbox>
                </b-col>
              </b-row>

              <b-row v-if="form.discount_variation == 1" class="my-4">
                <b-col cols="12" sm="12" md="8" class="mb-3 mb-xl-0">
                  <p class="text-right text-info font-weight-bold my-0" style="font-size: 12px;cursor: pointer"
                     @click="addTierDiscount">
                    {{ $t("message.product_created.add_volume_tier") }}</p>
                  <table class="table table-bordered">
                    <thead>
                    <tr>
                      <th>{{ $t("message.product_created.minimum_quantity") }}</th>
                      <th></th>
                      <th>{{ $t("message.product_created.additional_off") }}</th>
                      <th>{{ $t("message.product_created.action") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(volume, k) in form.tierDiscount" :key="k">
                      <td>
                        <b-form-input v-model="volume.min_qty" size="sm" type="number" min="0"
                                      value="0" :placeholder= "$t('message.product_created.enter_minimum_unit')" ></b-form-input>
                      </td>
                      <td>
                        {{ $t("message.product_created.or_more") }}
                      </td>
                      <td>
                        <b-input-group size="sm" append="%">
                          <b-form-input v-model="volume.percent_off" size="sm" type="number" min="0"
                                        value="0" :placeholder= "$t('message.product_created.discount_rate')"></b-form-input>
                        </b-input-group>
                      </td>
                      <td>
                        <b-button color="secondary" @click="removeTierDiscount(k, volume)">
                          <font-awesome-icon icon="trash-alt"/>
                        </b-button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </b-col>
              </b-row>
              <br><br><br>{{ $t("message.product_created.product_price_and_stock") }}
              <hr>
              <b-row class="mt-4">
                <b-col cols="12">
                  <b-form-group>
                    <b-form-radio-group id="radio-group-2" v-model="form.priceType" name="radio-sub-component">
                      <b-form-radio value="0">{{ $t("message.product_created.simple_product") }}</b-form-radio>
                      <b-form-radio value="1">{{ $t("message.product_created.variable_product") }}</b-form-radio>
                      <b-form-radio value="2">{{ $t("message.product_created.volume_tier_pricing") }}</b-form-radio>
                    </b-form-radio-group>
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row class="mb-4">
                <b-col cols="6" sm="4" md="12" class="mb-3 mb-xl-0">
                  <b-form-checkbox
                      id="checkbox-7"
                      v-model="form.stockManagement"
                      value="1"
                      unchecked-value="0">
                    {{ $t("message.product_created.stock_management") }}
                  </b-form-checkbox>
                </b-col>
              </b-row>
              <b-row v-if="(form.priceType == 0 || form.priceType == 2) && form.stockManagement == 1">
                <b-col cols="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Quantity : "
                      label-for="quantity">
                    <b-form-input v-model="form.quantity" size="sm" type="number" min="0"
                                  value="0" :placeholder= "$t('message.product_created.enter_product_quantity')" id="quantity"></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row v-if="form.priceType == 0">
                <b-col cols="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Price : "
                      label-for="price">
                    <b-form-input v-model="form.unit_price" size="sm" type="number" min="0"
                                  value="0" :placeholder= "$t('message.product_created.enter_per_unit_price')" id="price"></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
              <table v-if="form.priceType == 1" class="table table-bordered">
                <thead>
                <tr>
                  <th>{{ $t("message.product_created.variant") }}</th>
                  <th>{{ $t("message.product_created.variant_price") }}</th>
                  <th v-if="form.stockManagement == 1">{{ $t("message.product_created.quantity") }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(volume, index) in form.priceMenu" :key="index">
                  <td>
                    <b-badge class="p-1 mx-1" v-for="(variant, variantKey) in volume.variant" :key="variantKey" variant="secondary">{{ variant }}
                    </b-badge>
                  </td>
                  <td>
                    <b-form-input v-model="volume.price" size="sm" type="number" min="0"
                                  value="0" :placeholder= "$t('message.product_created.enter_maximum_unit')"></b-form-input>
                  </td>
                  <td v-if="form.stockManagement == 1">
                    <b-form-input v-model="volume.qty" size="sm" type="number" min="0"
                                  value="0" :placeholder= "$t('message.product_created.per_unit_price_off')"></b-form-input>
                  </td>
                </tr>
                </tbody>
              </table>
              <b-row v-if="form.priceType == 2" class="my-4">
                <b-col cols="12" sm="12" md="8" class="mb-3 mb-xl-0">
                  <p class="text-right text-info font-weight-bold my-0" style="font-size: 12px;cursor: pointer"
                     @click="addTierPrice">
                    {{ $t("message.product_created.add_volume_tier") }}</p>
                  <table class="table table-bordered">
                    <thead>
                    <tr>
                      <th>{{ $t("message.product_created.minimum_quantity") }}</th>
                      <th>{{ $t("message.product_created.maximum_quantity") }}</th>
                      <th>{{ $t("message.product_created.total_price") }}</th>
                      <th>{{ $t("message.product_created.action") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(volume, k) in form.tierPrice" :key="k">
                      <td>
                        <b-form-input v-model="volume.min_qty" size="sm" type="number" min="0"
                                      value="0" :placeholder= "$t('message.product_created.enter_minimum_unit')"></b-form-input>
                      </td>
                      <td>
                        <b-form-input v-model="volume.max_qty" size="sm" type="number" min="0"
                                      value="0" :placeholder= "$t('message.product_created.enter_maximum_unit')"></b-form-input>
                      </td>
                      <td>
                        <b-form-input v-model="volume.off_price" size="sm" type="number" min="0"
                                      value="0" :placeholder= "$t('message.product_created.per_unit_price_off')"></b-form-input>
                      </td>
                      <td>
                        <b-button color="secondary" @click="removeTierPrice(k, volume)">
                          <font-awesome-icon icon="trash-alt"/>
                        </b-button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </b-col>
              </b-row>

            </tab-content>
            <!-- End Part - 3 -->
            <!-- Part - 4 -->
            <tab-content title="Product Description" icon="ti-receipt" :before-change="validateForthStep">
              {{ $t("message.product_created.product_description") }}
              <hr>
              <b-form-group
                  label-cols-sm="4"
                  label-cols-lg="3"
                  label="Product Weight :"
                  label-for="product-weight">
                <b-form-input v-model="$v.form.weight.$model" size="sm" :state="validateState('weight')"
                              :placeholder= "$t('message.product_created.enter_product_weight')" id="product-weight"></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.weight.maxLength">
                  {{ $t("message.product_created.product_weight_100_character") }}
                </b-form-invalid-feedback>
              </b-form-group>
              <div role="group" class="form-group my-3 form-row">
                <label class="col-form-label col-sm-3"> {{ $t("message.product_created.tags") }} </label>
                <div class="col-sm-9">
                  <vue-tags-input
                      v-model="tag" class="w-100"
                      :tags="tags" :placeholder= "$t('message.product_created.enter_product_tags')"
                      :allow-edit-tags="true" :separators="[';', ',']"
                      @tags-changed="newTags => tags = newTags"/>
                </div>
              </div>
              <div role="group" class="form-group my-3 form-row">
                <label class="col-form-label col-sm-3">{{ $t("message.product_created.dimension") }}</label>
                <div class="col-sm-3">
                  <b-form-input v-model="$v.form.length.$model" size="sm" :state="validateState('length')"
                                :placeholder= "$t('message.product_created.length')"></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.length.maxLength">
                    {{ $t("message.product_created.not_10_character") }}
                  </b-form-invalid-feedback>
                </div>
                <div class="col-sm-3">
                  <b-form-input v-model="$v.form.width.$model" size="sm" :state="validateState('width')"
                                :placeholder= "$t('message.product_created.width')"></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.width.maxLength">
                    {{ $t("message.product_created.not_10_character") }}
                  </b-form-invalid-feedback>
                </div>
                <div class="col-sm-3">
                  <b-form-input v-model="$v.form.height.$model" size="sm" :state="validateState('height')"
                                :placeholder= "$t('message.product_created.height')"></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.height.maxLength">
                    {{ $t("message.product_created.not_10_character") }}
                  </b-form-invalid-feedback>
                </div>
              </div>
              <b-row form class="form-group">
                <b-col sm="3">
                  {{ $t("message.product_created.product_type") }}
                </b-col>
                <b-col sm="9">
                  <b-form-group>
                    <b-form-radio-group id="radio-group-1" v-model="form.product_type" name="radio-sub">
                      <b-form-radio value="New">{{ $t("message.product_created.new") }}</b-form-radio>
                      <b-form-radio value="Used">{{ $t("message.product_created.used") }}</b-form-radio>
                    </b-form-radio-group>
                  </b-form-group>
                </b-col>
              </b-row>
              <br><br>
              <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
              <br><br>



            </tab-content>
            <!-- End Part - 4 -->
            <!-- Part - 5 -->
            <tab-content title="Product Shipping and SEO" icon="ti-truck">
              {{ $t("message.product_created.product_shipping_cost") }}
              <hr>
              <loading :active.sync="visible" :can-cancel="true"></loading>
              <b-row class="my-3">
                <b-col cols="6" sm="4" md="4" class="mb-3 mb-xl-0">
                  <b-form-checkbox v-model="shippingSwitch" name="check-button" switch>
                    {{ $t("message.product_created.free_shipping") }}
                  </b-form-checkbox>
                </b-col>
              </b-row>
              <b-row class="my-3">
                <b-col cols="6" sm="4" md="6" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Shipping cost : "
                      label-for="shipping-cost">
                    <b-form-input v-model="form.shipping_cost" size="sm" type="number" min="0"
                                  :disabled="shippingSwitch"
                                  value="0" :placeholder= "$t('message.product_created.shipping_cost')" id="shipping-cost"></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
              <br><br><br>{{ $t("message.product_created.seo_meta_tags") }}
              <hr>
              <b-row>
                <b-col cols="12" sm="12" md="6" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Meta Title : "
                      label-for="meta-title">
                    <b-form-input v-model="form.meta_title" size="sm"
                                  :placeholder= "$t('message.product_created.meta_title')" id="meta-title"></b-form-input>
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="12" sm="12" md="6" class="mb-3 mb-xl-0">
                  <b-form-group
                      label-cols-sm="4"
                      label-cols-lg="3"
                      label="Description : "
                      label-for="meta-title">
                    <b-form-textarea v-model="form.meta_description" size="sm" rows="5"
                                     :placeholder= "$t('message.product_created.product_descriptions')" id="meta-title"></b-form-textarea>
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row class="mb-5">
                <b-col cols="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  {{ $t("message.product_created.meta_image") }}
                </b-col>
                <b-col cols="6" sm="4" md="2" class="mb-3 mb-xl-0">
                  <vue-upload-multiple-image
                      @before-remove="(index, done, fileList) =>{ done(); form.meta_img = fileList}"
                      @upload-success="(formData, index, fileList) =>{ form.meta_img = fileList}"
                      @edit-image="(formData, index, fileList) =>{ form.meta_img = fileList}"
                      :data-images="meta_img" popupText="Product meta image, you can edit and delete"
                      idUpload="myIdUpload5" editUpload="myIdEdit5" idEdit="myIdEdited5"
                      dragText="Drag images (290x300)." browseText="Select single image"
                      primaryText="Meta Image" accept="image/jpeg,image/png,image/bmp,image/jpg"
                      markIsPrimaryText="" :multiple="false"
                  ></vue-upload-multiple-image>
                </b-col>
              </b-row>
            </tab-content>
            <!-- End Part - 5 -->
          </form-wizard>
        </b-card-body>
      </b-card>
    </div>
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import {api_base_url} from "@/core/config/app";
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {SUBSUBCATEGORY_LIST} from "@/core/services/store/module/subsubcategory";
import {SUBCATEGORY_LIST} from "@/core/services/store/module/subcategory";
import {CATEGORY_LIST} from "@/core/services/store/module/category";
import {mapGetters} from "vuex";
import {BRAND_LIST} from "@/core/services/store/module/brand";
import {UNIT_LIST} from "@/core/services/store/module/unit";
import {PROPERTY_LIST} from "@/core/services/store/module/property";
import {COLOR_LIST} from "@/core/services/store/module/color";
import {ATTRIBUTE_LIST} from "@/core/services/store/module/attribute";
import {CURRENCY_LIST} from "@/core/services/store/module/currency";
import ApiService from "@/core/services/api.service";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  mixins: [validationMixin],
  name: "ProductEdit",
  data() {
    return {
      cat_valid: false,
      cat_method: 1,
      visible: false,
      form: new Form({
        name: '',
        sort_desc: '',
        added_by: 'supplier',
        category_id: '',
        subcategory_id: '',
        subsubcategory_id: '',
        category_label: 1,
        brand_id: '',
        unit_id: '',
        product_keywords: [{
          key_name: ''
        }],
        property_options: [{
          label: '',
          value: '',
          type: 0
        }],
        photos: [],
        thumbnail_img: [],
        featured_img: [],
        flash_deal_img: [],
        video_link: '',
        color: [],
        color_type: 0,
        color_image: [],
        attribute: [],
        attribute_options: [],
        orderQtyLimit: 0,
        orderQtyLimitMax: null,
        orderQtyLimitMin: null,
        currency_id: '',
        tax: null,
        tax_type: 'Flat',
        discount: null,
        discount_type: 'Flat',
        discount_variation: 0,
        tierDiscount: [{
          min_qty: 1,
          percent_off: 1,
        }],
        priceType: 0,
        stockManagement: 1,
        quantity: 0,
        unit_price: 0,
        sku: '',
        priceMenu: [],
        tierPrice: [{
          min_qty: 1,
          max_qty: 1,
          off_price: 1,
        }],
        weight: '',
        length: '',
        width: '',
        height: '',
        tags: '',
        product_type: 'New',
        description: '',
        shipping_type: 0,
        shipping_cost: '',
        meta_title: '',
        meta_description: '',
        meta_img: [],
      }),
      subcategory_id: '',
      subsubcategory_id: '',
      subcategory: [],
      subsubcategory: [],
      images: [],
      thumbnail: [],
      featured: [],
      flash_deal: [],
      secondStepAlert: false,
      orderQtyLimit: false,
      tags: [],
      tag: '',
      editor: ClassicEditor,
      editorConfig: {},
      shippingSwitch: false,
      meta_img: [],
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
      currency_id: {
        required,
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
      photos: {
        required,
      },
      thumbnail_img: {
        required,
      },
    }
  },
  methods: {
    loadData() {
      ApiService.get('user/product/' + this.$route.params.id)
          .then(({data}) => {
            data.color_image = data.color_image.map(e => ({imageAlfa: [{path: this.showImage(e.image)}], ...e}))
            this.images = data.photos.map(e => ({path: this.showImage(e), hash: e}))
            this.thumbnail = data.thumbnail_img ? [{path: this.showImage(data.thumbnail_img)}] : []
            this.featured = data.featured_img ? [{path: this.showImage(data.featured_img)}] : []
            this.flash_deal = data.flash_deal_img ? [{path: this.showImage(data.flash_deal_img)}] : []
            this.meta_img = data.meta_img ? [{path: this.showImage(data.meta_img)}] : []
            this.subcategory_id = data.subcategory_id
            this.subsubcategory_id = data.subsubcategory_id
            this.form.fill(data);
          })
          .catch(() => {
            this.$router.push({name: "product-list"});
          });
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    showImage(e) {
      return api_base_url + e;
    },
    loadCategory() {
      // this.form.property_options = [{
      //   label: '',
      //   value: '',
      //   type: 0
      // }];
      if (this.cat_valid) {
        let data = '';
        if (this.cat_method === 1) {
          let id = this.form.category_id;
          data = this.propertyList.find(value => value.cat_id === id && value.position === 1)
        }

        if (this.cat_method === 2) {
          let id = this.form.subcategory_id;
          data = this.propertyList.find(value => value.cat_id === id && value.position === 2)
        }

        if (this.cat_method === 3) {
          let id = this.form.subsubcategory_id;
          data = this.propertyList.find(value => value.cat_id === id && value.position === 3)
        }
        if (data !== undefined) {
          let property = JSON.parse(data.name);
          for (let i = 0; i < property.length; i++) {
            this.form.property_options.unshift({
              label: property[i].name,
              value: '',
              type: 1
            });
          }
        }
      }
    },
    catNameShow(id, type) {
      if (type === 'category' && id !== '') {
        let data = this.getCategoryById(id)
        return data ? data.name : '';
      }

      if (type === 'subcategory' && id !== '') {
        let data = this.getSubcategoryNameById(id)
        return data ? '>>' + data.name : '';
      }

      if (type === 'sub-subcategory' && id !== '') {
        let data = this.getSubsubcategoryNameById(id)
        return data ? '>>' + data.name : '';
      }
    },
    addMoreKeyword() {
      if (this.currentUser && this.currentUser.allowed_keywords <= this.form.product_keywords.length) {
        swal.fire(this.$t("message.common.error"), this.$t("message.common.max_number_keyword"), 'warning');
        return
      }
      this.form.product_keywords.push({key_name: ''})
    },
    removeKeyword(index, label) {
      let idx = this.form.product_keywords.indexOf(label);
      if (idx > -1) {
        this.form.product_keywords.splice(idx, 1);
      }
    },
    addMoreProperty() {
      this.form.property_options.push({
        label: '',
        value: '',
        type: 0
      })
    },
    removeProperty(index, label) {
      let idx = this.form.property_options.indexOf(label);
      if (idx > -1) {
        this.form.property_options.splice(idx, 1);
      }
    },
    validateFirstStep() {
      this.$v.form.$touch();
      return true;
    },
    productVideo() {
      this.$refs.youtube.src = this.form.video_link;
    },
    validateSecondStep() {
      this.$v.form.$touch();
      if (this.$v.form.photos.$anyError || this.$v.form.thumbnail_img.$anyError) {
        this.secondStepAlert = true;
      } else {
        this.secondStepAlert = false;
      }
      return true;
    },
    selectColour(e) {
      if (this.form.color_type == 1) {
        for (let prop in this.form.color_image) {
          let data = this.form.color.find(value => value === this.form.color_image[prop].name)
          if (!data) {
            this.form.color_image.splice(prop, 1);
          }
        }
        for (let prop in e) {
          let data2 = this.form.color_image.find(value => value.name === e[prop])
          if (!data2) {
            this.form.color_image.push({
              name: e[prop],
              image: '',
              imageAlfa: [],
            })
          }
        }
      }
      this.priceList();
    },
    selectAttribute(e) {
      for (let prop in this.form.attribute_options) {
        let data = this.form.attribute.find(value => value === this.form.attribute_options[prop].name)
        if (!data) {
          this.form.attribute_options.splice(prop, 1);
        }
      }
      for (let prop in e) {
        let data2 = this.form.attribute_options.find(value => value.name === e[prop])
        if (!data2) {
          this.form.attribute_options.push({
            name: e[prop],
            tag: '',
            value: []
          })
        }
      }
      this.priceList();
    },
    addTierDiscount() {
      this.form.tierDiscount.push({
        min_qty: 1,
        percent_off: 1,
      })
    },
    removeTierDiscount(index, invoice_product) {
      let idx = this.form.tierDiscount.indexOf(invoice_product);
      if (idx > -1) {
        this.form.tierDiscount.splice(idx, 1);
      }
    },
    priceList() {
      let data = [];
      if (this.form.color.length > 0) data.push(this.form.color);
      for (let prop in this.form.attribute_options) {
        if (this.form.attribute_options[prop].value.length > 0) {
          var value = [];
          for (let id in this.form.attribute_options[prop].value) {
            value.push(this.form.attribute_options[prop].value[id].text);
          }
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
            'price': this.form.unit_price,
            'qty': this.form.quantity,
          })
        }
      }
      this.form.priceMenu = finalResult;
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
    addTierPrice() {
      this.form.tierPrice.push({
        min_qty: 1,
        max_qty: 1,
        off_price: 1,
      })
    },
    removeTierPrice(index, invoice_product) {
      var idx = this.form.tierPrice.indexOf(invoice_product);
      if (idx > -1) {
        this.form.tierPrice.splice(idx, 1);
      }
    },
    validateThirdStep() {
      this.$v.form.$touch();
      return true;
    },
    validateForthStep() {
      this.form.tags = '';
      for (let i = 0; i < this.tags.length; i++) {
        if (i === this.tags.length - 1)
          this.form.tags += this.tags[i].text;
        else
          this.form.tags += this.tags[i].text + ",";
      }
      return true;
    },
    formSubmit: function () {
      if (this.$v.form.$anyError) {
        swal.fire(this.$t("message.common.invalid"), this.$t("message.product_created.fill_up_required_data"), 'warning')
        return false;
      }

      if (!this.cat_valid) {
        swal.fire(this.$t("message.common.invalid"), this.$t("message.product_created.category_not_selected"), 'warning')
        return false;
      }
      this.visible = true;
      this.form.category_label = this.cat_method;
      this.form.put('user/product/' + this.$route.params.id)
          .then((e) => {
            this.visible = false;
            swal.fire({
              title: this.$t("message.product_created.success"),
              text: this.$t("message.product_created.product_update_successfully"),
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Ok'
            }).then((result) => {
              this.$router.push({name: "product-list"});
            })
          })
          .catch((error) => {
            this.visible = false;
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
                title: this.$t("message.common.warning"),
                html: mainData,
                type: 'error'
              })
            } else {
              swal.fire({
                title: this.$t("message.common.something_wrong"),
                text: this.$t("message.product_created.input_data_problem"),
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              }).then((result) => {
                //this.$router.push({name: "In House Products"});
              })
            }
          })
    },
  },
  created() {
    this.loadData();
    this.$store.dispatch(SUBSUBCATEGORY_LIST)
    this.$store.dispatch(SUBCATEGORY_LIST)
    this.$store.dispatch(CATEGORY_LIST)
    this.$store.dispatch(BRAND_LIST)
    this.$store.dispatch(UNIT_LIST)
    this.$store.dispatch(PROPERTY_LIST)
    this.$store.dispatch(COLOR_LIST)
    this.$store.dispatch(ATTRIBUTE_LIST)
    this.$store.dispatch(CURRENCY_LIST)
  },
  computed: {
    ...mapGetters(["categoryList", "getSubcategoryById", "getSubsubcategoryById", "getCategoryById", "getSubcategoryNameById", "getSubsubcategoryNameById", "brandList",
      "unitList", "colorList", "attributeList", "currencyList", "propertyList"]),
    currentUser() {
      return this.$store.getters.currentUser;
    },
  },
  components: {
    VueUploadMultipleImage,Loading
  },
  watch: {
    cat_valid: function () {
      this.loadCategory();
    },
    'form.category_id': function (e) {
      if (e !== '' && e !== undefined) {
        this.form.subcategory_id = this.subcategory_id;
        this.form.subsubcategory_id = this.subsubcategory_id;
        this.subcategory = [];
        this.subsubcategory = [];
        this.subcategory = this.getSubcategoryById(e);
        this.cat_method = 1;
        this.cat_valid = !this.subcategory.length > 0;
        this.loadCategory();
      }
    },
    'form.subcategory_id': function (e) {
      if (e !== '' && e !== undefined) {
        this.form.subsubcategory_id = this.subsubcategory_id;
        this.subsubcategory = [];
        this.subsubcategory = this.getSubsubcategoryById(e);
        this.cat_method = 2;
        this.cat_valid = !this.subsubcategory.length > 0;
        this.loadCategory();
      }
    },
    'form.subsubcategory_id': function (e) {
      if (e !== '' && e !== undefined) {
        this.cat_method = 3;
        this.cat_valid = true;
        this.loadCategory();
      }
    },
    'form.color_type': function (val) {
      if (val == 1) {
        this.selectColour(this.form.color);
      }
    },
    orderQtyLimit: function (val) {
      val ? this.form.orderQtyLimit = 1 : this.form.orderQtyLimit = 0;
    },
    shippingSwitch: function (val) {
      val ? this.form.shipping_type = 0 : this.form.shipping_type = 1;
    },
  }
}
</script>

<style scoped>

</style>
