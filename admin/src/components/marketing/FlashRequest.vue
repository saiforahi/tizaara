<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.flash_request.marketing")}}
        </span>
        <h3 class="page-title">{{$t("message.flash_request.flash_deals_request")}}</h3>
      </div>
    </div>
    <hr>
    <!-- End Page Header -->
    <div class="row mb-3">
      <div class="col-sm-6 col-md-4 col-xl-3">
        <v-select v-model="supplier_id" :options="Object.values(supplierList)" label="first_name"
                  placeholder="Select Supplier"
                  :reduce="name => name.id">
          <template #option="{ first_name, last_name }">
            {{ first_name + ' ' + last_name }}
          </template>
          <template #selected-option="{ first_name, last_name }">
            {{ first_name + ' ' + last_name }}
          </template>
        </v-select>
      </div>
    </div>
    <div class="card card-body p-0">
      <b-table :items="requestFlashDealList" :fields="fields">
        <template #cell(image)="data">
          <img v-lazy="showImage(data.value)" class="img-thumbnail" width="60px">
        </template>
        <template #cell(action)="data">
          <b-button variant="success" @click="addFlashProduct(data.item.id)">
            <font-awesome-icon icon="plus"/>
          </b-button>
        </template>
      </b-table>
    </div>

    <!-- Modal -->
    <b-modal id="brandModal" title="Please select flash deal" hide-footer>
      <b-form @submit.prevent="submit()">
        <b-row class="my-3">
          <b-col sm="12">
            <b-form-select v-model="$v.form.flash_id.$model" :options="Object.values(flash_dealList)" text-field="title"
                           value-field="id"
                           :state="validateState('flash_id')">

            </b-form-select>
            <b-form-invalid-feedback v-if="!$v.form.flash_id.required">
              {{$t("message.flash_request.flash_required")}}
            </b-form-invalid-feedback>
          </b-col>
        </b-row>
        <b-row class="my-3 justify-content-end">
          <b-col cols="8" sm="6" md="4" class="mb-3 mb-xl-0">
            <b-button variant="primary"
                      type="submit" size="sm"
                      class="text-white">{{$t("message.flash_request.submit")}}
            </b-button>

            <b-button variant="secondary"
                      type="button" size="sm" @click="$bvModal.hide('brandModal')"
                      class="text-white float-right">{{$t("message.flash_request.close")}}
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-modal>
    <!-- End Modal -->

  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {SUPPLIER_LIST} from "@/core/services/store/user.module";
import {
  FLASH_DEALS_LIST,
  PRODUCT_REQUEST_FLASH_DEAL,
  REQUEST_FLASH_DEAL
} from "@/core/services/store/module/flash_deals";
import {api_base_url} from "@/core/config/app";
import {validationMixin} from "vuelidate";
import {required} from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  name: "FlashRequest",
  data() {
    return {
      form: {
        'request_id': '',
        'flash_id': ''
      },
      supplier_id: '',
      fields: ['image', 'product_name', 'user_name', 'discount', 'discount_type', 'action']
    }
  },
  validations: {
    form: {
      flash_id: {
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
    loadData() {
      const url = '?';
      this.$store.dispatch(REQUEST_FLASH_DEAL, url)
    },
    addFlashProduct(id) {
      this.form.request_id = id;
      this.$bvModal.show('brandModal');
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }

      const request_id = this.form.request_id;
      const flash_id = this.form.flash_id;
      this.$bvModal.hide('brandModal');
      this.$store.dispatch(PRODUCT_REQUEST_FLASH_DEAL, {request_id, flash_id})
          .then(() => toast.fire({
            icon: 'success',
            title: this.$t("message.flash_request.flash_added")
          }))
          .catch((data) => console.log(data));
    }
  },
  created() {
    this.loadData();
    this.supplierList.length < 1 ? this.$store.dispatch(SUPPLIER_LIST) : '';
    this.flash_dealList.length < 1 ? this.$store.dispatch(FLASH_DEALS_LIST) : '';
  },
  computed: {
    ...
        mapGetters(["supplierList", "requestFlashDealList", "flash_dealList"])
  },
}
</script>

<style scoped>

</style>