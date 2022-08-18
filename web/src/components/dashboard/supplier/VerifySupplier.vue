<template>
  <div class="content pr-md-5">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="container">
          <!-- Page Header -->
          <div class="row mb-3">
            <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
              <h3 class="page-title">{{ 'Verify Request' }}</h3>
            </div>
          </div>
          <!-- End Page Header -->
          <hr>
            <div v-if="basic.is_verified===1">
              <img :src="showImage('verified.png')" width="30%" height="25px" alt="verified" />
            </div>
            <div v-if="basic.is_verified===2">
              <p style="color:red">Your verification request is declined</p>
            </div>
            <div v-else class="justify-content-center col-md-12">
              <form action="javascript:void(0)" method="post" ref="password_change_form" @submit="changePass">
                <div class="col-md-6 mb-3">
                  <label>Message</label>
                  <b-form-textarea name="conform_password" type="password" rows="5" v-model="form.message"
                                   label="Message" placeholder="Write your message here" required></b-form-textarea>
                </div>
                <div class="justify-content-end">
                  <b-button type="submit" class="btn btn-primary ml-3">Request Sent</b-button>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {COMPANY_BASIC_LIST} from "../../../core/services/store/module/companybasic";
import {mapGetters} from "vuex";
import {api_base_url} from "../../../core/config/app";
export default {
  mixins: [validationMixin],
  name: "VerifyMe",
  data(){
    return{
      form:new Form({
        message:'',
        busy:false,
      }),
      basic:{},
    }
  },
  methods: {
    changePass(){
      this.form.post('user/verification/request/store').then((response)=>{
        if (response.data.status ==='success'){
          this.$refs.password_change_form.reset();
          this.$toaster.success(response.data.message);
        }else{
          this.$toaster.error(response.data.message);
        }
      }).catch((error)=>{
        if (error.response.status ==422) {
          Object.keys(error.response.data.errors).map((field) => {
            this.$toaster.error(error.response.data.errors[field][0]);
          })
        }
        else this.$toaster.error(error.response.data.message);
      })
    },
    cmpBasic(){
      this.basic=this.companyBasicInfoByUserID(this.currentUser.id);
    },
    showImage(e) {
      return api_base_url + e;
    },
  },
  computed:{
    ...mapGetters(["companyBasicInfo", "companyBasicInfoByUserID","isAuthenticated","currentUser"]),
  },
  created(){
    this.$store.dispatch(COMPANY_BASIC_LIST);
    this.cmpBasic();
  },
  watch:{
    currentUser(){
      this.cmpBasic();
    }
  }

}
</script>
