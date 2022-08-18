<template>
  <div class="card-body">
    <div class="container">
      <b-form @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="row p-2">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="name">
                  <label v-html="'Factory Name '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-building"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.name.$model"
                        id="certification_name" size="sm" :state="validateState('name')"
                        :placeholder= "$t('message.factory_edit.name')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.name.required">
                      {{ $t("message.factory_edit.name") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
                      {{ $t("message.factory_edit.name_100_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="factory_space">
                  <label v-html="'Factory Space '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-home"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.factory_space.$model"
                        id="factory_space" size="sm" :state="validateState('factory_space')"
                        :placeholder= "$t('message.factory_edit.factory_space')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.factory_space.required">
                      {{ $t("message.factory_edit.factory_space") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.factory_space.maxLength">
                      {{ $t("message.factory_edit.factory_space_250_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-7" label-for="rnd_staff_id">
                  <label v-html="'No of R & D Staff '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-user"></i>
                    </b-input-group-prepend>
                    <b-form-select :options="rnd_staffs" v-model="$v.form.rnd_staff_id.$model"
                                   :state="validateState('rnd_staff_id')" value-field="id" size="sm"
                                   text-field="name" id="rnd_staff_id">
                      <template v-slot:first>
                        <b-form-select-option value="" disabled>{{ $t("message.factory_edit.rnd_staff_id") }}</b-form-select-option>
                      </template>
                    </b-form-select>
                    <b-form-invalid-feedback v-if="!$v.form.rnd_staff_id.required">
                      {{ $t("message.factory_edit.rnd_staff_id") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-7" label-for="staff_number_id">
                  <label v-html="'No. of QC Staff '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-user-tie"></i>
                    </b-input-group-prepend>
                  <b-form-select :options="staff_numbers" v-model="$v.form.staff_number_id.$model"
                                 :state="validateState('staff_number_id')" value-field="id" size="sm"
                                 text-field="number" id="staff_number_id">
                    <template v-slot:first>
                      <b-form-select-option value="" disabled>{{ $t("message.factory_edit.staff_number_id") }}</b-form-select-option>
                    </template>
                  </b-form-select>
                  <b-form-invalid-feedback v-if="!$v.form.staff_number_id.required">
                    {{ $t("message.factory_edit.staff_number_id") }}
                  </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-7" label-for="production_line_id">
                  <label v-html="'No of Production Line '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-book"></i>
                    </b-input-group-prepend>
                  <b-form-select :options="production_lines" v-model="$v.form.production_line_id.$model"
                                 :state="validateState('production_line_id')" value-field="id" size="sm"
                                 text-field="name" id="production_line_id">
                    <template v-slot:first>
                      <b-form-select-option value="" disabled>{{ $t("message.factory_edit.production_line_id") }}</b-form-select-option>
                    </template>
                  </b-form-select>
                  <b-form-invalid-feedback v-if="!$v.form.production_line_id.required">
                    {{ $t("message.factory_edit.production_line_id") }}
                  </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-7" label-for="annual_output_id">
                  <label v-html="'Annual output Value '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-money-bill-alt"></i>
                    </b-input-group-prepend>
                    <b-form-select :options="annual_outputs" v-model="$v.form.annual_output_id.$model"
                                   :state="validateState('annual_output_id')" value-field="id" size="sm"
                                   text-field="output" id="annual_output_id">
                      <template v-slot:first>
                        <b-form-select-option value="" disabled>{{ $t("message.factory_edit.annual_output_id") }}</b-form-select-option>
                      </template>
                    </b-form-select>
                    <b-form-invalid-feedback v-if="!$v.form.annual_output_id.required">
                      {{ $t("message.factory_edit.annual_output_id") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
            </div>
          </div>
        </div>
        <b-row class="justify-content-end">
          <b-col cols="12" sm="8" md="6" lg="4" class="my-4">
            <b-button type="submit" variant="success" class="mr-2">{{ $t("message.factory_edit.save") }}</b-button>
            <b-button type="button" @click="$emit('view')" variant="danger">{{ $t("message.factory_edit.cancel") }}</b-button>
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
import {COMPANY_FACTORY_LIST} from "../../../../core/services/store/module/factory";

export default {
  name: "CertificateAdd",
  mixins: [validationMixin],
  data() {
    return {
      star:'<span style="color:red;">*</span>',//for required red color star
      form: new Form({
        name: '',//store factory name
        factory_space: '',//store factory space info
        staff_number_id: null,//store selected staff number id
        rnd_staff_id: null,//store selected rnd number id
        production_line_id: null,//store production line id
        annual_output_id: null,//store annual output id id
      }),
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(100)
      },
      factory_space: {
        required,
        maxLength: maxLength(250)
      },
      staff_number_id: {
        required,
      },
      rnd_staff_id: {
        required,
      },
      production_line_id: {
        required,
      },
      annual_output_id: {
        required,
      },
    }
  },
  methods: {
    validateState(name){
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('company/factory/info/store')
          .then((response) => {
            this.$toaster.success(response.data.message);
            this.$store.dispatch(COMPANY_FACTORY_LIST);
            this.$emit('view');
          }).catch((error)=>{
          if (error.response.status==422) this.$toaster.error(error.response.data.message);
          else this.$toaster.error(error);
      })
    }
  },
  components: {ImageUploader},
  computed: {
    ...mapGetters(["staff_numbers","rnd_staffs","production_lines","annual_outputs"]),
  }
}
</script>

<style scoped>

</style>