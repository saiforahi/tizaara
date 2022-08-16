<template>
  <div>
    <CCard class="">
      <CCardBody>
        <b-form @submit.prevent="createGroup()" @keydown="form.onKeydown($event)">
          <h3 class="text-center">{{$t("message.group_create.add_group_product")}}</h3>
          <p class="text-center">{{$t("message.group_create.select_product_name")}}</p>
          <hr>
          <CInput label="Product Group Name :" v-model="$v.form.name.$model"
                  horizontal placeholder="Enter product grouping name"
                  :invalidFeedback="!$v.form.name.required? this.$t('message.group_create.product_grouping_name_required'): $v.form.name.maxLength?'': this.$t('message.group_create.product_grouping_name_character') "
                  :isValid="validateState('name')"/>
          <CRow>
            <CCol col="6" sm="4" md="3" class="mb-3 mb-xl-0">
              Select Product
            </CCol>
            <CCol col="6" sm="8" md="9" class="mb-3 mb-xl-0">
              <v-select label="name" :filterable="false" :options="options" @search="onSearch" @input="addNewProduct"
                        :reduce="name => name.id" placeholder="Search Product by name and sku" multiple>
                <template slot="no-options">
                  {{$t("message.group_create.type_search_product_link")}}
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
          </CRow>
          <CRow class="justify-content-end my-4">
            <CCol col="4" sm="4" md="2">
              <CButton block color="info" type="submit" :disabled="form.busy">
                  {{$t("message.group_create.submit")}}
              </CButton>
            </CCol>
            <CCol col="4" sm="4" md="2">
              <CButton block color="dark" @click="$router.push({name: 'Products Group'})">{{$t("message.group_create.close")}}</CButton>
            </CCol>
          </CRow>
        </b-form>
      </CCardBody>
    </CCard>
  </div>
</template>

<script>
import _ from 'lodash';
import ApiService from "@/core/services/api.service";
import {api_base_url} from "@/core/config/app";
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  name: "GroupCreate",
  data() {
    return {
      form: new Form({
        name: '',
        linkProduct: '',

      }),
      options: []
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(200)
      },
      linkProduct: {
        required
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
            vm.options = res.data;
            loading(false);
          });
    }, 350),
    createGroup() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('product-group')
          .then((e) => {
            swal.fire({
              title: this.$t("message.group_create.well_done"),
              text:  this.$t("message.group_create.product_groping_successfully"),
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Ok'
            }).then((result) => {
              this.$router.push({name: "Products Group"});
            })
          })
          .catch((e) => {
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
                text: this.$t("message.common.input_data_problem"),
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              }).then((result) => {
                this.$router.push({name: "In House Products"});
              })
            }
          })
    }
  }
}
</script>

<style scoped>

</style>
