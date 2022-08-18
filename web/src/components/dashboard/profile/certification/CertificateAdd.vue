<template>
  <div class="card-body">
    <div class="container">
      <b-form @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="row p-2">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="certification_name">
                  <label v-html="'Certification Name '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-book"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.name.$model"
                        id="certification_name" size="sm" :state="validateState('name')"
                        :placeholder= "$t('message.certification_edit.certificate_name')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.name.required">
                      {{ $t("message.certification_edit.certificate_name") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
                      {{ $t("message.certification_edit.certificate_name_150_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="reference_number">
                  <label v-html="'Reference No '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="far fa-address-card"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.reference_number.$model"
                        id="reference_number" size="sm" :state="validateState('reference_number')"
                        :placeholder= "$t('message.certification_edit.reference_number')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.reference_number.required">
                      {{ $t("message.certification_edit.reference_number") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.reference_number.maxLength">
                      {{ $t("message.certification_edit.reference_number_15_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-4 my-2">
                <b-form-group id="input-group-1" label-for="issued_by">
                  <label v-html="'Issued By '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-user-tie"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.issued_by.$model"
                        id="issued_by" size="sm" :state="validateState('issued_by')"
                        :placeholder= "$t('message.certification_edit.issued_by')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.issued_by.required">
                      {{ $t("message.certification_edit.issued_by") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.issued_by.maxLength">
                      {{ $t("message.certification_edit.issued_by_150_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-4 my-2">
                <b-form-group id="input-group-1" label-for="start_date">
                  <label v-html="'Start date(Validity period Form) '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-calendar-alt"></i>
                    </b-input-group-prepend>
                    <b-form-input type="date" v-model="$v.form.start_date.$model"
                                  id="start_date" size="sm" :state="validateState('start_date')"
                                  :placeholder= "$t('message.certification_edit.start_date')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.start_date.required">
                      {{ $t("message.certification_edit.start_date") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-4 my-2">
                <b-form-group id="input-group-1" label-for="end_date">
                  <label v-html="'End Date(Validity Period To) '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-calendar-alt"></i>
                    </b-input-group-prepend>
                    <b-form-input type="date" v-model="$v.form.end_date.$model"
                                  id="end_date" size="sm" :state="validateState('end_date')"
                                  :placeholder= "$t('message.certification_edit.end_date')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.end_date.required">
                      {{ $t("message.certification_edit.end_date") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-4">
                <label>Certificate image</label>
                <image-uploader :image="form.photo" @update-parent="updateParent"></image-uploader>
                <input type="hidden" :class="{ 'is-invalid': form.errors.has('image') }">
              </div>
            </div>
          </div>
        </div>
        <b-row class="justify-content-end">
          <b-col cols="12" sm="8" md="6" lg="4" class="my-4">
            <b-button type="submit" variant="success" class="mr-2">{{ $t("message.certification_edit.save") }}</b-button>
            <b-button type="button" @click="$emit('view')" variant="danger">{{ $t("message.certification_edit.cancel") }}</b-button>
          </b-col>
        </b-row>
      </b-form>
    </div>
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {required, maxLength} from "vuelidate/lib/validators";
import ImageUploader from "../../../helper/ImageUploader";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import {PERSONAL_LIST} from "@/core/services/store/module/personal";
import {CERTIFICATE_LIST} from "../../../../core/services/store/module/certificate";

export default {
  name: "CertificateAdd",
  mixins: [validationMixin],
  data() {
    return {
      star:'<span style="color:red;">*</span>',//for required red color star
      form: new Form({
        photo: '',
        name: '',
        reference_number: '',
        issued_by: '',
        start_date: '',
        end_date: '',
      }),
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(150)
      },
      reference_number: {
        required,
        maxLength: maxLength(15)
      },
      issued_by: {
        required,
        maxLength: maxLength(150)
      },
      start_date: {
        required,
      },
      end_date: {
        required,
      },
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    updateParent(variable) {
      this.form.photo = variable;
    },
    validateState(name){
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('certificate/store')
          .then((response) => {
            this.$toaster.success(response.data.message);
            this.$store.dispatch(CERTIFICATE_LIST)
            this.$emit('view');
          }).catch((error)=>{
        if (error.response.status==422) this.$toaster.error(error.response.data.errors[0]);
        else this.$toaster.error(error);
      })
    }
  },
  components: {ImageUploader},
  computed: {
    ...mapGetters([]),
    personalInfo() {
      return this.$store.getters.personalInfo;
    },
  }
}
</script>

<style scoped>

</style>