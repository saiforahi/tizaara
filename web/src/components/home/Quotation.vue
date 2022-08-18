<template>
  <div class="weekly-market-wrapper newsletter-wrapper">
    <div class="container">
      <div class="row">
        <h2 class="page-title"><a href=""><img src="@/assets/image/rfq.png" class="img-fluid" width="40" alt=""> {{ $t("message.quotations.request_for_quotation") }}</a>
        </h2>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="custom-feature-grid request-quotation">
            <h4>{{ $t("message.quotations.global_sourcing_marketplace") }}</h4>
            <div class="banner-number">
              <div class="number-item">
                <div class="item-total">
                  <span style="transition: all 0.8s ease-out 0s; font-size: 24px;font-weight: 500;">1107000 +</span>
                </div>
                <span>{{ $t("message.quotations.RFQs") }}</span>
              </div>
              <div class="number-item">
                <div class="item-total" style="font-weight: 500;"> &lt;17h</div>
                <span>{{ $t("message.quotations.avg_quotation_duration") }}</span>
              </div>
              <div class="number-item">
                <div class="item-total">
                  <span style="transition: all 0.8s ease-out 0s; font-size: 24px;font-weight: 500;">139000 +</span>
                </div>
                <span>{{ $t("message.quotations.active_suppliers") }}</span>
              </div>
              <div class="number-item">
                <div class="item-total">
                  <span style="transition: all 0.8s ease-out 0s; font-size: 24px;font-weight: 500;">5234 </span>
                </div>
                <span>{{ $t("message.quotations.industries") }}</span>
              </div>
            </div>
            <a href="" class="btn-more" style="background: transparent;border: 1px solid #f23a3a;">{{ $t("message.quotations.learn_more") }}</a>
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card-wrapper">
            <div class="card request-form">
              <div class="card-body">
                <h3>{{ $t("message.quotations.multiple_quotes") }}</h3>
                <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
                  <div class="form-group">
                    <b-form-input type="text" class="form-control" id="product" v-model="$v.form.product.$model"
                                  :placeholder= "$t('message.quotations.enter_product_service_name')"
                                  :state="validateState('product')"></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.product.required">
                      {{ $t("message.quotations.leave_empty") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.product.maxLength">
                      {{ $t("message.quotations.maximum_255_character") }}
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <b-form-input type="number" class="form-control" placeholder="Quantity"
                                    v-model="$v.form.quantity.$model"
                                    id="quantity" :state="validateState('quantity')"></b-form-input>
                      <b-form-invalid-feedback v-if="!$v.form.quantity.maxLength">
                        {{ $t("message.quotations.maximum_10_character") }}
                      </b-form-invalid-feedback>
                    </div>
                    <div class="form-group col-md-6">
                      <b-form-select v-model="form.unit_id" :options="Object.values(unitList)" :state="validateState('unit_id')"
                                     size="sm" text-field="name" value-field="id" id="country">
                        <template v-slot:first>
                          <b-form-select-option value="" disabled>{{ $t("message.quotations.select_units") }}</b-form-select-option>
                        </template>
                      </b-form-select>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <b-form-textarea
                        id="textarea"
                        v-model="form.details"
                        placeholder="Briefly describe what you looking to buy..."
                        rows="3" :state="validateState('details')"
                        max-rows="6"
                    ></b-form-textarea>
                    <b-form-invalid-feedback v-if="!$v.form.details.maxLength">
                      {{ $t("message.quotations.maximum_255_character") }}
                    </b-form-invalid-feedback>
                  </div>
                  <button type="submit" class="btn btn-main">{{ $t("message.quotations.request_for_quotations") }}</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {maxLength, required, email} from "vuelidate/lib/validators";
import {validationMixin} from "vuelidate";
import {mapGetters} from "vuex";
import {UNIT_LIST} from "@/core/services/store/module/unit";

export default {
  mixins: [validationMixin],
  name: "quotation",
  data() {
    return {
      form: new Form({
        product: '',
        details: '',
        quantity: null,
        unit_id: '',
      }),
    }
  },
  validations: {
    form: {
      product: {
        required,
        maxLength: maxLength(191)
      },
      details: {
        maxLength: maxLength(500)
      },
      quantity: {
        required,
        maxLength: maxLength(10)
      },
      unit_id:{
        required,
      }
    }
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      if (!this.isAuthenticated){
        $('#reg').modal('hide');
        $(`#login`).modal('show');
      }else{
        this.form.post('quotation')
            .then(() => {
              this.$v.$reset();
              this.form.reset();
              toast.fire({
                icon: 'success',
                title: this.$t("message.quotations.quotation_send_successfully")
              });
            })
            .catch(() => {
              swal.fire( this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            });
      }
    }
  },
  created() {
    this.$store.dispatch(UNIT_LIST)
  },
  computed: {
    ...mapGetters(["isAuthenticated","unitList"]),
  }
}
</script>

<style scoped>

</style>