<template>
  <div class="col-md-8 offset-md-2 order-md-1">
    <h4 class="mb-3">Member Details</h4>
    <form class="needs-validation" novalidate="">
      <div class="row">
        <div class="col-md-6 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.first_name") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model="$v.form.first_name.$model"
                type="text"
                id="export_started_year"
                size="sm"
                :state="validateState('first_name')"
                :placeholder="$t('message.checkout.first_name')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.first_name.required">
                {{ $t("message.checkout.first_name_required") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback v-if="!$v.form.first_name.maxLength">
                {{ $t("message.checkout.first_name_max") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-6 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.last_name") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model="$v.form.last_name.$model"
                type="text"
                id="export_started_year"
                size="sm"
                :state="validateState('last_name')"
                :placeholder="$t('message.checkout.last_name')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.last_name.required">
                {{ $t("message.checkout.last_name_required") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback v-if="!$v.form.last_name.maxLength">
                {{ $t("message.checkout.last_name_max") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-6 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.email") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model="$v.form.email.$model"
                type="email"
                id="email"
                size="sm"
                :state="validateState('email')"
                :placeholder="$t('message.checkout.email')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.email.required">
                {{ $t("message.checkout.email_required") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback v-else-if="!$v.form.email.maxLength">
                {{ $t("message.checkout.email_max") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-6 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.phone_number") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model="$v.form.phone_number.$model"
                id="email"
                size="sm"
                :state="validateState('phone_number')"
                :placeholder="$t('message.checkout.phone_number')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.phone_number.required">
                {{ $t("message.checkout.phone_number_required") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback
                v-else-if="!$v.form.phone_number.maxLength"
              >
                {{ $t("message.checkout.phone_number_max") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-6 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.address_l1") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model="$v.form.address_l1.$model"
                id="email"
                size="sm"
                :state="validateState('address_l1')"
                :placeholder="$t('message.checkout.address_l1')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.address_l1.required">
                {{ $t("message.checkout.address_l1_required") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback
                v-else-if="!$v.form.address_l1.maxLength"
              >
                {{ $t("message.checkout.maximum_char_is_191") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-6 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.address_l2") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model="form.address_l2"
                id="address_l2"
                size="sm"
                :placeholder="$t('message.checkout.address_l2')"
              ></b-form-input>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-4 my-2">
          <b-form-group id="input-group-7" label-for="annual_revenue_id">
            <label>{{ $t("message.checkout.country") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-money-bill-alt"></i>
              </b-input-group-prepend>
              <b-form-select
                :options="countryList"
                @change="dropChange('country')"
                v-model="$v.form.country_id.$model"
                :state="validateState('country_id')"
                value-field="id"
                size="sm"
                text-field="name"
              >
                <template v-slot:first>
                  <b-form-select-option :value="null" disabled>{{
                    $t("message.checkout.country")
                  }}</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-invalid-feedback v-if="!$v.form.country_id.required">
                {{ $t("message.checkout.country_required") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-4 my-2">
          <b-form-group id="input-group-7" label-for="annual_revenue_id">
            <label>{{ $t("message.checkout.division") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-money-bill-alt"></i>
              </b-input-group-prepend>
              <b-form-select
                :options="getDivisionById(form.country_id)"
                @change="dropChange('division')"
                v-model="$v.form.division_id.$model"
                :state="validateState('division_id')"
                value-field="id"
                size="sm"
                text-field="name"
              >
                <template v-slot:first>
                  <b-form-select-option :value="null" disabled>{{
                    $t("message.checkout.division")
                  }}</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-invalid-feedback v-if="!$v.form.division_id.required">
                {{ $t("message.checkout.division_required") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-4 my-2">
          <b-form-group id="input-group-7" label-for="annual_revenue_id">
            <label>{{ $t("message.checkout.city") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-money-bill-alt"></i>
              </b-input-group-prepend>
              <b-form-select
                :options="getCityById(form.division_id)"
                v-model="$v.form.city_id.$model"
                :state="validateState('city_id')"
                value-field="id"
                size="sm"
                text-field="name"
              >
                <template v-slot:first>
                  <b-form-select-option :value="null" disabled>{{
                    $t("message.checkout.city")
                  }}</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-invalid-feedback v-if="!$v.form.city_id.required">
                {{ $t("message.checkout.city_required") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
        <div class="col-md-4 my-2">
          <b-form-group id="input-group-1" label-for="export_started_year">
            <label>{{ $t("message.checkout.zip_code") }}</label>
            <b-input-group size="sm">
              <b-input-group-prepend is-text>
                <i class="fas fa-vote-yea"></i>
              </b-input-group-prepend>
              <b-form-input
                v-model.number="$v.form.zip.$model"
                type="number"
                id="zip"
                size="sm"
                :state="validateState('zip')"
                :placeholder="$t('message.checkout.zip_code')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.zip.required">
                {{ $t("message.checkout.zip_code_required") }}
              </b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </div>
      </div>
      <hr class="mb-4" />
    </form>
  </div>
</template>
<script>
import { validationMixin } from "vuelidate";
import { required, maxLength, email } from "vuelidate/lib/validators";
import { COUNTRY_LIST } from "../../core/services/store/module/country";
import { CITY_LIST } from "../../core/services/store/module/city";
import { mapGetters } from "vuex";
import { DIVISION_LIST } from "../../core/services/store/module/division";
import { api_base_url } from "../../core/config/app";
export default {
  name: "MembershipSignUp",
  mixins: [validationMixin],
  data() {
    return {
      shipping_a_s_a_b_address: false,
      form: new Form({
        first_name: "",
        last_name: "",
        email: "",
        phone_number: "",
        address_l1: "",
        address_l2: "",
        country_id: null,
        city_id: null,
        division_id: null,
        zip: "",
        carts: [],
        pay_amount: 0,
        user_id: null,
      }),
      cities: [],
      areas: [],
    };
  },
  validations: {
    form: {
      first_name: {
        required,
        maxLength: maxLength(50),
      },
      last_name: {
        required,
        maxLength: maxLength(50),
      },
      email: {
        required,
        email,
      },
      phone_number: {
        required,
      },
      address_l1: {
        required,
      },
      country_id: {
        required,
      },
      city_id: {
        required,
      },
      division_id: {
        required,
      },
      zip: {
        required,
      },
    },
  },
  computed: {
    ...mapGetters([
      "countryList",
      "cityList",
      "divisionList",
      "getCityById",
      "getDivisionById",
    ]),
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
  },
  created() {
    if (this.countryList < 1) this.$store.dispatch(COUNTRY_LIST);
    if (this.divisionList < 1) this.$store.dispatch(DIVISION_LIST);
    if (this.cityList < 1) this.$store.dispatch(CITY_LIST);
  },
};
</script>

<style scoped>
.bd-placeholder-img {
  font-size: 1.125rem;
  text-anchor: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
@media (min-width: 768px) {
  .bd-placeholder-img-lg {
    font-size: 3.5rem;
  }
}
</style>