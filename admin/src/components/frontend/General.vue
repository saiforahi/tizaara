<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.general.frontend_settings")}}
        </span>
        <h3 class="page-title">{{ $t("message.general.general_settings")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->

    <CRow class="justify-content-center my-4">
      <CCol md="6">
        <CCardGroup>
          <CCard class="p-4">
            <CForm @submit.prevent="updateGeneral()" @keydown="form.onKeydown($event)">
              <CInput label="Site Name :" v-model="$v.form.site_name.$model"
                      horizontal placeholder="Enter Site Name" :disabled="editMode"
                      :invalidFeedback="!$v.form.site_name.required? this.$t('message.general.site_name_required'): $v.form.site_name.maxLength?'':this.$t('message.general.site_name_character') "
                      :isValid="validateState('site_name')"/>

              <CTextarea label="Address" v-model="$v.form.address.$model"
                         placeholder="Enter Address" horizontal :disabled="editMode"
                         :invalidFeedback="!$v.form.address.required? this.$t('message.general.address_required'): $v.form.address.maxLength?'': this.$t('message.general.address_character') "
                         rows="4" :isValid="validateState('address')"/>


              <CInput label="Footer Text :" v-model="$v.form.footer_text.$model"
                      horizontal placeholder="Enter Footer Text" :disabled="editMode"
                      :invalidFeedback="!$v.form.footer_text.required? this.$t('message.general.footer_text_required'): $v.form.footer_text.maxLength?'': this.$t('message.general.footer_text_character') "
                      :isValid="validateState('footer_text')"/>

              <CInput label="Phone :" v-model="$v.form.phone.$model"
                      horizontal placeholder="Enter Phone Number" :disabled="editMode"
                      :invalidFeedback="!$v.form.phone.required? this.$t('message.general.phone_number_required'): $v.form.phone.maxLength?'': this.$t('message.general.phone_number_character') "
                      :isValid="validateState('phone')"/>

              <CInput label="Email :" v-model="$v.form.email.$model" type="email"
                      horizontal placeholder="Enter Email Address" :disabled="editMode"
                      :invalidFeedback="!$v.form.email.required? this.$t('message.general.email_address_required'): $v.form.email.maxLength?'': this.$t('message.general.email_address_character') "
                      :isValid="validateState('email')"/>

              <CInput label="Facebook :" v-model="$v.form.facebook.$model"
                      horizontal placeholder="Enter Facebook Url" :disabled="editMode"
                      :invalidFeedback="!$v.form.facebook.required? this.$t('message.general.facebook_url_required'): $v.form.facebook.maxLength?'': this.$t('message.general.facebook_character') "
                      :isValid="validateState('facebook')"/>

              <CInput label="Instagram :" v-model="$v.form.instagram.$model"
                      horizontal placeholder="Enter Instagram Url" :disabled="editMode"
                      :invalidFeedback="!$v.form.instagram.required? this.$t('message.general.instagram_required'): $v.form.instagram.maxLength?'': this.$t('message.general.instagram_character') "
                      :isValid="validateState('instagram')"/>

              <CInput label="Twitter :" v-model="$v.form.twitter.$model"
                      horizontal placeholder="Enter Twitter Url" :disabled="editMode"
                      :invalidFeedback="!$v.form.twitter.required? this.$t('message.general.twitter_required'): $v.form.twitter.maxLength?'': this.$t('message.general.twitter_character') "
                      :isValid="validateState('twitter')"/>

              <CInput label="Youtube :" v-model="$v.form.youtube.$model"
                      horizontal placeholder="Enter Youtube Url" :disabled="editMode"
                      :invalidFeedback="!$v.form.youtube.required? this.$t('message.general.youtube_required'): $v.form.youtube.maxLength?'': this.$t('message.general.youtube_character') "
                      :isValid="validateState('youtube')"/>

              <CInput label="Google Plus :" v-model="$v.form.google_plus.$model"
                      horizontal placeholder="Enter Google Plus Url" :disabled="editMode"
                      :invalidFeedback="!$v.form.google_plus.required? this.$t('message.general.google_required'): $v.form.google_plus.maxLength?'': this.$t('message.general.google_character') "
                      :isValid="validateState('google_plus')"/>


              <div class="form-group form-actions">
                <CButton :disabled="update" type="submit" v-if="!editMode" color="primary" class="float-right">
                  <span v-if="update" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Update
                </CButton>

                <a v-if="editMode" @click="editMode = false" class="btn btn-secondary float-right">
                  Edit
                </a>
              </div>
            </CForm>
          </CCard>
        </CCardGroup>
      </CCol>
    </CRow>
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import ApiService from "@/core/services/api.service";
import {GENERAL_LIST} from "@/core/services/store/general.module";

export default {
  mixins: [validationMixin],
  name: "General",
  data() {
    return {
      form: new Form({
        id: '',
        site_name: '',
        address: '',
        phone: '',
        email: '',
        facebook: '',
        instagram: '',
        twitter: '',
        youtube: '',
        google_plus: '',
        footer_text: '',
      }),
      editMode: true,
      update: false,
    }
  },
  validations: {
    form: {
      site_name: {
        required,
        maxLength: maxLength(255)
      },
      address: {
        required,
        maxLength: maxLength(1000)
      },
      phone: {
        required,
        maxLength: maxLength(100)
      },
      email: {
        required,
        maxLength: maxLength(255)
      },
      facebook: {
        required,
        maxLength: maxLength(255)
      },
      instagram: {
        required,
        maxLength: maxLength(255)
      },
      twitter: {
        required,
        maxLength: maxLength(255)
      },
      youtube: {
        required,
        maxLength: maxLength(255)
      },
      google_plus: {
        required,
        maxLength: maxLength(255)
      },
      footer_text: {
        required,
        maxLength: maxLength(255)
      },
    }
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    loadData() {
      ApiService.get('general-settings')
          .then(({data}) => {
            this.form.fill(data);
          })
          .catch(() => {
            swal.fire(this.$t('message.common.error'), this.$t('message.common.something_wrong'), 'warning');
          });
    },
    updateGeneral: function () {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.update = true;
      this.form.put('general-settings/' + this.form.id)
          .then((e) => {
            this.$v.$reset();
            this.$store.dispatch(GENERAL_LIST)
            this.form.reset();
            this.loadData();
            this.editMode = true;
            this.update = false;
            toast.fire({
              icon: 'success',
              title: this.$t('message.general.general_update_successfully')
            });
          })
          .catch((e) => {
            swal.fire(this.$t('message.common.error'), this.$t('message.common.something_wrong'), 'warning');
          })
    }
  },
  created() {
    this.loadData();
  }
}
</script>

<style scoped>

</style>
