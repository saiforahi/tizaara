<template>
  <div class="card-body">
    <div class="container">
      <b-form @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="row p-2">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-1" label-for="export_started_year">
                  <label v-html="'Export Started year '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-vote-yea"></i>
                    </b-input-group-prepend>
                    <b-form-input v-model.number="$v.form.export_started_year.$model" type="number"
                        id="export_started_year" size="sm" :state="validateState('export_started_year')"
                        :placeholder= "$t('message.trade_edit.export_started_year')"
                    ></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.form.export_started_year.required">
                      {{ $t("message.trade_edit.export_started_year") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.form.export_started_year.maxLength">
                      {{ $t("message.trade_edit.export_started_year_4_character") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-7" label-for="annual_revenue_id">
                  <label v-html="'Annual Revenue '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-money-bill-alt"></i>
                    </b-input-group-prepend>
                    <b-form-select :options="annual_revenues" v-model="$v.form.annual_revenue_id.$model"
                                   :state="validateState('annual_revenue_id')" value-field="id" size="sm"
                                   text-field="revenue" id="annual_revenue_id">
                      <template v-slot:first>
                        <b-form-select-option value="" disabled>{{ $t("message.trade_edit.annual_revenue_id") }}</b-form-select-option>
                      </template>
                    </b-form-select>
                    <b-form-invalid-feedback v-if="!$v.form.annual_revenue_id.required">
                      {{ $t("message.trade_edit.annual_revenue_id") }}
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
              <div class="col-md-6 my-2">
                <b-form-group id="input-group-7" label-for="export_percent_id">
                  <label v-html="'Export percentage '+star+':'"></label>
                  <b-input-group size="sm">
                    <b-input-group-prepend is-text>
                      <i class="fas fa-retweet"></i>
                    </b-input-group-prepend>
                  <b-form-select :options="export_percents" v-model="$v.form.export_percent_id.$model"
                                 :state="validateState('export_percent_id')" value-field="id" size="sm"
                                 text-field="percent" id="export_percent_id">
                    <template v-slot:first>
                      <b-form-select-option value="" disabled>{{ $t("message.trade_edit.export_percent_id") }}</b-form-select-option>
                    </template>
                  </b-form-select>
                  <b-form-invalid-feedback v-if="!$v.form.export_percent_id.required">
                    {{ $t("message.trade_edit.export_percent_id") }}
                  </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </div>
            </div>
          </div>
        </div>
        <b-row class="justify-content-end">
          <b-col cols="12" sm="8" md="6" lg="4" class="my-4">
            <b-button type="submit" variant="success" class="mr-2">{{ $t("message.trade_edit.save") }}</b-button>
            <b-button type="button" @click="$emit('view')" variant="danger">{{ $t("message.trade_edit.cancel") }}</b-button>
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
import {COMPANY_TRADE_LIST} from "../../../../core/services/store/module/companyTrade";

export default {
  name: "TradeEdit",
  mixins: [validationMixin],
  props:{
    trade:{
      type:Object,
      required:true,
    }
  },
  data() {
    return {
      star:'<span style="color:red;">*</span>',//for required red color star
      form: new Form({
        export_started_year: '',//store export stared year
        annual_revenue_id: null,//store annual revenue id
        export_percent_id: null,//store export percentage id
      }),
    }
  },
  validations: {
    form: {
      export_started_year: {
        required,
        maxLength: maxLength(4)
      },
      annual_revenue_id: {
        required,
      },
      export_percent_id: {
        required,
      },
    }
  },
  created() {
    this.form.export_started_year=this.trade.export_started_year;
    this.form.annual_revenue_id=this.trade.annual_revenue_id;
    this.form.export_percent_id=this.trade.export_percent_id;
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
      this.form.put(`company/trade/info/update${this.trade.id}`)
          .then((response) => {
            this.$toaster.success(response.data.message);
            this.$store.dispatch(COMPANY_TRADE_LIST);
            this.$emit('view');
          }).catch((error)=>{
          if (error.response.status==422) this.$toaster.error(error.response.data.message);
          else if(error.response.status==403) this.$toaster.error(error.data.message);
          else this.$toaster.error(error);
      })
    }
  },
  computed: {
    ...mapGetters(["annual_revenues","export_percents"]),
  }
}
</script>

<style scoped>

</style>