<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{$t("message.new_flash.marketing")}}
        </span>
        <h3 class="page-title"></h3>
      </div>
    </div>
    <!-- End Page Header -->
    <CRow class="justify-content-center my-4">
      <CCol md="10">
        <CCardGroup>
          <CCard class="p-4">
            <CForm @submit.prevent="create()" @keydown="form.onKeydown($event)">
              <c-row class="">
                <CCol md="3" class="mb-3 mb-xl-0">
                  {{$t("message.new_flash.title")}}
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <CInput v-model="$v.form.title.$model"
                          placeholder="Enter Title Name"
                          :invalidFeedback="!$v.form.title.required? this.$t('message.new_flash.title_name_required'): $v.form.title.maxLength?'': this.$t('message.new_flash.title_name_character') "
                          :isValid="validateState('title')"/>
                </CCol>
              </c-row>
              <c-row class="mb-3">
                <CCol md="3" class="mb-3 mb-xl-0">
                  {{$t('message.new_flash.background_color')}}
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <v-select v-model="form.bg_color" :options="Object.values(colorList)" label="name"
                            placeholder="Select Background Color"
                            :reduce="name => name.id">
                    <template #option="{ name, code }">
                      <div class="d-inline-block">
                        <div class="float-left mr-2" v-bind:style="{ backgroundColor:  code }"
                             style="width: 18px; height: 18px;"></div>
                        <div>{{ name }}</div>
                      </div>
                    </template>
                  </v-select>
                </CCol>
              </c-row>
              <c-row class="mb-3">
                <CCol md="3" class="mb-3 mb-xl-0">
                  {{$t('message.new_flash.text_color')}}
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <v-select v-model="form.text_color" :options="Object.values(colorList)" label="name"
                            placeholder="Select Text Color"
                            :reduce="name => name.id">
                    <template #option="{ name, code }">
                      <div class="d-inline-block">
                        <div class="float-left mr-2" v-bind:style="{ backgroundColor:  code }"
                             style="width: 18px; height: 18px;"></div>
                        <div>{{ name }}</div>
                      </div>
                    </template>
                  </v-select>
                </CCol>
              </c-row>
              <c-row class="">
                <CCol md="3" class="mb-3 mb-xl-0">
                  {{$t('message.new_flash.banner')}}
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <b-form-file
                      accept="image/jpeg, image/png, image/jpg"
                      placeholder="Upload banner image (1920x500)"
                      @change="onInputChange"
                  ></b-form-file>
                </CCol>
              </c-row>
              <c-row class="my-3">
                <CCol md="3" class="mb-3 mb-xl-0">
                  {{$t('message.new_flash.date')}}
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <date-range-picker
                      ref="picker"
                      :locale-data="{ firstDay: 1, format: 'DD-MM-YYYY' }"
                      :auto-apply="true"
                      v-model="form.dateRange">
                    <template v-slot:input="picker" style="width: 100%">
                      {{ picker.startDate | moment("MMMM Do YYYY") }} - {{ picker.endDate | moment("MMMM Do YYYY") }}
                    </template>
                  </date-range-picker>
                  <br>
                  <span v-show="!$v.form.dateRange.required" class="danger">{{$t('message.new_flash.select_flash_date_range')}}</span>
                </CCol>
              </c-row>
              <c-row class="my-3">
                <CCol md="3" class="mb-3 mb-xl-0">
                  Products :
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <v-select label="name" :filterable="false" :options="options" @search="onSearch"
                            @input="addNewProduct" v-model="form.product"
                            placeholder="Search Product by name and sku" multiple>
                    <template slot="no-options">
                      {{$t('message.new_flash.type_search_product_link')}}
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
                </CCol>
              </c-row>
              <c-row class="my-3" v-if="form.product_list.length > 0">
                <CCol md="3" class="mb-3 mb-xl-0">
                  {{$t('message.new_flash.discounts')}}
                </CCol>
                <CCol md="9" class="mb-3 mb-xl-0">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                      <tr>
                        <th scope="col">{{$t('message.new_flash.product')}}</th>
                        <th scope="col">{{$t('message.new_flash.base_price')}}</th>
                        <th scope="col">{{$t('message.new_flash.discount')}}</th>
                        <th scope="col">{{$t('message.new_flash.discount_type')}}</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="product in form.product_list">
                        <td>
                          <b-media>
                            <template #aside>
                              <b-img :src="showImage(product.thumbnail_img)" width="64" alt="placeholder"></b-img>
                            </template>
                            <p>{{ product.name }}</p>
                          </b-media>
                        </td>
                        <td>{{ product.price }}</td>
                        <td>
                          <b-form-input type="number" min="0" v-model="product.discount" step="1"
                                        required></b-form-input>
                        </td>
                        <td>
                          <b-form-select v-model="product.discount_type" class="mb-3">
                            <b-form-select-option value="flat">{{$t('message.new_flash.flat')}}</b-form-select-option>
                            <b-form-select-option value="percent">{{$t('message.new_flash.percent')}}</b-form-select-option>
                          </b-form-select>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </CCol>
              </c-row>


              <div class="form-group form-actions my-4">
                <CButton :disabled="form.busy" type="submit" color="primary" class="float-right">
                  <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                        aria-hidden="true"></span>
                  {{$t('message.new_flash.save')}}
                </CButton>
              </div>
            </CForm>
          </CCard>
        </CCardGroup>
      </CCol>
    </CRow>

  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import {COLOR_LIST} from "@/core/services/store/module/color";
import {mapGetters} from "vuex";
import DateRangePicker from 'vue2-daterange-picker'
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";
import _ from 'lodash';
import {FLASH_DEALS_ADD} from "@/core/services/store/module/flash_deals";

export default {
  mixins: [validationMixin],
  name: "NewFlash",
  data() {
    return {
      form: new Form({
        title: '',
        bg_color: '',
        text_color: '',
        banner: '',
        dateRange: {},
        product: [],
        product_list: [],
      }),
      options: []
    }
  },
  validations: {
    form: {
      title: {
        required,
        maxLength: maxLength(255)
      },
      dateRange: {
        required
      }
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    addNewProduct(e) {
      for (let prop in this.form.product_list) {
        let data = this.form.product.find(value => value.id === this.form.product_list[prop].id)
        if (!data) {
          this.form.product_list.splice(prop, 1);
        }
      }
      for (let prop in e) {
        let data2 = this.form.product_list.find(value => value.id === e[prop].id)
        if (!data2) {
          this.form.product_list.push({
            name: e[prop].name,
            id: e[prop].id,
            thumbnail_img: e[prop].thumbnail_img,
            price: e[prop].priceType == 1 ? this.totalPrice(e[prop].product_stock) : e[prop].unit_price,
            discount: 0,
            discount_type: 'flat',
          })
        }
      }
    },
    totalPrice: function (e) {
      if (e.length == 0) return;
      let max = e.reduce((a, b) => Number(a.price) > Number(b.price) ? a : b)
      let min = e.reduce((a, b) => Number(a.price) < Number(b.price) && 0 !== Number(a.price) ? a : b)
      return max === min ? max.price : min.price + ' - ' + max.price;
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      let url = 'product-search?searchProduct=' + search;
      ApiService.get(url)
          .then(res => {
            vm.options = res.data;
            loading(false);
          });
    }, 350),
    onInputChange(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        alert(files.name + this.$t("message.new_flash.not_image"));
        return;
      }
      if (files['size'] > 2111775) {
        alert(this.$t("message.new_flash.uploading_file"));
        return;
      }
      let reader = new FileReader();
      reader.onload = (e) => {
        this.form.banner = reader.result
      }
      reader.readAsDataURL(files);
    },
    create() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('flash-deals')
          .then(({data}) => {
            this.$store.commit(FLASH_DEALS_ADD, data);
            toast.fire({
              icon: 'success',
              title: this.$t("message.new_flash.flash_add_successfully")
            });
            this.$router.push({name: 'Flash Deals'});
          })
    }
  },
  created() {
    this.colorList.length < 1 ? this.$store.dispatch(COLOR_LIST) : '';
  },
  computed: {
    ...mapGetters(["colorList"])
  },
  components: {DateRangePicker},
}
</script>

<style scoped>
.danger,
.danger .dropdown-toggle,
.danger .selected-tag {
  color: #f66161;
  border-color: red;
  font-size: 11px;
}
</style>