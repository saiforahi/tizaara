<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.division.region")}}
        </span>
        <h3 class="page-title">{{$t("message.division.division")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.division.add_new_division")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div class="dataTables_length"><label>{{$t("message.division.show")}}
        <select v-model="tableData.length" @change="loadData()">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
        {{$t("message.division.entries")}}</label></div>
      <div class="dataTables_filter">
        <label>{{$t("message.division.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>
      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects.data" :key="project.id">
          <td>{{ i + serial_no }}</td>
          <td>{{ project.name }}</td>
          <td>{{ project.country.name }}</td>
          <td>
            <CSwitch
                    class="mr-1"
                    color="danger"
                    :checked="project.status === 0? false:true"
                    shape="pill"
                    @update:checked="(state)=>{divisionChecked(state,project.id)}"
            />
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModalEdit(project)">
                <font-awesome-icon icon="edit"/>
              </CButton>
              <CButton color="secondary" @click="deleteDivision(project.id)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{$t("message.division.showing")}} {{ projects.from }} {{$t("message.division.to")}} {{ projects.to }} {{$t("message.division.of")}} {{ projects.total }} {{$t("message.division.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2"
                      @pagination-change-page="loadData">
            <span slot="prev-nav">{{$t("message.division.previous")}}</span>
            <span slot="next-nav">{{$t("message.division.next")}}</span>
          </pagination>
        </div>
      </div>
    </div>
    <!-- End Datatable -->

    <!-- Modal -->
    <b-modal id="adminModal" :title="editMode ? this.$t('message.division.division_information_edit'): this.$t('message.division.new_division_add')"
             hide-footer centered>
      <b-form @submit.prevent="editMode ? updateDivision(): createDivision()" @keydown="form.onKeydown($event)">
        <b-form-group>
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              v-model="$v.form.name.$model"
              placeholder="Division Name"
              :state="validateState('name')"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{$t("message.division.division_name_required")}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{$t("message.division.division_name_character")}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group>
          <v-select v-model="$v.form.country_id.$model" :options="country" label="name"
                    :reduce="name => name.id"
                    placeholder="Select Country">
            <template #search="{attributes, events}">
              <input
                  class="vs__search is-invalid"
                  v-bind="attributes"
                  :required="!form.country_id"
                  v-on="events"
              />
            </template>
            <template #option="{ name }">
              <em>{{ name }}</em>
            </template>
          </v-select>
        </b-form-group>

        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              {{ editMode ? this.$t('message.division.update') : this.$t('message.division.submit') }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">{{$t("message.division.close")}}</CButton>
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
  name: "Division",
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'Division Name', name: 'name'},
      {label: 'Country'},
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
        country_id: '',
      }),
      loadActive: false,
      projects: {},
      country: {},
      columns: columns,
      columns_exist: ['name', 'code'],
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
        maxLength: maxLength(100)
      },
      country_id: {
        required,
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
      let url = 'division?page=' + page;
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
    loadCountry() {
      let that = this;
      ApiService.get('country')
          .then(function (data) {
            that.country = data.data;
          })
          .catch(({response}) => {
            console.log(response);
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
    divisionChecked(e, f) {
      e = e ? 1 : 0;
      this.form.get('division/' + f + '/' + e)
        .catch((e) => {
          swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
        })
    },
    createDivision() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('division')
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.division.division_add_successfully')
            });
          })
          .catch((e) => {

          })
    },
    updateDivision() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('division/' + this.form.id)
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.division.division_update_successfully')
            });
          })
          .catch((e) => {

          })
    },
    deleteDivision(id) {
      swal.fire({
        title: this.$t('message.division.are_you_sure'),
        text: this.$t('message.division.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.division.delete_it')
      }).then((result) => {
        if (result.value) {
          this.form.delete('division/' + id)
              .then((data) => {
                this.loadData();
                toast.fire({
                  icon: 'success',
                  title: this.$t('message.division.division_deleted_successfully')
                });
              })
              .catch(() => {
                swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.loadData();
    this.loadCountry();
  }
}
</script>

<style scoped>

</style>
