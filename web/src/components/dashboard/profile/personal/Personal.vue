<template>
  <div class="card" style="min-height: 400px;">
    <div class="card-header">
      {{ mode ? $t("message.personal.personal_info") : $t("message.personal.edit_your_personal_info") }}
      <a href="javascript:void(0)" v-if="mode && editText" @click.prevent="mode = !mode" class="float-right"><i
          class="fas fa-user-edit" style="color: #03a9f3"></i>
        {{ $t("message.personal.edit") }}</a>
    </div>
    <div v-if="mode" class="card-body">
      <div class="container">
        <div class="row">
          <div class="col-3 p-md-4">
            <image-uploader :image="form.profile_photo"  @update-parent="updateParent"></image-uploader>
            <input type="hidden" :class="{ 'is-invalid': form.errors.has('profile_photo') }">
          </div>
          <div class="col-9 p-md-4">
            <blockquote>
              <h5>{{ personalInfo.first_name + ' ' + personalInfo.last_name }}</h5>
              <small><cite title="Source Title">
                <i class="fas fa-map-marker-alt"></i></cite>
                {{ addressFilter(getDivisionNameById(personalInfo.division), 'name') }}
              </small>
            </blockquote>
            <p>
              <i class="fas fa-envelope"></i> {{ personalInfo.email }} <br>
              <i class="fas fa-phone"></i> {{ personalInfo.mobile }} <br>
            </p>
          </div>
        </div>
      </div>
    </div>
    <PersonalEdit v-if="!mode" @view="mode = !mode"/>
  </div>
</template>

<script>

import PersonalEdit from "@/components/dashboard/profile/personal/PersonalEdit";
import {mapGetters} from "vuex";
import {maxLength, required} from "vuelidate/lib/validators";
import ImageUploader from "@/components/helper/ImageUploader";
import {api_base_url} from "@/core/config/app";
import {PERSONAL_LIST} from "../../../../core/services/store/module/personal";

export default {

  name: "Personal",
  data() {
    return {
      mode: true,
      editText: false,
      form: new Form({
        profile_photo: '',
      }),
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    addressFilter(data, key) {
      return data ? data[key] : '';
    },
    updateParent(variable) {
      this.form.profile_photo = variable;
      this.photoUpload();
    },
    async photoUpload(){
       await this.form.post('user/photo/upload').then((response)=>{
         this.$toaster.success(response.data.message);
         this.$store.dispatch(PERSONAL_LIST);
      }).catch((error)=>{
         this.$toaster.error(error);
      });
    }
  },
  computed: {
    ...mapGetters(["getDivisionNameById", "countryList"]),
    personalInfo() {
      let ps = this.$store.getters.personalInfo;
      ps.photo_type == 0 ? this.form.profile_photo = this.showImage(ps.photo) : this.form.profile_photo = ps.photo;
      return ps;
    },
  },
  created() {
    if (this.countryList.length > 0) {
      this.editText = true;
    }
    this.personalInfo.photo_type == 0 ? this.form.profile_photo = this.showImage(this.personalInfo.photo) : this.form.profile_photo = this.personalInfo.photo;
  },
  watch: {
    countryList() {
      if (this.countryList.length > 0) {
        this.editText = true;
      }
    },
  },
  components: {
    PersonalEdit,ImageUploader,
  }


}
</script>

<style scoped>

</style>
