<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">

      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.subcategory.new_help_subcategory")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(helpSubcategoryList)" :columns="columns" class="text-center"
                      :options="options">
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
    <b-modal id="adminModal2" :title="editMode ? this.$t('message.subcategory.subcategory_information_edit'): this.$t('message.subcategory.new_subcategory_add')" hide-footer>
      <b-form @submit.prevent="editMode ? updateCategory(): createCategory()" @keydown="form.onKeydown($event)">
        <b-form-group label="Subcategory Name :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              id="BrandName-1"
              v-model="$v.form.name.$model"
              placeholder="Subcategory Name"
              :state="validateState('name')"
              aria-describedby="BrandName"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{$t("message.subcategory.category_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{$t("message.subcategory.category_name_maximum_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group label="Select Category :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-select v-model="$v.form.category_id.$model" :options="Object.values(helpCategoryList)"
                         :state="validateState('category_id')" value-field="id"
                         text-field="name">
            <template v-slot:first>
              <b-form-select-option value="" disabled>{{$t("message.subcategory.please_select_category")}}</b-form-select-option>
            </template>
          </b-form-select>
          <b-form-invalid-feedback v-if="!$v.form.category_id.required">
            {{$t("message.subcategory.category_required")}}
          </b-form-invalid-feedback>
        </b-form-group>
        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
              <span v-if="form.busy" class="sr-only">{{$t("message.subcategory.loading")}}</span>
              {{ editMode ? this.$t("message.subcategory.update") : this.$t("message.subcategory.submit") }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal2')">{{$t("message.subcategory.close")}}</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->

  </div>
</template>

<script>
import {maxLength, required} from "vuelidate/lib/validators";
import {api_base_url} from "@/core/config/app";
import {
  HELP_SUBCATEGORY_ADD,
  HELP_SUBCATEGORY_LIST,
  HELP_SUBCATEGORY_MODIFY,
  HELP_SUBCATEGORY_REMOVE
} from "@/core/services/store/module/help";
import {mapGetters} from "vuex";
import {validationMixin} from "vuelidate";

export default {
  mixins: [validationMixin],
  name: "Subcategory",
  data() {
    return {
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        category_id: '',
        slug: '',
      }),
      columns: ['name', 'categoryName', 'action'],
      options: {
        headings: {
          name: this.$t("message.subcategory.subcategory_name"),
          categoryName: this.$t("message.subcategory.category_name"),
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
      category_id: {
        required,
      }
    }
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    openModal() {
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('adminModal2');
    },
    openModalEdit(data) {
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.form.fill(data);
      this.editMode = true;
      this.$bvModal.show('adminModal2');
    },
    createCategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('help-subcategory')
          .then(({data}) => {
            this.$store.commit(HELP_SUBCATEGORY_ADD, data);
            this.form.reset();
            this.$bvModal.hide('adminModal2');
            toast.fire({
              icon: 'success',
              title: this.$t("message.subcategory.subcategory_add_successfully")
            });
          })
    },
    updateCategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('help-subcategory/' + this.form.id)
          .then(({data}) => {
            this.$store.commit(HELP_SUBCATEGORY_MODIFY, data);
            this.form.reset();
            this.$bvModal.hide('adminModal2');
            toast.fire({
              icon: 'success',
              title: this.$t("message.subcategory.subcategory_update_successfully")
            });
          });
    },
    deleteCategory(id) {
      swal.fire({
        title: this.$t("message.subcategory.are_you_sure"),
        text: this.$t("message.subcategory.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.subcategory.delete_it")
      }).then((result) => {
        if (result.value) {
          this.form.delete('help-subcategory/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t('message.common.error'), data.data.message, 'warning')
                } else {
                  swal.fire(
                      'Deleted!',
                      'Subcategory has been deleted.',
                      'success'
                  )
                  this.$store.commit(HELP_SUBCATEGORY_REMOVE, id);
                }
              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.$store.dispatch(HELP_SUBCATEGORY_LIST)
  },
  computed: {
    ...mapGetters(["helpSubcategoryList", "helpCategoryList"])
  },
}
</script>

<style scoped>

</style>