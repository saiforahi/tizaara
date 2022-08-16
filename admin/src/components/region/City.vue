<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.city.region")}}
        </span>
        <h3 class="page-title">{{$t("message.city.city")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.city.add_new_city")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div class="dataTables_length"><label>{{$t("message.city.show")}}
        <select v-model="tableData.length" @change="loadData()">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
        {{$t("message.city.entries")}}</label></div>
      <div class="dataTables_filter">
        <label>{{$t("message.city.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>
      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects.data" :key="project.id">
          <td>{{ i + serial_no }}</td>
          <td>{{ project.name }}</td>
          <td>{{ project.division.name }}</td>
          <td>{{ project.division.country.name }}</td>
          <td>
            <CSwitch
                    class="mr-1"
                    color="danger"
                    :checked="project.status === 0? false:true"
                    shape="pill"
                    @update:checked="(state)=>{cityChecked(state,project.id)}"
            />
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModalEdit(project)">
                <font-awesome-icon icon="edit"/>
              </CButton>
              <CButton color="secondary" @click="deleteCity(project.id)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{$t("message.city.showing")}} {{ projects.from }} {{$t("message.city.to")}} {{ projects.to }} {{$t("message.city.of")}} {{ projects.total }} {{$t("message.city.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2"
                      @pagination-change-page="loadData">
            <span slot="prev-nav">{{$t("message.city.previous")}}</span>
            <span slot="next-nav">{{$t("message.city.next")}}</span>
          </pagination>
        </div>
      </div>
    </div>
    <!-- End Datatable -->

    <!-- Modal -->
    <b-modal id="adminModal" :title="editMode ? this.$t('message.city.city_information_edit'): this.$t('message.city.new_city_add')"
             hide-footer centered>
      <b-form @submit.prevent="editMode ? updateCity(): createCity()" @keydown="form.onKeydown($event)">
        <b-form-group>
          <b-form-input
              class="form-control form-control-solid h-auto"
              :class="{ 'is-invalid': form.errors.has('name')}"
              v-model="$v.form.name.$model"
              placeholder="City Name"
              :state="validateState('name')"
          ></b-form-input>
          <b-form-invalid-feedback v-if="!$v.form.name.required">
            {{$t('message.city.city_name_required')}}
          </b-form-invalid-feedback>
          <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
            {{$t('message.city.city_name_character')}}
          </b-form-invalid-feedback>
          <has-error :form="form" field="name"></has-error>
        </b-form-group>
        <b-form-group>
          <b-form-select v-model="$v.form.country_id.$model" class="mb-3" required
                         @input="countrySelect">
            <b-form-select-option value="" disabled>{{$t('message.city.select_country')}}</b-form-select-option>
            <b-form-select-option v-for="countries in country" :value="countries.id" :key="countries.id">
              {{ countries.name }}
            </b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback v-if="!$v.form.country_id.required">
            {{$t('message.city.country_name_required')}}
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group class="mb-5">
          <b-form-select v-model="$v.form.division_id.$model" class="mb-3" required>
            <b-form-select-option value="" disabled>{{$t('message.city.select_division')}}</b-form-select-option>
            <b-form-select-option v-for="divisions in division" :value="divisions.id" :key="divisions.id">
              {{ divisions.name }}
            </b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback v-if="!$v.form.division_id.required">
            {{$t('message.city.division_name_required')}}
          </b-form-invalid-feedback>
        </b-form-group>

        <CRow class="justify-content-end mt-5">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              {{ editMode ? this.$t("message.city.update") : this.$t("message.city.submit") }}
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">{{$t('message.city.close')}}</CButton>
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
  name: "City",
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'City Name', name: 'name'},
      {label: 'Division'},
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
        division_id: '',
      }),
      loadActive: false,
      projects: {},
      country: [],
      division: [],
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
      },
      division_id: {
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
      this.form.country_id = data.division.country.id;
      this.countrySelect(this.form.country_id);
      this.form.division_id = data.division.id;
      this.editMode = true;
      this.$bvModal.show('adminModal');
    },
    loadData(page = 1) {
      this.loadActive = true;
      this.projects = {};
      let url = 'city?page=' + page;
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
    countrySelect(e) {
      this.division = [];
      this.form.division_id = '';
      let that = this;
      ApiService.get('division/', e)
          .then(function (data) {
            that.division = data.data;
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
    cityChecked(e, f) {
      e = e ? 1 : 0;
      this.form.get('city/' + f + '/' + e)
        .catch((e) => {
          swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
        })
    },
    createCity() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('city')
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.city.city_add_successfully")
            });
          })
          .catch((e) => {

          })
    },
    updateCity() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('city/' + this.form.id)
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.city.city_update_successfully")
            });
          })
          .catch((e) => {

          })
    },
    deleteCity(id) {
      swal.fire({
        title: this.$t("message.city.are_you_sure"),
        text: this.$t("message.city.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.city.delete_it")
      }).then((result) => {
        if (result.value) {
          this.form.delete('city/' + id)
              .then((data) => {
                this.loadData();
                toast.fire({
                  icon: 'success',
                  title: this.$t("message.city.city_deleted_successfully")
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
