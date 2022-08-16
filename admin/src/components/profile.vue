<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title mb-4">
              <div class="d-flex justify-content-center">
                <div class="image-container">
                 <img :src="profile_photo" alt="" width="100" height="100">
                  <input ref="p_input" id="p_input" @change="photoUpload" type="file" hidden>
                  <button @click="photoField()">Change</button>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="tab-content ml-1" id="myTabContent">
                  <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
                    <div class="row">
                      <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Name Name: </label>
                      </div>
                      <div class="col-md-8 col-6">
                        {{currentUser.name}}
                      </div>
                    </div>
                    <hr />
                    <div class="row">
                      <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Email: </label>
                      </div>
                      <div class="col-md-8 col-6">
                        {{currentUser.email}}
                      </div>
                    </div>
                    <hr />
                    <div class="row">
                      <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Phone Number: </label>
                      </div>
                      <div class="col-md-8 col-6">
                        {{currentUser.phone_number}}
                      </div>
                    </div>
                    <hr />
                    <div class="row">
                      <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Address: </label>
                      </div>
                      <div class="col-md-8 col-6">
                        {{ currentUser.address }}
                      </div>
                    </div>
                    <hr />

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import 'vue-multi-select/dist/lib/vue-multi-select.css';
import {mapGetters} from "vuex";
import {api_base_url} from "../core/config/app";
import ApiService from "../core/services/api.service";
export default {
  name: "Profile",
  data() {
    return {
      profile_photo:null
    }
  },
  methods: {
    photoField(){
      $('#p_input').click();
    },
    photoUpload(){
      let data = new FormData();
      data.append('image',this.$refs.p_input.files[0]);
      ApiService.post('admin/profile/image/change',data).then((response)=>{
        this.$toaster.success(response.data.message);
          this.currentUser.photo= response.data.data;
      }).catch((error)=>{
        this.$toaster.error(error);
      });
    }
  },
  created() {
    if (this.currentUser.photo) this.profile_photo = {path:api_base_url+this.currentUser.photo};
  },
  computed: {
    ...mapGetters(["currentUser"])
  },
}
</script>

<style scoped>

</style>