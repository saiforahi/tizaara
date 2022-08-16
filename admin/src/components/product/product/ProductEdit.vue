<template>
  <div>
    <CCard class="">
      <CCardBody>
        <form-wizard @on-complete="formSubmit" title="Update Products" subtitle="Update product information"
                     color="#3c4b64">
          <!-- Part - 1 -->
          <tab-content title="Product Information" icon="ti-package"
                       :before-change="validateFirstStep">
            <div class="card" style="background-color: #dae2ed;">
              <div class="card-body" style="padding: 12px">
                <c-row>
                  <c-col>
                    <b-form-select v-model="form.category_id" :options="Object.values(categoryList)" value-field="id"
                                   text-field="name" @input="categorySelect" :select-size="18"
                                   class="cat-select-design"></b-form-select>
                  </c-col>
                  <c-col>
                    <b-form-select v-if="subcategory.length > 0" v-model="form.sub_category_id" :options="subcategory"
                                   :select-size="18" value-field="id" @input="subcategorySelect"
                                   text-field="name" class="cat-select-design"></b-form-select>
                  </c-col>
                  <c-col>
                    <b-form-select v-if="subsubcategory.length > 0" v-model="form.sub_subcategory_id"
                                   :options="subsubcategory" @input="subsubcategorySelect"
                                   :select-size="18" value-field="id"
                                   text-field="name" class="cat-select-design"></b-form-select>
                  </c-col>
                </c-row>
              </div>
              <div class="card-footer" style="font-size: 13px">
                 {{$t("message.product_edit.categories_selected")}}
                {{ catNameShow(form.category_id, 'category') }} {{ catNameShow(form.sub_category_id, 'subcategory') }}
                {{ catNameShow(form.sub_subcategory_id, 'sub-subcategory') }}
                <font-awesome-icon v-if="cat_valid"
                                   class="text-success" style="font-size: 15px" icon="check-circle"/>
              </div>
            </div>

            <CInput label="Product Name* :" v-model="$v.form.name.$model"
                    horizontal placeholder="Enter product name"
                    :invalidFeedback="!$v.form.name.required? this.$t('message.product_edit.product_name_required'): $v.form.name.maxLength?'': this.$t('message.product_edit.product_name_character') "
                    :isValid="validateState('name')"/>
            <CTextarea label="Product sort description : " v-model="$v.form.sort_desc.$model"
                       horizontal placeholder="Product sort description"
                       :invalidFeedback="$v.form.sort_desc.maxLength?'':this.$t('message.product_edit.product_sort_description_character')"
                       :isValid="validateState('sort_desc')"
                       rows="3"/>
            <div role="group" class="form-group form-row">
              <label class="col-form-label col-sm-3">  {{$t('message.product_edit.select_brand')}} </label>
              <div class="col-sm-9">
                <v-select v-model="form.brand_id" :options="Object.values(brandList)" label="name"
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
              <label class="col-form-label col-sm-3"> {{$t('message.product_edit.select_unit')}} </label>
              <div class="col-sm-9">
                <v-select v-model="form.unit" :options="Object.values(unitList)" label="name"
                          placeholder="Select product unit"
                          :reduce="name => name.id"></v-select>
              </div>
            </div>
            <b-row v-for="(property, k) in form.properties" :key="k">
              <b-col sm="3">
                <b-form-input v-model="property.label" placeholder="Enter property label"
                              :disabled="property.type != 0" required></b-form-input>
              </b-col>
              <b-col sm="8">
                <b-form-input class="mb-3" v-model="property.value" placeholder="Enter property value"
                              required></b-form-input>
              </b-col>
              <b-col sm="1">
                <CButton color="secondary" @click="removeProperty(k, property)">
                  <font-awesome-icon icon="trash-alt"/>
                </CButton>
              </b-col>
            </b-row>
            <p class="text-info font-weight-bold my-2" style="font-size: 12px;cursor: pointer"
               @click="addMoreProperty">
              {{$t('message.product_edit.add_more_property')}}</p>

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
              {{$t('message.product_edit.insert_multiple_product_thumbnail_image')}}
            </b-alert>
            <CRow class="my-3">
              <CCol md="6">
                <div role="group" class="d-flex justify-content-center">
                  <div class=""><label>{{$t('message.product_edit.select_product_images')}}</label>
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
              </CCol>
              <CCol md="6">
                <div role="group" class="d-flex justify-content-center">
                  <div class=""><label>{{$t('message.product_edit.select_thumbnail_image')}}</label>
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
              </CCol>
            </CRow>
            <CRow class="my-5">
              <CCol md="6">
                <div role="group" class="d-flex justify-content-center">
                  <div class=""><label>{{$t('message.product_edit.select_featured_images')}}</label>
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
              </CCol>
              <CCol md="6">
                <div role="group" class="d-flex justify-content-center">
                  <div class=""><label>{{$t('message.product_edit.select_flash_deal_image')}}</label>
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
                       {{$t('message.product_edit.search')}}
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
            {{$t('message.product_edit.product_attribute')}}
            <hr>
            <c-row>
              <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                <CButton block color="dark" disabled>
                  {{$t('message.product_edit.colour')}}
                </CButton>
              </CCol>
              <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                <v-select v-model="form.color" :options="Object.values(colorList)" label="name"
                          @input="selectColour"
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
                    v-model="color_type"
                    name="checkbox-1"
                    value="1"
                    unchecked-value="0">
                  {{$t('message.product_edit.colour_with_image')}}
                </b-form-checkbox>
              </CCol>
            </c-row>
            <CRow v-if="this.form.color_type === 1">
              <CCol col="0" sm="0" md="2">
              </CCol>
              <CCol col="12" sm="12" md="6">
                <CRow>
                  <CCol col="6" sm="6" md="4" class="mb-3 mb-xl-0 mt-3" v-for="(image, index) in form.color_image"
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
                  </CCol>
                </CRow>
              </CCol>
            </CRow>
            <CRow class="mt-3">
              <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                <CButton block color="dark" disabled>
                  {{$t('message.product_edit.attributes')}}
                </CButton>
              </CCol>
              <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                <v-select v-model="form.attribute" :options="Object.values(attributeList)" label="name"
                          @input="selectAttribute"
                          :reduce="name => name.name" placeholder="Select product Attribute" multiple>
                </v-select>
              </CCol>
            </CRow>
            <p class="text-info">{{$t('message.product_edit.note')}}</p>
            <CRow class="mt-3" v-for="(addAttributes, k) in form.attribute_options" :key="k">
              <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                <CButton block color="dark" disabled>{{ addAttributes.name }}</CButton>
              </CCol>
              <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                <vue-tags-input
                    v-model="addAttributes.tag" class="w-100"
                    :tags="addAttributes.value"
                    :allow-edit-tags="true" :separators="[';', ',']"
                    @tags-changed="(newTags) => {addAttributes.value = newTags; priceList()}"/>
              </CCol>
            </CRow>
            <br><br><br>{{$t('message.product_edit.product_discount')}}
            <hr>
            <CRow>
              <CCol col="12" md="7" class="mb-3 mb-xl-0">
                <CInput
                    label="SKU : "
                    horizontal
                    placeholder="Enter product sku"
                    v-model="form.sku" readonly disabled
                />
              </CCol>
            </CRow>
            <CRow class="mb-3">
              <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                {{$t('message.product_edit.order_quantity_limit')}}
              </CCol>
              <CCol col="6" sm="4" md="1" class="mb-3 mb-xl-0">
                <CSwitch class="mx-1" color="primary" :checked.sync="orderQtyLimit" variant="3d"/>
              </CCol>
              <CCol v-if="form.orderQtyLimit === 1" col="6" sm="4" md="3">
                <CInput
                    placeholder="Enter minimum Order Quantity"
                    min="1"
                    type="number"
                    v-model="form.orderQtyLimitMin"
                />
              </CCol>
              <CCol v-if="form.orderQtyLimit === 1" col="6" sm="4" md="3" class="mb-3 mb-xl-0">
                <CInput
                    placeholder="Enter maximum Order Quantity"
                    min="1"
                    type="number"
                    v-model="form.orderQtyLimitMax"
                />
              </CCol>
            </CRow>
<!--            <CRow>
              <CCol col="6" sm="4" md="7" class="mb-3 mb-xl-0">
                <b-form-group label="Select Currency*:"
                              label-cols-sm="4"
                              label-cols-lg="3">
                  <b-form-select v-model="$v.form.currency_id.$model" :options="Object.values(currencyList)"
                                 :state="validateState('currency_id')" value-field="id"
                                 text-field="name">
                    <template v-slot:first>
                      <b-form-select-option value="" disabled>{{$t('message.product_edit.please_select_currency')}}</b-form-select-option>
                    </template>
                  </b-form-select>
                  <b-form-invalid-feedback v-if="!$v.form.currency_id.required">
                    {{$t('message.product_edit.currency_required')}}
                  </b-form-invalid-feedback>
                </b-form-group>
              </CCol>
            </CRow>-->
            <CRow>
              <CCol col="6" sm="4" md="7" class="mb-3 mb-xl-0">
                <CInput
                    label="Tax : "
                    horizontal
                    placeholder="Enter tax value"
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
            <CRow v-if="form.discountMethod == 0">
              <CCol col="6" sm="4" md="7" class="mb-3 mb-xl-0">
                <CInput
                    label="Discount : "
                    horizontal
                    placeholder="Enter discount value"
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
                    v-model="form.discountMethod"
                    value="1" unchecked-value="0">
                  {{$t('message.product_edit.enable_discount')}}
                </b-form-checkbox>
              </CCol>
            </CRow>
            <CRow v-if="form.discountMethod == 1" class="my-4">
              <CCol col="12" sm="12" md="8" class="mb-3 mb-xl-0">
                <p class="text-right text-info font-weight-bold my-0" style="font-size: 12px;cursor: pointer"
                   @click="addTierDiscount">
                  {{$t('message.product_edit.add_volume_tier')}}</p>
                <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th>{{$t('message.product_edit.minimum_quantity')}}</th>
                    <th></th>
                    <th>{{$t('message.product_edit.additional_off')}}</th>
                    <th>{{$t('message.product_edit.action')}}</th>
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
                       {{$t('message.product_edit.or_more')}}
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


            <br><br><br>{{$t('message.product_edit.product_price_stock')}}
            <hr>
            <CRow class="mt-4">
              <CCol col="12">
                <b-form-group>
                  <b-form-radio-group id="radio-group-2" v-model="form.priceType" name="radio-sub-component">
                    <b-form-radio value="0">{{$t('message.product_edit.simple_product')}}</b-form-radio>
                    <b-form-radio value="1">{{$t('message.product_edit.variable_product')}}</b-form-radio>
                    <b-form-radio value="2">{{$t('message.product_edit.volume_tier_pricing')}}</b-form-radio>
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
                  {{$t('message.product_edit.stock_management')}}
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
            </CRow>
            <table v-if="form.priceType == 1" class="table table-bordered">
              <thead>
              <tr>
                <th>{{$t('message.product_edit.variant')}}</th>
                <th>{{$t('message.product_edit.variant_price')}}</th>
                <th v-if="form.stockManagement == 1">{{$t('message.product_edit.quantity')}}</th>
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
                  {{$t('message.product_edit.add_volume_tier')}}</p>
                <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th>{{$t('message.product_edit.minimum_quantity')}}</th>
                    <th>{{$t('message.product_edit.maximum_muantity')}}</th>
                    <th>{{$t('message.product_edit.unit_price')}}</th>
                    <th>{{$t('message.product_edit.action')}}</th>
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
          <tab-content title="Product Description" :before-change="validateForthStep" icon="ti-receipt">
            {{$t('message.product_edit.product_description')}}
            <hr>
            <CInput label="Product Weight :" v-model="$v.form.weight.$model"
                    horizontal placeholder="Enter product weight"
                    :invalidFeedback="$v.form.weight.maxLength?'': this.$t('message.product_edit.product_weight_character') "
                    :isValid="validateState('weight')"/>
            <div role="group" class="form-group form-row">
              <label class="col-form-label col-sm-3"> {{$t('message.product_edit.tags')}} </label>
              <div class="col-sm-9">
                <vue-tags-input
                    v-model="tag" class="w-100"
                    :tags="tags" placeholder="Enter product tags"
                    :allow-edit-tags="true" :separators="[';', ',']"
                    @tags-changed="newTags => tags = newTags"/>
              </div>
            </div>
            <div role="group" class="form-group form-row">
              <label class="col-form-label col-sm-3">{{$t('message.product_edit.dimension')}} </label>
              <div class="col-sm-3">
                <CInput v-model="$v.form.length.$model"
                        placeholder="Length"
                        :invalidFeedback="$v.form.length.maxLength?'':'' "
                        :isValid="validateState('length')"/>
              </div>
              <div class="col-sm-3">
                <CInput v-model="$v.form.width.$model"
                        placeholder="Width"
                        :invalidFeedback="$v.form.width.maxLength?'':this.$t('message.product_edit.not_more_character') "
                        :isValid="validateState('width')"/>
              </div>
              <div class="col-sm-3">
                <CInput v-model="$v.form.height.$model"
                        placeholder="Height"
                        :invalidFeedback="$v.form.height.maxLength?'':this.$t('message.product_edit.not_more_character') "
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
                    <b-form-radio value="New">{{$t('message.product_edit.new')}}</b-form-radio>
                    <b-form-radio value="Used">{{$t('message.product_edit.used')}}</b-form-radio>
                  </b-form-radio-group>
                </b-form-group>
              </CCol>
            </CRow>
            <br><br>
            <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
            <br><br>

          </tab-content>
          <!-- End Part - 4 -->
          <!-- Part - 5 -->
          <tab-content title="Product Shipping and SEO" icon="ti-truck">
            {{$t('message.product_edit.product_shipping_cost')}}
            <hr>
            <loading :active.sync="visible" :can-cancel="true"></loading>
            <CRow>
              <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                {{$t('message.product_edit.free_shipping')}}
              </CCol>
              <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
                <CSwitch class="mx-1" color="primary" :checked.sync="shippingSwitch" variant="3d"/>
              </CCol>
            </CRow>
            <CRow>
              <CCol col="6" sm="4" md="6" class="mb-3 mb-xl-0">
                <CInput
                    label="Shipping cost : "
                    horizontal
                    placeholder="Shipping cost"
                    value="0"
                    type="number" :disabled="shippingSwitch"
                    v-model="form.shipping_cost"
                />
              </CCol>
            </CRow>
            <br><br><br>{{$t('message.product_edit.seo_meta_tags')}}
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
                {{$t('message.product_edit.meta_image')}}
              </CCol>
              <CCol col="6" sm="4" md="2" class="mb-3 mb-xl-0">
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
              </CCol>
            </CRow>
          </tab-content>
          <!-- End Part - 5 -->
        </form-wizard>
      </CCardBody>
    </CCard>
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import {api_base_url} from "@/core/config/app";
import {mapGetters} from "vuex";
import {SUBSUBCATEGORY_LIST} from "@/core/services/store/module/subsubcategory";
import {SUBCATEGORY_LIST} from "@/core/services/store/module/subcategory";
import {CATEGORY_LIST} from "@/core/services/store/module/category";
import {BRAND_LIST} from "@/core/services/store/module/brand";
import {UNIT_LIST} from "@/core/services/store/module/unit";
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import {COLOR_LIST} from "@/core/services/store/module/color";
import {ATTRIBUTE_LIST} from "@/core/services/store/module/attribute";
import {CURRENCY_LIST} from "@/core/services/store/module/currency";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {PROPERTY_LIST} from "@/core/services/store/module/property";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
  mixins: [validationMixin],
  name: "ProductEdit",
  data() {
    return {
      visible: false,
      cat_valid: false,
      cat_method: 1,
      form: new Form({
        id: '',
        added_by: 'admin',
        name: '',
        sort_desc: '',
        category_id: '',
        sub_category_id: '',
        sub_subcategory_id: '',
        category_label: 1,
        brand_id: '',
        unit: '',
        properties: [{
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
        discountMethod: 0,
        tierDiscount: [{
          unit: 1,
          value: 1,
        }],
        priceType: 0,
        stockManagement: 1,
        quantity: 0,
        unit_price: 0,
        sku: '',
        priceMenu: [],
        tierPrice: [],
        weight: '',
        length: '',
        width: '',
        height: '',
        tags: '',
        product_type: 'New',
        description: '',
        shipping_type: 0,
        shipping_cost: 0,
        meta_title: '',
        meta_description: '',
        meta_img: [],
      }),
      subcategory: [],
      subsubcategory: [],
      images: [],
      thumbnail: [],
      featured: [],
      flash_deal: [],
      secondStepAlert: false,
      color_type: "0",
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
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    showImage(e) {
      return api_base_url + e;
    },
    categorySelect(e) {
      if (e !== '' && e !== undefined) {
        this.form.sub_category_id = '';
        this.form.sub_subcategory_id = '';
        this.subcategory = [];
        this.subsubcategory = [];
        this.subcategory = this.getSubcategoryById(e);
        this.cat_method = 1;
        this.cat_valid = !this.subcategory.length > 0;
        this.loadCategory();
      }
    },
    subcategorySelect(e) {
      if (e !== '' && e !== undefined) {
        this.form.sub_subcategory_id = '';
        this.subsubcategory = [];
        this.subsubcategory = this.getSubsubcategoryById(e);
        this.cat_method = 2;
        this.cat_valid = !this.subsubcategory.length > 0;
        this.loadCategory();
      }
    },
    subsubcategorySelect(e) {
      if (e !== '' && e !== undefined) {
        this.cat_method = 3;
        this.cat_valid = true;
        this.loadCategory();
      }
    },
    loadCategory() {
      this.form.properties = [{
        label: '',
        value: '',
        type: 0
      }];
      if (this.cat_valid) {
        let data = '';
        if (this.cat_method === 1) {
          let id = this.form.category_id;
          data = this.propertyList.find(value => value.cat_id === id && value.position === 1)
        }

        if (this.cat_method === 2) {
          let id = this.form.sub_category_id;
          data = this.propertyList.find(value => value.cat_id === id && value.position === 2)
        }

        if (this.cat_method === 3) {
          let id = this.form.sub_subcategory_id;
          data = this.propertyList.find(value => value.cat_id === id && value.position === 3)
        }
        if (data !== undefined) {
          let property = JSON.parse(data.name);
          for (let i = 0; i < property.length; i++) {
            this.form.properties.unshift({
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
    addMoreProperty() {
      this.form.properties.push({
        label: '',
        value: '',
        type: 0
      })
    },
    removeProperty(index, label) {
      let idx = this.form.properties.indexOf(label);
      if (idx > -1) {
        this.form.properties.splice(idx, 1);
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
      if (this.color_type === "1") {
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
        unit: 1,
        value: 1,
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
            'variant_price': this.form.unit_price,
            'quantity': this.form.quantity,
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
    formSubmit() {
      if (this.$v.form.$anyError) {
        swal.fire(this.$t("message.common.invalid"), this.$t("message.common.fill_up_required_data"), 'warning')
        return false;
      }

      if (!this.cat_valid) {
        swal.fire(this.$t("message.common.invalid"), this.$t("message.product_edit.category_not_selected"), 'warning')
        return false;
      }
      this.visible = true;
      this.show = true;
      this.form.category_label = this.cat_method;
      this.form.put('product/' + this.form.id)
          .then((e) => {
            this.visible = false;
            this.show = false;
            swal.fire({
              title: this.$t("message.product_edit.product_update_successfully"),
              text: this.$t("message.product_edit.product_update_successfully"),
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Ok'
            }).then((result) => {
              this.$router.go(-1);
            })
          })
          .catch((error) => {
            this.visible = false;
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
                text: this.$t("message.product_edit.input_data_problem"),
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
    loadProduct() {
      let url = 'product/' + this.$route.params.id;
      this.form.get(url)
          .then(({data}) => {
            let that = this;
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.sort_desc = data.sort_desc;
            this.form.category_id = data.category_id;
            setTimeout(function () {
              that.form.sub_category_id = data.subcategory_id;
              setTimeout(function () {
                that.form.sub_subcategory_id = data.subsubcategory_id;
                setTimeout(function () {
                  that.form.properties = JSON.parse(data.property_options);
                }, 1000);
              }, 2000);
            }, 2000);
            this.form.sub_category_id = data.subcategory_id;
            this.form.category_label = data.category_label;
            this.cat_method = data.category_label;
            this.form.brand_id = data.brand_id;
            this.form.unit = data.unit.id;
            this.form.weight = data.weight;
            this.form.length = data.length;
            this.form.width = data.width;
            this.form.height = data.height;
            this.form.product_type = data.product_type;
            let photos = JSON.parse(data.photos);
            for (let i = 0; i < photos.length; i++) {
              this.images.push({
                path: this.showImage(photos[i]),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
              this.form.photos.push({
                path: this.showImage(photos[i]),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
            }
            if (data.thumbnail_img !== '') {
              this.thumbnail.push({
                path: this.showImage(data.thumbnail_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
              this.form.thumbnail_img.push({
                path: this.showImage(data.thumbnail_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
            }
            if (data.featured_img !== '') {
              this.featured.push({
                path: this.showImage(data.featured_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
              this.form.featured_img.push({
                path: this.showImage(data.featured_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
            }
            if (data.flash_deal_img !== '') {
              this.flash_deal.push({
                path: this.showImage(data.flash_deal_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
              this.form.flash_deal_img.push({
                path: this.showImage(data.flash_deal_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
            }
            if (data.meta_img !== '') {
              this.meta_img.push({
                path: this.showImage(data.meta_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
              this.form.meta_img.push({
                path: this.showImage(data.meta_img),
                default: 1,
                highlight: 1,
                caption: 'caption to display. receive',
              });
            }
            this.form.video_link = data.video_link;
            this.form.color = JSON.parse(data.colors);
            this.form.color_type = data.color_type;
            this.color_type = data.color_type;
            let color = JSON.parse(data.color_image);
            for (let prop in color) {
              this.form.color_image.push({
                name: color[prop].name,
                image: [{
                  path: this.showImage(color[prop].image),
                  default: 1,
                  highlight: 1,
                  caption: 'caption to display. receive',
                }],
                imageAlfa: [{
                  path: this.showImage(color[prop].image),
                  default: 1,
                  highlight: 1,
                  caption: 'caption to display. receive',
                }],
              })
            }
            this.form.attribute = JSON.parse(data.attributes);
            let attribute = JSON.parse(data.attribute_options);
            for (let prop in attribute) {
              let value = [];
              for (let prap in attribute[prop].value) {
                value.push({
                  "text": attribute[prop].value[prap].text,
                  "tiClasses": ["ti-valid"]
                })
              }
              this.form.attribute_options.push({
                name: attribute[prop].name,
                tag: '',
                value: value
              })
            }
            if (data.description !== null) {
              this.form.description = data.description;
            }
            if (data.tags != null && data.tags != "null"){
              let tags = JSON.parse(data.tags).split(",");
              for (let prap in tags) {

                this.tags.push({
                  "text": tags[prap],
                  "tiClasses": ["ti-valid"]
                })
              }
            }
            this.form.orderQtyLimit = data.orderQtyLimit;
            this.orderQtyLimit = data.orderQtyLimit == 1;
            this.form.orderQtyLimitMax = data.orderQtyLimitMax;
            this.form.orderQtyLimitMin = data.orderQtyLimitMin;
            this.form.currency_id = data.currency_id;
            this.form.tax = data.tax;
            this.form.tax_type = data.tax_type;
            this.form.discount = data.discount;
            this.form.discount_type = data.discount_type;
            this.form.discountMethod = data.discount_variation;
            let tierDiscount = [];
            for (let prop in data.discount_variation_data) {
              tierDiscount.push({
                unit: data.discount_variation_data[prop].min_qty,
                value: data.discount_variation_data[prop].percent_off,
              })
            }
            this.form.tierDiscount = tierDiscount;
            data.price_variation.forEach(item=>{
              this.form.tierPrice.push({
                min_unit: item.min_qty,
                max_unit: item.max_qty,
                value: item.off_price,
              });
            });
            data.product_stock.forEach(item=>{
              this.form.priceMenu.push({
                'variant': JSON.parse(item.variant),
                'variant_price': item.price,
                'quantity': item.qty,
              })
            });
            this.form.priceType = data.priceType;
            this.form.stockManagement = data.stockManagement;
            this.form.unit_price = data.unit_price;
            this.form.quantity = data.quantity;
            this.form.sku = data.sku;
            this.form.shipping_type = data.shipping_type;
            this.form.shipping_cost = data.shipping_cost;
            this.form.meta_title = data.meta_title;
            this.form.meta_description = data.meta_description;
          })
    }
  },
  created() {
    this.$store.dispatch(SUBSUBCATEGORY_LIST)
    this.$store.dispatch(SUBCATEGORY_LIST)
    this.$store.dispatch(CATEGORY_LIST)
    this.$store.dispatch(BRAND_LIST)
    this.$store.dispatch(UNIT_LIST)
    this.$store.dispatch(COLOR_LIST)
    this.$store.dispatch(ATTRIBUTE_LIST)
    this.$store.dispatch(CURRENCY_LIST)
    this.$store.dispatch(PROPERTY_LIST)
    this.loadProduct();
  },
  watch: {
    cat_valid: function () {
      this.loadCategory();
    },
    color_type: function (val) {
      if (val == "1") {
        this.form.color_type = 1;
        this.selectColour(this.form.color);
      } else {
        this.form.color_type = 0;
      }
    },
    orderQtyLimit: function (val) {
      val ? this.form.orderQtyLimit = 1 : this.form.orderQtyLimit = 0;
    },
    shippingSwitch: function (val) {
      val ? this.form.shipping_type = 0 : this.form.shipping_type = 1;
    },
  },
  computed: {
    ...mapGetters(["categoryList", "getSubcategoryById", "getSubsubcategoryById", "getCategoryById", "getSubcategoryNameById", "getSubsubcategoryNameById", "brandList",
      "unitList", "colorList", "attributeList", "currencyList", "propertyList"])
  },
  components: {
    VueUploadMultipleImage,Loading,
  },
}
</script>

<style scoped>

</style>