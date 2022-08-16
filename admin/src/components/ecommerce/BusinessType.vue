<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{ $t("message.business_type.e_commerce_setup")}}
        </span>
        <h3 class="page-title">{{ $t("message.business_type.business_type")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{ $t("message.business_type.add_new_business_type")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->


    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div class="dataTables_length"><label>{{ $t("message.business_type.show")}}
        <select v-model="tableData.length" @change="loadData()">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
        {{ $t("message.business_type.entries")}}</label></div>
      <div class="dataTables_filter">
        <label>{{ $t("message.business_type.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>

      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects.data" :key="project.id">
          <td>{{ i + serial_no }}</td>
          <td>{{ project.name }}</td>
          <td>
            <CSwitch
                class="mr-1"
                color="danger"
                :checked="project.status === 0? false:true"
                shape="pill"
                @update:checked="(state)=>{businessChecked(state,project.id)}"
            />
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModalEdit(project)">
                <font-awesome-icon icon="edit"/>
              </CButton>
              <CButton color="secondary" @click="deleteBusiness(project.id)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{ $t("message.business_type.showing")}} {{ projects.from }} {{ $t("message.business_type.to")}} {{ projects.to }} {{ $t("message.business_type.of")}} {{ projects.total }} {{ $t("message.business_type.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2"
                      @pagination-change-page="loadData">
            <span slot="prev-nav">{{ $t("message.business_type.previous")}}</span>
            <span slot="next-nav">{{ $t("message.business_type.next")}}</span>
          </pagination>
        </div>
      </div>
    </div>
    <!-- End Datatable -->

    <!-- Modal -->
    <b-modal id="adminModal" :title="editMode ? this.$t('message.business_type.business_type_information_edit'): this.$t('message.business_type.new_business_type_add')"
             hide-footer centered>
      <b-form @submit.prevent="editMode ? updateBusiness(): createBusiness()" @keydown="form.onKeydown($event)">
        <CInput label="Business Type Name :" v-model="$v.form.name.$model"
                placeholder="Enter Business Type Name" :class="{ 'is-invalid': form.errors.has('name')}"
                :invalidFeedback="!$v.form.name.required? this.$t('message.business_type.business_type_name_required'): $v.form.name.maxLength?'': this.$t('message.business_type.business_type_name_character') "
                :isValid="validateState('name')"/>
        <has-error :form="form" field="name"></has-error>

        <CRow class="justify-content-end mt-5">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              {{ editMode ? this.$t('message.business_type.update') : this.$t('message.business_type.submit') }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">{{ $t("message.business_type.close")}}</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import ApiService from "@/core/services/api.service";
import Datatable from "../helper/Datatable";
import {maxLength, required} from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  name: "BusinessType",
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'Name', name: 'name'},
      {label: 'Status'},
      {label: 'Action'}
    ];

    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      editMode: false,
      form: new Form({
        id: '',
        name: '',
      }),
      loadActive: false,
      projects: {},
      columns: columns,
      columns_exist: ['name'],
      sortKey: 'deadline',
      sortOrders: sortOrders,
      perPage: ['10', '20', '50'],
      align: 'right',
      tableData: {
        draw: 0,
        length: 10,
        search: '',
        column: 0,
        dir: 'desc',
      }
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(255)
      }
    }
  },
  components: {
    datatable: Datatable
  },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    openModal() {
      this.form.reset();
      this.$v.$reset();
      this.form.clear();
      this.editMode = false;
      this.$bvModal.show('adminModal');
    },
    openModalEdit(data) {
      this.form.reset();
      this.$v.$reset();
      this.form.clear();
      this.form.fill(data);
      this.editMode = true;
      this.$bvModal.show('adminModal');
    },
    loadData(page = 1) {
      this.loadActive = true;
      this.projects = {};
      let url = 'business_type?page=' + page;
      this.tableData.draw++;
      ApiService.get(url, '', {params: this.tableData})
          .then(({data}) => {
            this.loadActive = false;
            let response = data.data;
            if (this.tableData.draw == data.draw) {
              this.projects = response;
              this.serial_no = response.from;
            }
          })
          .catch(({response}) => {

          });
    },
    sortBy(key) {
      if (this.columns_exist.indexOf(key) === -1)
        return true;
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
      this.tableData.column = this.getIndex(this.columns, 'name', key);
      this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
      this.loadData();
    },
    getIndex(array, key, value) {
      return array.findIndex(i => i[key] == value)
    },
    createBusiness() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('business_type')
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.business_type.business_type_add_successfully')
            });
          })
          .catch((e) => {
            swal(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning')
          })
    },
    updateBusiness() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('business_type/' + this.form.id)
          .then(() => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.business_type.business_type_update_successfully')
            });
          })
          .catch((e) => {
            swal(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning')
          })
    },
    deleteBusiness(id) {
      swal.fire({
        title: this.$t('message.business_type.are_you_sure'),
        text: this.$t('message.business_type.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.business_type.delete_it')
      }).then((result) => {
        if (result.value) {
          this.form.delete('business_type/' + id)
              .then((data) => {
                this.loadData();
                toast.fire({
                  icon: 'success',
                  title: this.$t('message.business_type.business_type_deleted_successfully')
                });
              })
              .catch(() => {
                swal(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
    businessChecked(e, f) {
      e = e ? 1 : 0;
      this.form.get('business_type/' + f + '/' + e)
          .catch((e) => {
            swal(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning')
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
