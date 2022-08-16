<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.packages.seller")}}
        </span>
        <h3 class="page-title">{{$t("message.packages.seller_membership_plan")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.packages.add_new_membership_plan")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <CRow class="justify-content-around">
      <CCol col="12" sm="12" md="4" class="mb-3" v-for="(data, k) in sellerPackageList" :key="k">
        <div class="card">
          <div class="card-body text-center">
            <img alt="Package Logo"
                 :src="showImage(data.logo)"
                 class="mw-100 mx-auto mb-4" height="150px">
            <p class="mb-3 h6 fw-600">{{ data.name }}</p>
            <p class="h4">{{ data.amount }} BDT</p>
            <p class="fs-15">{{$t("message.packages.product_upload")}}
              <b class="text-bold">{{ data.upload }}</b>
            </p>
            <p class="fs-15">{{$t("message.packages.membership_plan_duration")}}
              <b class="text-bold">{{ data.duration }} {{$t("message.packages.days")}}</b>
            </p>
            <div class="mar-top">
              <button @click="openModalEdit(data)" class="btn btn-sm btn-info mx-1">{{$t("message.packages.edit")}}</button>
              <button @click="deleteData(data.id)" class="btn btn-sm btn-danger mx-1">{{$t("message.packages.delete")}}</button>
            </div>
          </div>
        </div>
      </CCol>
    </CRow>

    <!-- Modal -->
    <b-modal id="ourModal" :title="editMode ? this.$t('message.packages.update_membership_plan'): this.$t('message.packages.create_membership_plan')" hide-footer>
      <b-form @submit.prevent="editMode ? updateData(): createData()" @keydown="form.onKeydown($event)">
        <b-form-group label="Package Name :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              id="BrandName-1"
              v-model="$v.form.name.$model"
              placeholder="Enter Plan Name"
              :state="validateState('name')"
              aria-describedby="BrandName"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{$t("message.packages.package_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{$t("message.packages.package_name_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group label="Amount :"
                      label-cols-sm="5"
                      label-cols-lg="4">
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
        <b-form-group label="Product Upload :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              v-model="$v.form.upload.$model"
              placeholder="Product Upload"
              type="number" min="0" step="1"
              :state="validateState('upload')"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.upload.required">
            {{$t("message.packages.product_upload_quantity_required")}}
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Package Duration :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              v-model="$v.form.duration.$model"
              placeholder="Validity in number of days"
              type="number" min="0" step="1"
              :state="validateState('duration')"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.duration.required">
            {{$t("message.packages.duration_required")}}
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Package Logo :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-file
              accept="image/jpeg, image/png, image/jpg"
              placeholder="Choose package logo 400x400"
              @change="onInputChange" :state="validateState('logo')"
          ></b-form-file>
          <b-form-invalid-feedback v-if="!$v.form.logo.required">
            {{$t("message.packages.package_logo_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback id="fileErrorText" :state="validation"></b-form-invalid-feedback>
        </b-form-group>
        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
              <span v-if="form.busy" class="sr-only">{{$t("message.packages.loading")}}</span>
              {{ editMode ? this.$t("message.packages.update") : this.$t("message.packages.submit") }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('ourModal')">{{$t("message.packages.close")}}</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->

  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {maxLength, required} from "vuelidate/lib/validators";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";

export default {
  mixins: [validationMixin],
  name: "Packages",
  data() {
    return {
      validation: true,
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        logo: '',
        amount: '',
        upload: '',
        duration: ''
      }),
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(50)
      },
      amount: {
        required
      },
      upload: {
        required
      },
      duration: {
        required
      },
      logo: {
        required
      }
    }
  },
  methods: {
    showImage(e) {
      if (e) return api_base_url + e;
    },
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
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.form.fill(data);
      this.editMode = true;
      this.$bvModal.show('ourModal');
    },
    onInputChange(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText').text(files.name + this.$t("message.packages.not_image"));
        this.validation = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText').text(this.$t("message.packages.uploading_large_file"));
        this.validation = false;
        return;
      }
      this.validation = true;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.form.logo = reader.result
      }
      reader.readAsDataURL(files);
    },
    createData() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('seller-package')
          .then(({data}) => {
            this.$store.commit('SELLER_PACKAGE_ADD', data);
            this.form.reset();
            this.$bvModal.hide('ourModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.packages.seller_packages_successfully")
            });
          })
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
              title: this.$t("message.packages.seller_packages_update_successfully")
            });
          })
    },
    deleteData(id) {
      let that = this;
      swal.fire({
        title: this.$t("message.packages.are_you_sure"),
        text: this.$t("message.packages.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.packages.delete_it")
      }).then((result) => {
        if (result.value) {
          this.form.delete('seller-package/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t("message.common.deleted"),
                      this.$t("message.packages.seller_packages_deleted"),
                      this.$t("message.common.succes")
                  )
                  this.$store.commit('SELLER_PACKAGE_REMOVE', id);
                }

              })
              .catch(() => {
                swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    if (!this.sellerPackageList.length > 0) this.$store.dispatch('SELLER_PACKAGE_LIST');
  },
  computed: {
    ...mapGetters(["sellerPackageList"])
  },
}
</script>

<style scoped>

</style>
