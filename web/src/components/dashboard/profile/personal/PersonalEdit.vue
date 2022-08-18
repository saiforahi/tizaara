<template>
  <div class="card-body">
    <div class="container">
      <b-form @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="row p-2">
          <div class="col-md-4">
            <image-uploader :image="form.photo" @update-parent="updateParent"></image-uploader>
            <input type="hidden" :class="{ 'is-invalid': form.errors.has('image') }">
          </div>
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="first_name">
                  <label v-html="'First Name '+ star +':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fa fa-user-circle"></i>
                    </b-input-group-prepend>
                    <b-form-input
                        id="first_name" size="sm"
                        v-model="$v.form.first_name.$model" :state="validateState('first_name')"
                        :placeholder= "$t('message.personal_edit.enter_your_first_name')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.first_name.required">
                      {{ $t("message.personal_edit.first_name_required") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.first_name.maxLength">
                      {{ $t("message.personal_edit.first_name_255_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-2" label-for="last_name">
                  <label v-html="'Last name '+ star +':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fa fa-user-circle"></i>
                    </b-input-group-prepend>
                  <b-form-input
                      id="last_name" size="sm"
                      v-model="$v.form.last_name.$model" :state="validateState('last_name')"
                      :placeholder= "$t('message.personal_edit.enter_your_last_name')"
                  ></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.last_name.required">
                    {{ $t("message.personal_edit.last_name_required") }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-if="!$v.form.last_name.maxLength">
                    {{ $t("message.personal_edit.last_name_255_character") }}
                  </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-3" label="Job Title:" label-for="job_title">
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-briefcase"></i>
                    </b-input-group-prepend>
                  <b-form-input
                      id="job_title" size="sm"
                      v-model="$v.form.job_title.$model" :state="validateState('job_title')"
                      :placeholder= "$t('message.personal_edit.enter_your_job_title')"
                  ></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.job_title.maxLength">
                    {{ $t("message.personal_edit.job_title_255_character") }}
                  </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-4" label="Telephone No:" label-for="telephone_no">
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-phone-square-alt"></i>
                    </b-input-group-prepend>
                  <b-form-input
                      id="telephone_no" size="sm"
                      v-model="$v.form.telephone_no.$model" :state="validateState('telephone_no')"
                      :placeholder= "$t('message.personal_edit.enter_your_telephone_no')"
                  ></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.telephone_no.maxLength">
                    {{ $t("message.personal_edit.telephone_15_character") }}
                  </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
            </div>
          </div>
        </div>
        <div class="row p-2">
          <div class="col-md-4 my-2">
            <b-form-group id="input-group-5" label-for="country">
              <label v-html="'Select Country '+ star +':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-globe-asia"></i>
                </b-input-group-prepend>
              <b-form-select v-model="$v.form.country.$model" :options="Object.values(countryList)"
                             :state="validateState('country')" value-field="id" size="sm"
                             text-field="name" id="country" @input="countrySelect">
                <template v-slot:first>
                  <b-form-select-option value="" disabled>{{ $t("message.personal_edit.select_country") }}</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-invalid-feedback v-if="!$v.form.country.required">
                {{ $t("message.personal_edit.country_required") }}
              </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-2">
            <b-form-group id="input-group-6" label-for="division">
              <label v-html="'Select Division '+ star +':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-search-location"></i>
                </b-input-group-prepend>
              <b-form-select v-model="$v.form.division.$model" :options="divisionList"
                             :state="validateState('division')" value-field="id" size="sm"
                             text-field="name" id="division" @input="divisionSelect">
                <template v-slot:first>
                  <b-form-select-option value="" disabled>{{ $t("message.personal_edit.select_your_division") }}</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-invalid-feedback v-if="!$v.form.division.required">
                {{ $t("message.personal_edit.division_required") }}
              </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-2">
            <b-form-group id="input-group-7" label-for="city">
              <label v-html="'Select City '+ star +':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-building"></i>
                </b-input-group-prepend>
              <b-form-select v-model="$v.form.city.$model" :options="cityList"
                             :state="validateState('city')" value-field="id" size="sm"
                             text-field="name" id="city" @input="citySelect">
                <template v-slot:first>
                  <b-form-select-option value="" disabled>{{ $t("message.personal_edit.select_your_city") }}</b-form-select-option>
                </template>
              </b-form-select>
              <b-form-invalid-feedback v-if="!$v.form.city.required">
                {{ $t("message.personal_edit.city_required") }}
              </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-2">
            <b-form-group id="input-group-8" label="Select Area :" label-for="area">

              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-map-marked-alt"></i>
                </b-input-group-prepend>
              <b-form-select v-model="form.area" :options="areaList"
                              value-field="id" size="sm"
                             text-field="name" id="area">
                <template v-slot:first>
                  <b-form-select-option value="" disabled>{{ $t("message.personal_edit.select_your_area") }}</b-form-select-option>
                </template>
              </b-form-select>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-2">
            <b-form-group id="input-group-10" label="Zip code :" label-for="zip_code">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fab fa-usps"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="zip_code" size="sm" type="number"
                    :placeholder= "$t('message.personal_edit.enter_your_zip_code')"
                    v-model="$v.form.zip_code.$model"
                    :state="validateState('zip_code')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.zip_code.maxLength">
                  {{ $t("message.personal_edit.maximum_10_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-6 my-2">
            <b-form-group id="input-group-9" label-for="address">
              <label v-html="'Address ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-home"></i>
                </b-input-group-prepend>
              <b-form-textarea
                  id="address" size="sm"
                  placeholder= "Write your address here.."
                  rows="1" v-model="$v.form.address.$model"
                  :state="validateState('address')"
              ></b-form-textarea>
              <b-form-invalid-feedback v-if="!$v.form.address.required">
                {{ $t("message.personal_edit.leave_empty") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback v-if="!$v.form.address.maxLength">
                {{ $t("message.personal_edit.maximum_255_character") }}
              </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <b-row class="justify-content-end">
          <b-col cols="12" sm="8" md="6" lg="4" class="my-4">
            <b-button type="submit" variant="success" class="mr-2">{{ $t("message.personal_edit.save") }}</b-button>
            <b-button type="button" @click="$emit('view')" class="mr-2" variant="danger">{{ $t("message.personal_edit.cancel") }}</b-button>
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

export default {
  name: "PersonalEdit",
  mixins: [validationMixin],
  data() {
    return {
      star:'<span style="color:red;">*</span>',
      form: new Form({
        photo: '',
        first_name: '',
        last_name: '',
        job_title: '',
        telephone_no: '',
        country: '',
        division: '',
        city: '',
        area: '',
        address: '',
        zip_code: '',
      }),
      divisionList: [],
      cityList: [],
      areaList: [],
    }
  },
  validations: {
    form: {
      first_name: {
        required,
        maxLength: maxLength(255)
      },
      last_name: {
        required,
        maxLength: maxLength(255)
      },
      job_title: {
        maxLength: maxLength(255)
      },
      telephone_no: {
        maxLength: maxLength(15)
      },
      country: {
        required
      },
      division: {
        required
      },
      city: {
        required
      },
      address: {
        required,
        maxLength: maxLength(255)
      },
      zip_code: {
        maxLength: maxLength(10)
      },
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadPersonalInfo() {
      if (this.personalInfo.status === "old") {
        this.form.country = this.personalInfo.country;
        this.countrySelect(this.personalInfo.country);
        this.form.division = this.personalInfo.division;
        this.divisionSelect(this.personalInfo.division);
        this.form.city = this.personalInfo.city;
        this.citySelect(this.personalInfo.city);
        this.form.area = this.personalInfo.area;
        this.form.address = this.personalInfo.address;
        this.form.zip_code = this.personalInfo.zip_code;
      }
      this.form.first_name = this.personalInfo.first_name;
      this.form.last_name = this.personalInfo.last_name;
      this.form.job_title = this.personalInfo.job_title;
      this.form.telephone_no = this.personalInfo.telephone;
      this.personalInfo.photo_type == 0 ? this.form.photo = this.showImage(this.personalInfo.photo) : this.form.photo = this.personalInfo.photo;
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    updateParent(variable) {
      this.form.photo = variable;
    },
    countrySelect(e) {
      this.form.division = '';
      this.form.city = '';
      this.form.area = '';
      this.cityList = [];
      this.areaList = [];
      this.divisionList = this.getDivisionById(e);
    },
    divisionSelect(e) {
      this.form.city = '';
      this.form.area = '';
      this.areaList = [];
      this.cityList = this.getCityById(e);
    },
    citySelect(e) {
      this.form.area = '';
      this.areaList = this.getAreaById(e);
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('personal')
          .then((response) => {
            this.$toaster.success(response.data.message);
            this.$store.dispatch(PERSONAL_LIST);
            this.$emit('view');
          }).catch((error)=>{
        this.$toaster.error(error);
      });
    }
  },
  components: {ImageUploader},
  created() {
    this.loadPersonalInfo();
  },
  computed: {
    ...mapGetters(["countryList", "getDivisionById", "getCityById", "getAreaById"]),
    personalInfo() {
      return this.$store.getters.personalInfo;
    },
  }
}
</script>

<style scoped>

</style>