<template>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 my-4">
      <div v-if="user.status == 1">
        <svg xmlns="http://www.w3.org/2000/svg" height="200px" version="1.1" viewBox="0 0 512 512.00113" width="100%">
          <g id="surface1">
            <path
                d="M 294.847656 100.304688 L 344.867188 150.328125 L 344.867188 0 C 261.84375 0 194.542969 67.304688 194.542969 150.328125 C 194.542969 233.351562 261.84375 300.652344 344.867188 300.652344 L 344.867188 150.328125 Z M 294.847656 100.304688 "
                style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,85.490196%,26.666667%);fill-opacity:1;"/>
            <path
                d="M 344.867188 0 L 344.867188 150.328125 L 419.898438 75.296875 L 344.867188 150.328125 L 344.867188 300.652344 C 427.890625 300.652344 495.195312 233.351562 495.195312 150.328125 C 495.195312 67.304688 427.890625 0 344.867188 0 Z M 344.867188 0 "
                style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,92.156863%,60%);fill-opacity:1;"/>
            <path
                d="M 508.285156 357.25 C 500.917969 344.601562 484.734375 340.261719 472.027344 347.523438 L 362.554688 410.746094 C 363.722656 408.753906 364.617188 406.613281 365.207031 404.378906 C 369.007812 390.21875 360.617188 375.65625 346.460938 371.839844 L 262.808594 356.984375 C 250.113281 354.097656 237.878906 349.453125 226.464844 343.1875 C 205.699219 332.375 182.578125 326.875 159.171875 327.183594 C 136.164062 326.875 113.425781 332.1875 92.9375 342.65625 C 89.273438 344.484375 85.457031 345.996094 81.53125 347.167969 C 66.710938 351.636719 51.296875 353.84375 35.816406 353.710938 L 0 353.710938 L 0 477.511719 L 53.851562 477.511719 C 59.210938 477.527344 64.5625 477.941406 69.859375 478.75 L 285.445312 511.730469 C 289.578125 512.457031 293.835938 511.707031 297.472656 509.609375 L 498.558594 393.503906 C 511.234375 386.164062 515.585938 369.949219 508.285156 357.25 Z M 339.652344 424.011719 L 339.5625 424.011719 C 337.265625 424.023438 334.976562 423.726562 332.753906 423.128906 L 221.246094 406.769531 L 223.636719 391.382812 L 361.582031 412.25 C 356.691406 419.585938 348.46875 423.996094 339.652344 424.011719 Z M 339.652344 424.011719 "
                style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,90.980392%,79.215686%);fill-opacity:1;"/>
            <path
                d="M 344.867188 176.855469 C 342.523438 176.855469 340.277344 175.921875 338.617188 174.265625 L 288.59375 124.242188 C 285.199219 120.730469 285.296875 115.128906 288.8125 111.738281 C 292.238281 108.425781 297.671875 108.425781 301.097656 111.738281 L 344.867188 155.507812 L 413.648438 86.730469 C 417.160156 83.335938 422.757812 83.433594 426.152344 86.945312 C 429.460938 90.375 429.460938 95.808594 426.152344 99.234375 L 351.121094 174.265625 C 349.460938 175.921875 347.214844 176.855469 344.867188 176.855469 Z M 344.867188 176.855469 "
                style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,31.372549%,13.72549%);fill-opacity:1;"/>
            <path
                d="M 361.582031 412.25 C 356.695312 419.585938 348.46875 423.996094 339.652344 424.011719 L 339.5625 424.011719 C 337.265625 424.023438 334.976562 423.726562 332.753906 423.128906 L 221.246094 406.769531 L 223.636719 391.382812 Z M 361.582031 412.25 "
                style=" stroke:none;fill-rule:nonzero;fill:rgb(74.901961%,68.235294%,59.607843%);fill-opacity:1;"/>
          </g>
        </svg>

        <h4 class="text-center text-capitalize mt-3">{{ $t("message.verify.pending.please_wait") }}</h4>
        <p class="text-center">{{ $t("message.verify.pending.approve_email") }}</p>
      </div>
      <div v-if="user.status == 0" class="card">
        <div class="card-header">
          <strong>{{ $t("message.verify.missing_information.please") }}</strong> {{ $t("message.verify.missing_information.insert_missing") }}
        </div>
        <div class="card-body card-block">
          <form @keydown="form.onKeydown($event)" class="form-horizontal">
            <div class="row form-group mb-4">
              <div class="col col-md-3"><label class=" form-control-label">{{ $t("message.verify.missing_information.i_am_a") }}</label></div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="supplier"
                       v-model="form.account_type" value="1">
                <label class="form-check-label" for="supplier">{{ $t("message.verify.missing_information.supplier") }}</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="buyer"
                       v-model="form.account_type" value="2">
                <label class="form-check-label" for="buyer">{{ $t("message.verify.missing_information.buyer") }}</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="both"
                       v-model="form.account_type" value="0">
                <label class="form-check-label" for="both">{{ $t("message.verify.missing_information.both") }}</label>
              </div>
            </div>
            <div class="form-row mb-4">
              <div class="col col-md-3"><label class=" form-control-label">{{ $t("message.verify.missing_information.name") }}</label></div>
              <div class="form-group col-md-4">
                <b-form-input class="form-control" id="first-name" v-model="form.first_name"
                              :placeholder= "$t('message.message.first_name')" type="text" disabled
                ></b-form-input>
              </div>
              <div class="form-group col-md-4">
                <b-form-input class="form-control" id="last-name" v-model="form.last_name"
                              :placeholder= "$t('message.message.last_name')" type="text" disabled
                ></b-form-input>
              </div>
            </div>
            <div class="row form-group mb-4">
              <div class="col col-md-3"><label class=" form-control-label">Email Address</label>
              </div>
              <div class="col-12 col-md-8">
                <b-form-input class="form-control" id="email"
                              placeholder= "Enter your email address" type="email"
                              v-model="$v.form.email.$model" :disabled="!email"
                              :class="{ 'is-invalid': form.errors.has('email') }"
                              :state="validateState('email')"></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.email.required">
                  {{ $t("message.verify.missing_information.enter_email_address") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.email.maxLength">
                  {{ $t("message.verify.missing_information.email_or_phone") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.email.email">
                  {{ $t("message.verify.missing_information.invalid_email") }}
                </b-form-invalid-feedback>
                <has-error :form="form" field="email"></has-error>
              </div>
            </div>
            <div class="row form-group mb-4">
              <div class="col col-md-3"><label class=" form-control-label">{{ $t("message.verify.missing_information.phone_number") }}</label>
              </div>
              <div class="col-12 col-md-8">
                <b-form-input class="form-control" id="phone" @keyup="onSearch"
                              :placeholder= "$t('message.verify.missing_information.enter_your_phone')" type="number"
                              v-model="$v.form.mobile.$model" :disabled="!mobile"
                              :class="{ 'is-invalid': form.errors.has('mobile') }"
                              :state="validateState('mobile')"></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.mobile.required">
                  {{ $t("message.verify.missing_information.enter_your_phone") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.mobile.maxLength">
                  {{ $t("message.verify.missing_information.phone_digits") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.mobile.minLength">
                  {{ $t("message.verify.missing_information.phone_digits") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.mobile.isUnique">
                  {{ $t("message.verify.missing_information.phone_number_registered") }}
                </b-form-invalid-feedback>
                <has-error :form="form" field="mobile"></has-error>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" :disabled="form.busy" @click="onSubmit" class="btn btn-primary float-right">
            <i class="fa fa-dot-circle-o"></i> {{ $t("message.verify.missing_information.submit") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {validationMixin} from "vuelidate";
import {minLength, email, maxLength, required} from "vuelidate/lib/validators";
import {VERIFY_AUTH} from "@/core/services/store/auth.module";
import ApiService from "@/core/services/api.service";

export default {
  mixins: [validationMixin],
  name: "Verify",
  data() {
    return {
      form: new Form({
        account_type: "1",
        first_name: "",
        last_name: "",
        email: "",
        mobile: "",
      }),
      account_type: false,
      email: false,
      mobile: false,
      validationSubmit: false,

    }
  },
  validations: {
    form: {
      email: {
        required,
        email,
        maxLength: maxLength(255)
      },
      account_type: {
        required
      },
      mobile: {
        required,
        maxLength: maxLength(11),
        minLength: minLength(11),
        isUnique(value) {
          if (value === '') return true
          if (value == this.user.mobile) return true
          if (value.length !== 11) return true
          let url = 'user/user-search?phone=' + value;
          return ApiService.get(url).then(res => {
            console.log(res.data)
            return res.data == 2;
          });
        }
      },
    }
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    userStatus(val) {
      if (val.status == 2) {
        this.$router.push({name: "Dashboard"});
      }
    },
    loadUser() {
      this.account_type = this.user.account_type === '' || this.user.account_type == null;
      this.email = this.user.email === '' || this.user.email == null;
      this.mobile = this.user.mobile === '' || this.user.mobile == null;
      this.validationSubmit = this.user.status == 1;

      this.form.first_name = this.user.first_name;
      this.form.last_name = this.user.last_name;
      this.form.email = this.user.email;
      this.form.mobile = this.user.mobile;
      this.form.account_type = this.user.account_type;
    },
    onSubmit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('user/verify-request/' + this.user.id)
          .then(() => {
            this.$store.dispatch(VERIFY_AUTH)
          })
          .catch(error => {
            let data = error.response;
            if (data.status === 500) {
              swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            }
          })
    },
    onSearch() {
      this.search(this.form.mobile, this);
    },
    search: _.debounce((search, vm) => {
      let url = 'user/user-search?phone=' + search;
      ApiService.get(url)
          .then(res => {
            return res.data != 1;
          });
      return false;
    }, 350)
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  },
  watch: {
    user(val) {
      this.userStatus(val);
      this.loadUser();
    }
  },
  created() {
    Fire.$emit('menuToggle');
  }
}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>
