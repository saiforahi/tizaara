<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-sm-left mb-4 mb-sm-0">
        <div class="card">
          <div class="card-header">
            <h5>{{$t("message.commission.seller_commission")}}</h5>
          </div>
          <div class="card-body">
            <b-form @submit.prevent="updateData()" @keydown="form.onKeydown($event)">
              <b-form-group label="Seller commission :"
                            label-cols-sm="5" description="Commission percent"
                            label-cols-lg="4">
                <b-form-input
                    class="form-control form-control-solid h-auto"
                    v-model="$v.form.seller_commission.$model"
                    placeholder="Enter Amount"
                    type="number" min="0" step="0.01"
                    :state="validateState('seller_commission')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.seller_commission.required">
                  {{$t("message.commission.amount_required")}}
                </b-form-invalid-feedback>
              </b-form-group>
              <CButton color="info" type="submit" :disabled="form.busy" class="float-right">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
                <span v-if="form.busy" class="sr-only">{{$t("message.commission.loading")}}</span>
                Update
              </CButton>
            </b-form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Page Header -->


  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {required} from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  name: "Commission",
  data() {
    return {
      validation: true,
      editMode: false,
      form: new Form({
        seller_commission: '',
      }),
    }
  },
  validations: {
    form: {
      seller_commission: {
        required
      },
    }
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    updateData() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('seller-package/' + this.form.id)
          .then(({data}) => {
            this.$store.commit('SELLER_PACKAGE_MODIFY', data);
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('ourModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.commission.seller_packages_update_successfully")
            });
          })
    },
  }
}
</script>

<style scoped>

</style>
