<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{ $t("message.sub_sub_category.product")}}
        </span>
        <h3 class="page-title">{{ $t("message.sub_sub_category.sub_subcategory_information")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{ $t("message.sub_sub_category.add_new_sub_subcategory")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(subsubcategoryList)" :columns="columns" class="text-center"
                      :options="options">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>

        <div slot="action" slot-scope="props">
          <CButtonGroup size="sm" class="mx-1">
            <CButton color="secondary" @click="openModalEdit(props.row)">
              <font-awesome-icon icon="edit"/>
            </CButton>
            <CButton color="secondary" @click="deleteSubcategory(props.row.id)">
              <font-awesome-icon icon="trash-alt"/>
            </CButton>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->


    <!-- Modal -->
    <b-modal id="adminModal" :title="editMode ? this.$t('message.sub_sub_category.sub_subcategory_information_edit'): this.$t('message.sub_sub_category.new_sub_subcategory_add')"
             hide-footer>
      <b-form @submit.prevent="editMode ? updateSubcategory(): createSubcategory()" @keydown="form.onKeydown($event)">
        <b-form-group label="Sub-Subcategory Name :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              id="BrandName-1"
              v-model="$v.form.name.$model"
              placeholder="Sub-Subcategory Name"
              :state="validateState('name')"
              aria-describedby="BrandName"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{ $t("message.sub_sub_category.subcategory_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{ $t("message.sub_sub_category.subcategory_name_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group label="Select Category :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-select v-model="$v.form.category_id.$model" :options="Object.values(categoryList)"
                         @input="categorySelect"
                         :state="validateState('category_id')" value-field="id"
                         text-field="name">
            <template v-slot:first>
              <b-form-select-option value="" disabled>{{ $t("message.sub_sub_category.please_select_category")}}</b-form-select-option>
            </template>
          </b-form-select>
          <b-form-invalid-feedback v-if="!$v.form.category_id.required">
            {{ $t("message.sub_sub_category.category_required")}}
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Select Subcategory :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-select v-model="$v.form.sub_category_id.$model" :options="subcategory"
                         :state="validateState('sub_category_id')" value-field="id"
                         text-field="name">
            <template v-slot:first>
              <b-form-select-option value="" disabled>{{ $t("message.sub_sub_category.please_select_sub_category")}}</b-form-select-option>
            </template>
          </b-form-select>
          <b-form-invalid-feedback v-if="!$v.form.sub_category_id.required">
            {{ $t("message.sub_sub_category.subcategory_required")}}
          </b-form-invalid-feedback>
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
            {{ $t("message.sub_sub_category.meta_title_character")}}
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
              <span v-if="form.busy" class="sr-only">{{ $t("message.sub_sub_category.loading")}}</span>
              {{ editMode ? 'Update' : 'Submit' }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">Close</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {required, maxLength} from "vuelidate/lib/validators";
import {api_base_url} from "@/core/config/app";
import {mapGetters} from "vuex";
import {SUBCATEGORY_LIST} from "@/core/services/store/module/subcategory";
import {CATEGORY_LIST} from "@/core/services/store/module/category";
import {
  SUBSUBCATEGORY_ADD,
  SUBSUBCATEGORY_LIST,
  SUBSUBCATEGORY_MODIFY,
  SUBSUBCATEGORY_REMOVE
} from "@/core/services/store/module/subsubcategory";

export default {
  mixins: [validationMixin],
  name: "SubSubCategory",
  data() {
    return {
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        category_id: '',
        sub_category_id: '',
        meta_title: '',
        slug: '',
        meta_description: ''
      }),
      subcategory: [],
      columns: ['serial', 'name', 'subcategoryName', 'categoryName', 'action'],
      options: {
        headings: {
          serial: '#',
          name: 'Sub-Subcategory Name',
          subcategoryName: 'Sub-Category Name',
          categoryName: 'Category Name',
        },
        sortable: ['name', 'categoryName', 'subcategoryName'],
        filterable: ['name', 'categoryName', 'subcategoryName']
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
      category_id: {
        required
      },
      sub_category_id: {
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
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('adminModal');
    },
    openModalEdit(data) {
      this.form.reset();
      this.$v.$reset();
      this.form.clear();
      this.form.fill(data);
      this.categorySelect(this.form.category_id);
      this.form.sub_category_id = data.sub_category_id;
      this.editMode = true;
      this.$bvModal.show('adminModal');
    },
    categorySelect(e) {
      this.form.sub_category_id = '';
      this.subcategory = this.getSubcategoryById(e);
    },
    createSubcategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('subsubcategory')
          .then(({data}) => {
            this.$store.commit(SUBSUBCATEGORY_ADD, data);
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.sub_sub_category.sub_subcategory_add_successfully")
            });
          })
          .catch((e) => {

          })
    },
    updateSubcategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('subsubcategory/' + this.form.id)
          .then(({data}) => {
            this.$store.commit(SUBSUBCATEGORY_MODIFY, data);
            this.form.reset();
            this.validation = true;
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.sub_sub_category.sub_subcategory_update_successfully")
            });
          })
          .catch((e) => {

          })
    },
    deleteSubcategory(id) {
      swal.fire({
        title: this.$t("message.sub_sub_category.are_you_sure"),
        text: this.$t("message.sub_sub_category.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.sub_sub_category.delete_it")
      }).then((result) => {
        if (result.value) {
          this.form.delete('subsubcategory/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t("message.common.deleted"),
                      this.$t("message.sub_category.sub_sub_category"),
                      this.$t("message.common.succes")
                  )
                  this.$store.commit(SUBSUBCATEGORY_REMOVE, id);
                }
              })
              .catch(() => {
                swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.$store.dispatch(SUBSUBCATEGORY_LIST)
    this.$store.dispatch(SUBCATEGORY_LIST)
    this.$store.dispatch(CATEGORY_LIST)
  },
  computed: {
    ...mapGetters(["subsubcategoryList", "categoryList", "getSubcategoryById"])
  },
}
</script>

<style scoped>

</style>
