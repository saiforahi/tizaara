<template>
  <b-modal id="ourModal" :title="editMode ? this.$t('message.modal.update_membership_plan_information'): this.$t('message.modal.create_new_seller_membership_plan')" hide-footer>
    <b-form @submit.prevent="editMode ? updateData(): createData()" @keydown="form.onKeydown($event)">
      <b-form-group label="Plan Name :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{'is-invalid': form.errors.has('name')}"
                id="BrandName-1"
                v-model="$v.form.name.$model"
                placeholder="Enter Plan Name"
                :state="validateState('name')"
                aria-describedby="BrandName"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.name.required">
          {{$t('message.modal.plan_name_required')}}
        </b-form-invalid-feedback>
        <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
          {{$t('message.modal.plan_name_character')}}
        </b-form-invalid-feedback>
        <has-error :form="form" field="name"></has-error>
      </b-form-group>
      <b-form-group label="Duration :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                v-model="form.duration"
                placeholder="Validity in number of days"
                type="number" min="0" step="1"
        ></b-form-input>
      </b-form-group>
      <b-form-group label="Buffer time :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                v-model="form.buffer_time"
                placeholder="Validity in number of days"
                type="number" min="0" step="1"
        ></b-form-input>
      </b-form-group>
      <b-form-group label="No of allowed products :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                v-model="$v.form.no_of_allowed_products.$model"
                placeholder="Enter number"
                type="number" min="0" step="1"
                :state="validateState('no_of_allowed_products')"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.no_of_allowed_products.required">
          {{$t('message.modal.no_products_required')}}
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label="No of allowed keywords :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                v-model="$v.form.no_of_allowed_keywords.$model"
                placeholder="Enter number"
                type="number" min="0" step="1"
                :state="validateState('no_of_allowed_keywords')"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.no_of_allowed_keywords.required">
          {{$t('message.modal.no_keywords_required')}}
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label="No of allowed RFQ :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                v-model="form.no_of_allowed_rfq"
                placeholder="Enter number"
                type="number" min="0" step="1"
        ></b-form-input>
      </b-form-group>
      <b-form-group label="No of top adds :" label-cols-sm="5">
        <b-form-input
                class="form-control form-control-solid h-auto"
                v-model="form.no_of_top_adds"
                placeholder="Enter number"
                type="number" min="0" step="1"
        ></b-form-input>
      </b-form-group>
      <b-form-group label="Amount :"
                    label-cols-sm="5">
        <b-form-input
            class="form-control form-control-solid h-auto"
            v-model="$v.form.amount.$model"
            placeholder="Enter Amount"
            type="number" min="0" step="0.01"
            :state="validateState('amount')"
        ></b-form-input>
        <b-form-invalid-feedback v-if="!$v.form.amount.required">
          {{$t("message.packages.amount_required")}}
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label="Others Benefits :" label-cols-sm="5">
        <ckeditor :editor="editor" v-model="form.benefit" :config="editorConfig"></ckeditor>
      </b-form-group>
      <CRow class="justify-content-end">
        <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
          <CButton block color="info" type="submit" :disabled="form.busy">
            <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span v-if="form.busy" class="sr-only">{{$t('message.modal.loading')}}</span>
            {{ editMode ? this.$t('message.modal.update') : this.$t('message.modal.submit') }}
          </CButton>
        </CCol>
        <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
          <CButton block color="dark" @click="$bvModal.hide('ourModal')">{{$t('message.modal.close')}}</CButton>
        </CCol>
      </CRow>
    </b-form>
  </b-modal>
</template>

<script>
  import {validationMixin} from 'vuelidate'
  import {maxLength, required} from 'vuelidate/lib/validators'
  import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

  export default {
    name: 'Modal',
    mixins: [validationMixin],
    data() {
      return {
        validation: true,
        editMode: false,
        form: new Form({
          id: '',
          name: '',
          benefit: '',
          duration: '',
          buffer_time: '',
          no_of_allowed_products: '',
          no_of_allowed_keywords: '',
          no_of_allowed_rfq: '',
          no_of_top_adds: '',
          amount: '',
        }),
        editor: ClassicEditor,
        editorConfig: {},
      }
    },
    validations: {
      form: {
        name: {
          required,
          maxLength: maxLength(50)
        },
        no_of_allowed_products: {
          required
        },
        no_of_allowed_keywords: {
          required
        },
        amount: {
          required
        },
      }
    },
    methods: {
      validateState(name) {
        const {$dirty, $error} = this.$v.form[name];
        return $dirty ? !$error : null;
      },
      openModal() {
        this.validation = true;
        this.$v.$reset();
        this.form.reset();
        this.form.clear();
        this.editMode = false;
        this.$bvModal.show('ourModal');
      },
      openModalEdit(data) {
        this.$store.commit('set', ['isLoading', true])
        this.validation = true;
        this.$v.$reset();
        this.form.reset();
        this.form.clear();
        this.form.get(`membership-plan/${data.id}`)
          .then(({data}) => {
            this.form.fill(data);
            this.editMode = true;
            this.$store.commit('set', ['isLoading', false])
            this.$bvModal.show('ourModal');
          })
      },
      createData() {
        this.$v.form.$touch();
        if (this.$v.form.$anyError) {
          return;
        }
        this.form.post('membership-plan')
          .then(() => {
            this.$emit('success');
            this.form.reset();
            this.$bvModal.hide('ourModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.modal.seller_membership_plan_add_successfully')
            });
          })
      },
      updateData() {
        this.$v.form.$touch();
        if (this.$v.form.$anyError) {
          return;
        }
        this.form.put('membership-plan/' + this.form.id)
          .then(() => {
            this.$emit('success');
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('ourModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.modal.seller_membership_plan_update_successfully')
            });
          })
      },
    }
  }
</script>

<style scoped>

</style>
