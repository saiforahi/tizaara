<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">

      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.category.add_new_help_category")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(helpCategoryList)" :columns="columns" class="text-center" :options="options">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="icon" slot-scope="props">
          <img v-lazy="showImage(props.row.icon)" class="border" width="80px">
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
    <b-modal id="adminModal" :title="editMode ? this.$t('message.category.category_information_edit'): this.$t('message.category.new_category_add')" hide-footer>
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
            {{$t("message.category.category_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{$t("message.category.category_name_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group label="Category icon :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-file
              accept="image/jpeg, image/png, image/jpg"
              placeholder="Choose category icon 100x100"
              @change="onInputChange2" :state="validateState('icon')"
          ></b-form-file>
          <b-form-invalid-feedback v-if="!$v.form.icon.required">
            {{$t("message.category.category_icon_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback id="fileErrorText2" :state="validation2"></b-form-invalid-feedback>
        </b-form-group>
        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
              <span v-if="form.busy" class="sr-only">{{$t("message.category.loading")}}</span>
              {{ editMode ? this.$t("message.category.update") : this.$t("message.category.submit") }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">{{$t("message.category.close")}}</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->

  </div>
</template>

<script>
import {maxLength, required} from "vuelidate/lib/validators";
import {validationMixin} from "vuelidate";
import {mapGetters} from "vuex";
import {
  HELP_CATEGORY_ADD,
  HELP_CATEGORY_LIST,
  HELP_CATEGORY_MODIFY,
  HELP_CATEGORY_REMOVE
} from "@/core/services/store/module/help";
import {api_base_url} from "@/core/config/app";

export default {
  mixins: [validationMixin],
  name: "Category",
  data() {
    return {
      validation2: true,
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        icon: '',
        slug: '',
      }),
      columns: ['serial', 'name', 'icon', 'action'],
      options: {
        headings: {
          serial: '#',
          name: this.$t("message.category.category_name"),
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
      icon: {
        required,
      }
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    openModal() {
      this.validation2 = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('adminModal');
    },
    openModalEdit(data) {
      this.validation2 = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.form.fill(data);
      this.editMode = true;
      this.$bvModal.show('adminModal');
    },
    onInputChange2(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText2').text(files.name + this.$t("message.category.not_image"));
        this.validation2 = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText2').text(this.$t("message.category.uploading_large_file"));
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
    createCategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('help-category')
          .then(({data}) => {
            this.$store.commit(HELP_CATEGORY_ADD, data);
            this.form.reset();
            this.validation2 = true;
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.category.category_add_successfully")
            });
          })
    },
    updateCategory() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('help-category/' + this.form.id)
          .then(({data}) => {
            this.$store.commit(HELP_CATEGORY_MODIFY, data);
            this.form.reset();
            this.validation2 = true;
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.category.category_update_successfully")
            });
          });
    },
    deleteCategory(id) {
      swal.fire({
        title: this.$t("message.category.are_you_sure"),
        text: this.$t("message.category.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.category.delete_it")
      }).then((result) => {
        if (result.value) {
          this.form.delete('help-category/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t('message.common.error'), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t('message.common.deleted'),
                      this.$t('message.category.category_deleted'),
                      this.$t('message.common.success')
                  )
                  this.$store.commit(HELP_CATEGORY_REMOVE, id);
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
    this.$store.dispatch(HELP_CATEGORY_LIST)
  },
  computed: {
    ...mapGetters(["helpCategoryList"])
  },
}
</script>

<style scoped>

</style>