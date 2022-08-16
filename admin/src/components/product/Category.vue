<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.categories.product")}}
        </span>
        <h3 class="page-title">{{ $t("message.categories.category_information")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{ $t("message.categories.add_new_category")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(categoryList)" :columns="columns" class="text-center" :options="options">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="banner" slot-scope="props">
          <img v-lazy="getProfilePhoto(props.row.banner)" class="border" width="80px">
        </div>
        <div slot="icon" slot-scope="props">
          <img v-lazy="getProfilePhoto(props.row.icon)" class="border" width="80px">
        </div>
        <div slot="action" slot-scope="props">
          <CButtonGroup size="sm" class="mx-1">
            <CButton color="secondary" @click="openModalEdit(props.row)">
              <font-awesome-icon icon="edit"/>
            </CButton>
            <CButton color="secondary" @click="deleteCategory(props.row.id)">
              <font-awesome-icon icon="trash-alt"/>
            </CButton>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->

    <!-- Modal -->
    <b-modal id="adminModal" :title="editMode ? this.$t('message.categories.category_information_edit'): this.$t('message.categories.new_category_add')" hide-footer>
      <b-form @submit.prevent="editMode ? updateCategory(): createCategory()" @keydown="form.onKeydown($event)">
        <b-form-group label="Category Name :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              id="BrandName-1"
              v-model="$v.form.name.$model"
              placeholder="Category Name"
              :state="validateState('name')"
              aria-describedby="BrandName"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{ $t("message.categories.category_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{ $t("message.categories.category_name_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group label="Category banner :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-file
              accept="image/jpeg, image/png, image/jpg"
              placeholder="Choose category banner 200x300"
              @change="onInputChange"
          ></b-form-file>
          <b-form-invalid-feedback id="fileErrorText" :state="validation"></b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Category icon :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-file
              accept="image/jpeg, image/png, image/jpg"
              placeholder="Choose category icon 32x32"
              @change="onInputChange2" :state="validateState('icon')"
          ></b-form-file>
          <b-form-invalid-feedback v-if="!$v.form.icon.required">
            {{ $t("message.categories.category_icon_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback id="fileErrorText2" :state="validation2"></b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Meta Title :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              v-model="$v.form.meta_title.$model"
              :state="validateState('meta_title')"
              placeholder="Meta Title"
          ></b-form-input>
          <b-form-invalid-feedback>
            {{ $t("message.categories.meta_title_character")}}
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
              <span v-if="form.busy" class="sr-only">{{ $t("message.categories.loading")}}</span>
              {{ editMode ? this.$t('message.categories.update') : this.$t('message.categories.submit') }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">{{ $t("message.categories.close")}}</CButton>
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
import {CATEGORY_ADD, CATEGORY_LIST, CATEGORY_MODIFY, CATEGORY_REMOVE} from "@/core/services/store/module/category";

export default {
  mixins: [validationMixin],
  name: "Category",
  data() {
    return {
      validation: true,
      validation2: true,
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        commision_rate: '',
        banner: '',
        icon: '',
        meta_title: '',
        slug: '',
        meta_description: ''
      }),
      columns: ['serial', 'name', 'banner', 'icon', 'action'],
      options: {
        headings: {
          serial: '#',
          name: 'Category Name',
        },
        sortable: ['name'],
        filterable: ['name']
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
      icon: {
        required,
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
    onInputChange(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText').text(files.name + this.$t('message.categories.not_image'));
        this.validation = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText').text(this.$t('message.categories.uploading_large_file'));
        this.validation = false;
        return;
      }
      this.validation = true;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.form.banner = reader.result
      }
      reader.readAsDataURL(files);
    },
    onInputChange2(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText2').text(files.name + this.$t('message.categories.not_image'));
        this.validation2 = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText2').text(this.$t('message.categories.uploading_large_file'));
        this.validation2 = false;
        return;
      }
      this.validation2 = true;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.form.icon = reader.result
      }
      reader.readAsDataURL(files);
    },
    openModal() {
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('adminModal');
    },
    openModalEdit(data) {
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.form.fill(data);
      this.editMode = true;
      this.$bvModal.show('adminModal');
    },
    createCategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('category')
          .then(({data}) => {
            this.$store.commit(CATEGORY_ADD, data);
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: this.$t('message.common.succes'),
              title: this.$t('message.categories.category_add_successfully')
            });
          })
    },
    updateCategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('category/' + this.form.id)
          .then(({data}) => {
            this.$store.commit(CATEGORY_MODIFY, data);
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: this.$t('message.common.succes'),
              title: this.$t('message.categories.category_add_successfully')
            });
          });
    },
    deleteCategory(id) {
      swal.fire({
        title: this.$t('message.categories.are_you_sure'),
        text: this.$t('message.categories.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.categories.delete_it')
      }).then((result) => {
        if (result.value) {
          this.form.delete('category/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t('message.common.error'), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t('message.common.deleted'),
                      this.$t('message.categories.category_deleted'),
                      this.$t('message.common.succes')
                  )
                  this.$store.commit(CATEGORY_REMOVE, id);
                }
              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'), this.$t('message.common.some_wrong'), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.$store.dispatch(CATEGORY_LIST)
  },
  computed: {
    ...mapGetters(["categoryList"])
  },
}
</script>

<style scoped>

</style>
