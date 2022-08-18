<template>
  <div class="card-body">
    <div class="container">
      <b-form @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="row p-2">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="name">
                  <label v-html="'Port Name '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-user"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.name.$model"
                        id="name" size="sm" :state="validateState('name')"
                        :placeholder= "$t('message.port_edit.name')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.name.required">
                      {{ $t("message.port_edit.name") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
                      {{ $t("message.port_edit.name_150_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
<!--              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="address">
                  <label v-html="'Port Address '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-map-marked-alt"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model="$v.form.address.$model"
                        id="address" size="sm" :state="validateState('address')"
                        :placeholder= "$t('message.port_edit.address')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.address.required">
                      {{ $t("message.port_edit.address") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.address.maxLength">
                      {{ $t("message.port_edit.address_255_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>-->
            </div>
          </div>
        </div>
        <b-row class="justify-content-end">
          <b-col cols="12" sm="8" md="6" lg="4" class="my-4">
            <b-button type="submit" variant="success" class="mr-2">{{ $t("message.port_edit.save") }}</b-button>
            <b-button type="button" @click="$emit('view')" variant="danger">{{ $t("message.port_edit.cancel") }}</b-button>
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
import {NEAREST_PORT_LIST} from "../../../../core/services/store/module/nearestPort";

export default {
  name: "PortAdd",
  mixins: [validationMixin],
  data() {
    return {
      star:'<span style="color:red;">*</span>',//for required red color star
      form: new Form({
        name: '',//port name is store here
        //address: '',//port address
      }),
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(150)
      },
      /*address: {
        required,
        maxLength: maxLength(255)
      },*/
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
      this.form.post('company/nearest/port/store')
          .then((response) => {
            this.$toaster.success(response.data.message);
            this.$store.dispatch(NEAREST_PORT_LIST);
            this.$emit('view');
          }).catch((error)=>{
        if (error.response.status==422) this.$toaster.error(error.response.data.message);
        else this.$toaster.error(error);
      })
    }
  },
}
</script>

<style scoped>

</style>