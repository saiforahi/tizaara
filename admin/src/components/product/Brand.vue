<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.brand.product")}}
        </span>
        <h3 class="page-title">{{ $t("message.brand.brand_information")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{ $t("message.brand.add_new_brand")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(brandList)" :columns="columns" class="text-center" :options="options">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="logo" slot-scope="props">
          <img v-lazy="getProfilePhoto(props.row.logo)" class="border" width="80px">
        </div>
        <div slot="action" slot-scope="props">
          <CButtonGroup size="sm" class="mx-1">
            <CButton color="secondary" @click="openModalEdit(props.row)">
              <font-awesome-icon icon="edit"/>
            </CButton>
            <CButton color="secondary" @click="deleteBrand(props.row.id)">
              <font-awesome-icon icon="trash-alt"/>
            </CButton>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->

    <!-- Modal -->
    <b-modal id="brandModal" :title="editMode ? 'Brand Information Edit': 'New Brand Add'" hide-footer>
      <b-form @submit.prevent="editMode ? updateBrand(): createBrand()" @keydown="form.onKeydown($event)">
        <b-form-group label="Brand Name :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              id="BrandName-1"
              v-model="$v.form.name.$model"
              placeholder="Enter Brand Name"
              :state="validateState('name')"
              aria-describedby="BrandName"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{ $t("message.brand.brand_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{ $t("message.brand.brand_name_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group label="Brand logo :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-file
              accept="image/jpeg, image/png, image/jpg"
              placeholder="Choose brand logo 120x80"
              @change="onInputChange" :state="validateState('logo')"
          ></b-form-file>
          <b-form-invalid-feedback v-if="!$v.form.logo.required">
            {{ $t("message.brand.brand_logo_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback id="fileErrorText" :state="validation"></b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Meta Title :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              v-model="$v.form.meta_title.$model"
              :state="validateState('meta_title')"
              placeholder="Enter Meta Title"
          ></b-form-input>
          <b-form-invalid-feedback>
            {{ $t("message.brand.meta_title_character")}}
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Meta Description :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-textarea
              id="textarea"
              v-model="form.meta_description"
              placeholder="Description"
              rows="3"
          ></b-form-textarea>
        </b-form-group>
        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
              <span v-if="form.busy" class="sr-only">{{ $t("message.brand.loading")}}</span>
              {{ editMode ? this.$t('message.brand.update') : this.$t('message.brand.submit') }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('brandModal')">{{ $t("message.brand.close")}}</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {validationMixin} from "vuelidate";
import {required, maxLength} from "vuelidate/lib/validators";
import {api_base_url} from "@/core/config/app";
import {BRAND_ADD, BRAND_LIST, BRAND_MODIFY, BRAND_REMOVE} from "@/core/services/store/module/brand";

export default {
  mixins: [validationMixin],
  name: "Brand",
  data() {
    return {
      validation: true,
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        logo: '',
        meta_title: '',
        slug: '',
        meta_description: ''
      }),
      columns: ['serial', 'name', 'logo', 'meta_title', 'action'],
      options: {
        headings: {
          serial: '#',
          name: 'Brand Name',
          logo: 'Brand Logo'
        },
        sortable: ['name'],
        filterable: ['name', 'meta_title']
      }
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(50)
      },
      meta_title: {
        maxLength: maxLength(255)
      },
      logo: {
        required
      }
    }
  },
  methods: {
    getProfilePhoto(e) {
      return api_base_url + e;
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
      this.$bvModal.show('brandModal');
    },
    openModalEdit(data) {
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.form.fill(data);
      this.editMode = true;
      this.$bvModal.show('brandModal');
    },
    onInputChange(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText').text(files.name + this.$t('message.brand.not_image'));
        this.validation = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText').text(this.$t('message.brand.uploading_large_file'));
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
    createBrand() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('brand')
          .then(({data}) => {
            this.$store.commit(BRAND_ADD, data);
            this.form.reset();
            this.$bvModal.hide('brandModal');
            toast.fire({
              icon: this.$t('message.common.succes'),
              title: this.$t('message.brand.brand_add_successfully')
            });
          })
    },
    updateBrand() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('brand/' + this.form.id)
          .then(({data}) => {
            this.$store.commit(BRAND_MODIFY, data);
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('brandModal');
            toast.fire({
              icon: this.$t('message.common.succes'),
              title: this.$t('message.brand.brand_update_successfully')
            });
          })
    },
    deleteBrand(id) {
      let that = this;
      swal.fire({
        title: this.$t('message.brand.are_you_sure'),
        text: this.$t('message.brand.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.brand.delete_it')
      }).then((result) => {
        if (result.value) {
          this.form.delete('brand/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire("Failed!", data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t('message.common.deleted'),
                      this.$t('message.brand.brand_deleted'),
                      this.$t('message.common.succes')
                  )
                  this.$store.commit(BRAND_REMOVE, id);
                }

              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'),this.$t('message.common.something_wrong'), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.$store.dispatch(BRAND_LIST)
  },
  computed: {
    ...mapGetters(["brandList"])
  },
}
</script>

<style scoped>

</style>
