<template>
  <div class="content pr-md-5">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="container">
          <!-- Page Header -->
          <div class="row mb-3">
            <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
              <h3 class="page-title">{{ 'Password Change' }}</h3>
            </div>
          </div>
          <!-- End Page Header -->
          <hr>
            <div class=" justify-content-center col-md-12">
              <form action="javascript:void(0)" method="post" ref="password_change_form" @submit="changePass">
                <div class="col-md-6 form-group">
                  <label>Current password</label>
                  <b-form-input name="password" class="form-control" type="password" v-model="form.old_password" label="Current password" placeholder="enter current password" required></b-form-input>
                </div>
                <div class="col-md-6">
                  <label>New password</label>
                  <b-form-input name="new_password" min="8" type="password" v-model="form.password" label="new password" placeholder="enter new password" required></b-form-input>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Confirm password</label>
                  <b-form-input name="conform_password" type="password" v-model="form.password_confirmation" label="Conform password" placeholder="enter confirm password" required></b-form-input>
                </div>
                <div class="justify-content-end">
                  <button type="submit" class="btn btn-primary ml-10">Submit</button>
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
export default {
  mixins: [validationMixin],
  name: "PasswordChange",
  data(){
    return{
      form:new Form({
        password:'',
        old_password:'',
        password_confirmation:'',
        busy:false,
      })
    }
  },
  methods: {
    changePass(){
      this.form.post('user/password/change').then((response)=>{
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
    }
  },

}
</script>
