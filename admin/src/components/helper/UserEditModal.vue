<template>
  <!-- Modal -->
  <b-modal id="userUpdateModal" :title="'Update an account'" hide-footer>
    <b-form @submit.prevent="updateUser()" @keydown="form.onKeydown($event)">
      <b-form-radio-group label="Crate as " v-slot="{ ariaDescribedby }"
                          v-model.number="$v.form.account_type.$model"
                          :state="validateState('account_type')">
        <label class="mr-2">User role </label>
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
          <CButton block color="dark" @click="$bvModal.hide('userUpdateModal')">{{ $t("message.brand.close")}}</CButton>
        </CCol>
      </CRow>
    </b-form>
  </b-modal>
  <!-- End Modal -->
</template>
<script>
import {required, maxLength,email} from "vuelidate/lib/validators";
import {validationMixin} from "vuelidate";
import ApiService from "../../core/services/api.service";
export default {
  name:'UserEditModal',
  mixins: [validationMixin],
  props:{
    user_info:{
      type:Object,
      required:true,
    },
  },
  data(){
    return{
      editMode:false,
      validation: true,
      membership_plans:[],
      form: new Form({
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
        email,
        maxLength: maxLength(50)
      },
      mobile: {
        maxLength: maxLength(50)
      },
      account_type:{
        required,
      }
    }
  },
  created() {
    this.getMembershipPlan();
    
    this.form.first_name=this.user_info.first_name;
    this.form.last_name=this.user_info.last_name;
    this.form.email=this.user_info.email;
    this.form.mobile=this.user_info.mobile;
    this.form.account_type=this.user_info.account_type;
    this.form.membership_plan_id=this.user_info.membership_plan_id;
  },
  methods: {
    openModal() {
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('userUpdateModal');
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    updateUser() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post(`admin/user/information/update/from/admin/${this.user_info.id}`)
          .then((response) => {
            //this.$store.commit(BRAND_ADD, data);
            this.form.reset();
            this.$bvModal.hide('userUpdateModal');
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
  },
  watch:{
    user_info(){
      console.log('member',this.user_info.membership_plan_id)
      this.form.first_name=this.user_info.first_name;
      this.form.last_name=this.user_info.last_name;
      this.form.email=this.user_info.email;
      this.form.mobile=this.user_info.mobile;
      this.form.account_type=this.user_info.account_type;
      this.form.membership_plan_id=this.user_info.membership_plan_id;
    },
  }
}
</script>