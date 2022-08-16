<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.logo.frontend_settings")}}
        </span>
        <h3 class="page-title">{{$t("message.logo.frontend_settings")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->


    <CRow class="justify-content-center my-4">
      <CCol md="12">
        <CCardGroup>
          <CCard class="p-4">
            <CForm @submit.prevent="updateGeneral()" @keydown="form.onKeydown($event)">
              <CRow class="my-3">
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t("message.logo.frontend_logo")}}</label>
                      <vue-upload-multiple-image
                          @upload-success="(formData, index, fileList) =>{ form.logo = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.logo = fileList}"
                          @before-remove="(index, done, fileList) =>{ done(); form.logo = fileList}"
                          :data-images="logo"
                          popupText="Frontend logo, you can edit and delete"
                          idUpload="myIdUpload" editUpload="myIdEdit" idEdit="myIdEdited"
                          dragText="Drag images (max height 40px)." browseText="Select single image"
                          primaryText="Logo" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t("message.logo.admin_logo")}}</label>
                      <vue-upload-multiple-image
                          @upload-success="(formData, index, fileList) =>{ form.admin_logo = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.admin_logo = fileList}"
                          @before-remove="(index, done, fileList) =>{ done(); form.admin_logo = fileList}"
                          :data-images="admin_logo"
                          popupText="Admin logo, you can edit and delete"
                          idUpload="myIdUpload1" editUpload="myIdEdit1" idEdit="myIdEdited1"
                          dragText="Drag images (max height 35px)." browseText="Select single image"
                          primaryText="Admin Logo" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
              </CRow>
              <CRow class="my-3">
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t("message.logo.admin_login_background")}}</label>
                      <vue-upload-multiple-image
                          @upload-success="(formData, index, fileList) =>{ form.admin_login_background = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.admin_login_background = fileList}"
                          @before-remove="(index, done, fileList) =>{ done(); form.admin_login_background = fileList}"
                          :data-images="admin_login_background"
                          popupText="Login page background, you can edit and delete"
                          idUpload="myIdUpload2" editUpload="myIdEdit2" idEdit="myIdEdited2"
                          dragText="Drag images (max height 40px)." browseText="Select single image"
                          primaryText="Login" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
                <CCol md="6">
                  <div role="group" class="d-flex justify-content-center">
                    <div class=""><label>{{$t("message.logo.favicon")}}</label>
                      <vue-upload-multiple-image
                          @upload-success="(formData, index, fileList) =>{ form.favicon = fileList}"
                          @edit-image="(formData, index, fileList) =>{ form.favicon = fileList}"
                          @before-remove="(index, done, fileList) =>{ done(); form.favicon = fileList}"
                          :data-images="favicon"
                          popupText="Title favicon icon, you can edit and delete"
                          idUpload="myIdUpload3" editUpload="myIdEdit3" idEdit="myIdEdited3"
                          dragText="Drag images (32x32)." browseText="Select single image"
                          primaryText="Favicon" accept="image/jpeg,image/png,image/bmp,image/jpg"
                          markIsPrimaryText="" :multiple="false"
                      ></vue-upload-multiple-image>
                    </div>
                  </div>
                </CCol>
              </CRow>
              <div class="form-group form-actions">
                <CButton :disabled="update" type="submit" color="primary" class="float-right">
                  <span v-if="update" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  {{$t("message.logo.update")}}
                </CButton>
              </div>
            </CForm>
          </CCard>
        </CCardGroup>
      </CCol>
    </CRow>

  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import {api_base_url} from "@/core/config/app";
import {GENERAL_LIST} from "@/core/services/store/general.module";

export default {
  name: "Logo",
  data() {
    return {
      form: new Form({
        id: '',
        logo: [],
        admin_logo: [],
        admin_login_background: [],
        favicon: [],
      }),
      update: false,
      logo: [],
      admin_logo: [],
      admin_login_background: [],
      favicon: [],
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadData() {
      ApiService.get('general-settings-logo')
          .then(({data}) => {
            this.logo.push({
              path: this.showImage(data.logo),
              default: 1,
              highlight: 1,
              caption: 'caption to display. receive',
            });
            this.admin_logo.push({
              path: this.showImage(data.admin_logo),
              default: 1,
              highlight: 1,
              caption: 'caption to display. receive',
            });
            this.admin_login_background.push({
              path: this.showImage(data.admin_login_background),
              default: 1,
              highlight: 1,
              caption: 'caption to display. receive',
            });
            this.favicon.push({
              path: this.showImage(data.favicon),
              default: 1,
              highlight: 1,
              caption: 'caption to display. receive',
            });
            this.form.id = data.id;
          })
          .catch(() => {
            swal.fire(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning');
          });
    },
    updateGeneral: function () {
      this.update = true;
      this.form.put('general-settings-logo/' + this.form.id)
          .then((e) => {
            this.form.reset();
            this.$store.dispatch(GENERAL_LIST)
            this.loadData();
            this.update = false;
            this.logo = [];
            this.admin_logo = [];
            this.admin_login_background = [];
            this.favicon = [];
            toast.fire({
              icon: 'success',
              title: this.$t("message.logo.logo_update_successfully")
            });
          })
          .catch((e) => {
            swal.fire(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning');
          })
    },
  },
  created() {
    this.loadData();
  },
  components: {
    VueUploadMultipleImage
  }
}
</script>

<style scoped>

</style>
