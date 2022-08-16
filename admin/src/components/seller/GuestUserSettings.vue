<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.get_user_settings.seller")}}</span>
        <h3 class="page-title">{{$t("message.get_user_settings.guest_user_settings")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->

    <c-row class="justify-content-center my-4">
      <c-col md="9">
        <c-card-group>
          <c-card class="p-4">
            <c-form @submit.prevent="updateGeneral()" @keydown="form.onKeydown($event)">
              <c-input label="Max allowed products :" v-model="$v.form.max_allowed_products.$model"
                      horizontal placeholder="Enter number"  :disabled="editMode"type="number" step="1" min="0"
                      :invalidFeedback="$v.form.max_allowed_products.maxLength?'': this.$t('message.get_user_settings.max_allowed_product_character') "
                      :isValid="validateState('max_allowed_products')"/>
              <c-input label="Max allowed keywords :" v-model="$v.form.max_allowed_keywords.$model"
                      horizontal placeholder="Enter number" :disabled="editMode" type="number" step="1" min="0"
                      :invalidFeedback="$v.form.max_allowed_keywords.maxLength?'': this.$t('message.get_user_settings.max_allowed_keywords_character') "
                      :isValid="validateState('max_allowed_keywords')"/>
              <div class="form-group form-actions">
                <c-button :disabled="update" type="submit" v-if="!editMode" color="primary" class="float-right">
                  <span v-if="update" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  {{$t("message.get_user_settings.update")}}
                </c-button>
                <a v-if="editMode" @click="editMode = false" class="btn btn-secondary float-right">
                  {{ $v.form.max_allowed_products.$model && $v.form.max_allowed_keywords.$model ? this.$t('message.get_user_settings.edit') : this.$t('message.get_user_settings.save') }}
                </a>
              </div>
            </c-form>
          </c-card>
        </c-card-group>
      </c-col>
    </c-row>
  </div>
</template>

<script>
  import {validationMixin} from "vuelidate";
  import {maxLength, required} from 'vuelidate/lib/validators'
  import ApiService from "@/core/services/api.service";

  export default {
    mixins: [validationMixin],
    name: "GuestUserSettings",
    data() {
      return {
        form: new Form({
          id: '',
          max_allowed_products: '',
          max_allowed_keywords: ''
        }),
        editMode: true,
        update: false,
      }
    },
    validations: {
      form: {
        max_allowed_products: {
          required,
          maxLength: maxLength(10)
        },
        max_allowed_keywords: {
          required,
          maxLength: maxLength(10)
        }
      }
    },
    methods: {
      validateState(name) {
        const {$dirty, $error} = this.$v.form[name];
        return $dirty ? !$error : null;
      },
      loadData() {
        ApiService.get('guest-user-settings')
          .then(({data}) => {
            this.form.fill(data);
          })
          .catch(() => {
            swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning');
          });
      },
      updateGeneral: function () {
        this.$v.form.$touch();
        if (this.$v.form.$anyError) {
          return;
        }
        this.update = true;
        this.form.put('guest-user-settings/' + this.form.id)
          .then((e) => {
            this.$v.$reset();
            this.form.reset();
            this.loadData();
            this.editMode = true;
            this.update = false;
            toast.fire({
              icon: 'success',
              title: this.$t('message.get_user_settings.guest_user_settings_update_successfully')
            });
          })
          .catch((e) => {
            swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning');
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
