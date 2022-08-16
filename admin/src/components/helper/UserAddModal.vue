<template>
  <!-- Modal -->
  <b-modal id="brandModal" :title="'Create an account'" hide-footer>
    <b-form @submit.prevent="createUser()" @keydown="form.onKeydown($event)">
      <b-form-radio-group label="Create as " v-slot="{ ariaDescribedby }"
                          v-model.number="$v.form.account_type.$model"
                          :state="validateState('account_type')">
        <label class="mr-2">Create as </label>
        <b-form-radio  :aria-describedby="ariaDescribedby" name="account_type" value="1">Supplier</b-form-radio>
        <b-form-radio :aria-describedby="ariaDescribedby" name="account_type" value="2">Buyer</b-form-radio>
        <b-form-radio :aria-describedby="ariaDescribedby" name="account_type" value="0">Both</b-form-radio>
      </b-form-radio-group>
      <b-form-group label="First Name :"
                    label-cols-sm="5"
                    label-cols-lg="4">
        <b-form-input
            class="form-control form-control-solid h-auto"
            :class="{ 'is-invalid': form.errors.has('first_name')}"
            v-model="$v.form.first_name.$model"
            placeholder="First Name"
            :state="validateState('first_name')"
            aria-describedby="BrandName"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.first_name.required">
          {{ $t("message.register.first_name_required")}}
        </b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.form.first_name.maxLength">
          {{ $t("message.register.first_name_max_50")}}
        </b-form-invalid-feedback>
        <has-error :form="form" field="first_name"></has-error>
      </b-form-group>
      <b-form-group label="Last Name :"
                    label-cols-sm="5"
                    label-cols-lg="4">
        <b-form-input
            class="form-control form-control-solid h-auto"
            :class="{ 'is-invalid': form.errors.has('last_name')}"
            v-model="$v.form.last_name.$model"
            placeholder="Enter last name"
            :state="validateState('last_name')"
            aria-describedby="BrandName"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.last_name.required">
          {{ $t("message.register.last_name_required")}}
        </b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.form.last_name.maxLength">
          {{ $t("message.register.last_name_max_50")}}
        </b-form-invalid-feedback>
        <has-error :form="form" field="last_name"></has-error>
      </b-form-group>
      <b-form-group label="Email :"
                    label-cols-sm="5"
                    label-cols-lg="4">
        <b-form-input
            class="form-control form-control-solid h-auto"
            :class="{ 'is-invalid': form.errors.has('email')}"
            v-model="$v.form.email.$model"
            placeholder="Enter email address"
            :state="validateState('email')"
            aria-describedby="BrandName"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.email.required">
          {{ $t("message.register.email_required")}}
        </b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.form.email.maxLength">
          {{ $t("message.register.email_max_50")}}
        </b-form-invalid-feedback>
        <has-error :form="form" field="last_name"></has-error>
      </b-form-group>
      <b-form-group label="Phone number :"
                    label-cols-sm="5"
                    label-cols-lg="4">
        <b-form-input
            class="form-control form-control-solid h-auto"
            :class="{ 'is-invalid': form.errors.has('mobile')}"
            v-model="$v.form.mobile.$model"
            placeholder="Enter phone number"
            :state="validateState('mobile')"
            aria-describedby="BrandName"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.mobile.required">
          {{ $t("message.register.phone_number")}}
        </b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.form.mobile.maxLength">
          {{ $t("message.register.phone_number_max_50")}}
        </b-form-invalid-feedback>
        <has-error :form="form" field="phone"></has-error>
      </b-form-group>
      <b-form-group label="Select Membership plan" label-cols-sm="5"
                    label-cols-lg="4">
        <select v-model="form.membership_plan_id" name="membership_plan_id" class="form-control" placeholder="select membership plan">
          <option v-for="(plan,key) in membership_plans" :value="plan.id" :key="key">{{plan.name}}</option>
        </select>
      </b-form-group>
<!--      <b-form-group label="Profile photo :"
                    label-cols-sm="5"
                    label-cols-lg="4">
        <b-form-file
            accept="image/jpeg, image/png, image/jpg"
            placeholder="Choose brand logo 120x80"
            @change="onInputChange"
        ></b-form-file>
        <b-form-invalid-feedback id="fileErrorText" :state="validation"></b-form-invalid-feedback>
      </b-form-group>-->
      <CRow class="justify-content-end">
        <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
          <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
            <span v-if="form.busy" class="sr-only">{{ $t("message.brand.loading")}}</span>
            {{ editMode ? this.$t('message.brand.update') : this.$t('message.brand.submit') }}
          </CButton>
        </CCol>
        <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
          <CButton block color="dark" @click="$bvModal.hide('brandModal')">{{ $t("message.brand.close")}}</CButton>
        </CCol>
      </CRow>
    </b-form>
  </b-modal>
  <!-- End Modal -->
</template>
<script>
import {api_base_url} from "@/core/config/app";
import {required, maxLength,email} from "vuelidate/lib/validators";
import {validationMixin} from "vuelidate";
import ApiService from "../../core/services/api.service";
export default {
  name:'UserAddModal',
  mixins: [validationMixin],
  data(){
    return{
      editMode:false,
      validation: true,
      membership_plans:[],
      form: new Form({
        id: '',
        first_name:'',
        last_name:'',
        email:'',
        mobile:'',
        account_type:1,
        membership_plan_id: '',
      }),
    }
  },
  validations: {
    form: {
      first_name: {
        required,
        maxLength: maxLength(50)
      },
      last_name: {
        required,
        maxLength: maxLength(50)
      },
      email: {
        required,
        email,
        maxLength: maxLength(50)
      },
      mobile: {
        required,
        maxLength: maxLength(50)
      },
      account_type:{
        required,
      }
    }
  },
  created() {
    this.getMembershipPlan();
  },
  methods: {
    openModal() {
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('brandModal');
    },
    onInputChange(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText').text(files.name + this.$t('message.brand.not_image'));
        this.validation = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText').text(this.$t('message.brand.uploading_large_file'));
        this.validation = false;
        return;
      }
      this.validation = true;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.form.logo = reader.result
      }
      reader.readAsDataURL(files);
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    showImage(e) {
      return api_base_url + e;
    },
    createUser() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('admin/user/registration/from/admin')
          .then((response) => {
            //this.$store.commit(BRAND_ADD, data);
            this.form.reset();
            this.$bvModal.hide('brandModal');
            toast.fire({
              icon: this.$t('message.common.succes'),
              title: response.data.message
            });
            this.$store.dispatch('SELLER_LIST');
            this.$store.dispatch('CUSTOMER_LIST');
          }).catch((error)=>{
          toast.fire({
            icon: 'error',
            title: error.response.data.message
          });
      })
    },
    /*
    * get membership plan
    * */
    getMembershipPlan(){
      ApiService.get('get/only/membership/plan').then((response)=>{
        this.membership_plans=response.data;
      }).catch(()=>{
        this.membership_plans=[];
      })
    }
  }
}
</script>