<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.country.region")}}
        </span>
        <h3 class="page-title">{{$t("message.country.country")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.country.add_new_country")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div class="dataTables_length"><label>{{$t("message.country.show")}}
        <select v-model="tableData.length" @change="loadData()">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
        {{$t("message.country.entries")}}</label></div>
      <div class="dataTables_filter">
        <label>{{$t("message.country.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>
      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects.data" :key="project.id">
          <td>{{ i + serial_no }}</td>
          <td>{{ project.name }}</td>
          <td>{{ project.code }}</td>
          <td>{{ project.code_a3 }}</td>
          <td>{{ project.code_n3 }}</td>
          <td>{{ project.lat }}</td>
          <td>{{ project.long }}</td>
          <td>
            <CSwitch
                    class="mr-1"
                    color="danger"
                    :checked="project.status === 0? false:true"
                    shape="pill"
                    @update:checked="(state)=>{countryChecked(state,project.id)}"
            />
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModalEdit(project)">
                <font-awesome-icon icon="edit"/>
              </CButton>
              <CButton color="secondary" @click="deleteCountry(project.id)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{$t("message.country.showing")}} {{ projects.from }} to {{ projects.to }} of {{ projects.total }} {{$t("message.country.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2"
                      @pagination-change-page="loadData">
            <span slot="prev-nav">{{$t("message.country.previous")}}</span>
            <span slot="next-nav">{{$t("message.country.next")}}</span>
          </pagination>
        </div>
      </div>
    </div>
    <!-- End Datatable -->

    <!-- Modal -->
    <b-modal id="adminModal" :title="editMode ? this.$t('message.country.country_information_edit'): this.$t('message.country.new_country_add')"
             hide-footer centered>
      <b-form @submit.prevent="editMode ? updateCountry(): createCountry()" @keydown="form.onKeydown($event)">
        <b-form-row>
          <b-col>
            <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{ 'is-invalid': form.errors.has('name')}"
                id="ColorName"
                v-model="$v.form.name.$model"
                placeholder="Country Name"
                :state="validateState('name')"
                aria-describedby="BrandName"
            ></b-form-input>
            <b-form-invalid-feedback v-if="!$v.form.name.required">
              {{$t("message.country.country_name_required")}}
            </b-form-invalid-feedback>
            <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
              {{$t("message.country.country_name_character")}}
            </b-form-invalid-feedback>
            <has-error :form="form" field="name"></has-error>
          </b-col>
          <b-col>
            <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{ 'is-invalid': form.errors.has('code')}"
                v-model="$v.form.code.$model"
                placeholder="Country Code"
                :state="validateState('code')"
                aria-describedby="BrandName"
            ></b-form-input>
            <b-form-invalid-feedback v-if="!$v.form.code.required">
              {{$t("message.country.country_code_required")}}
            </b-form-invalid-feedback>
            <b-form-invalid-feedback v-if="!$v.form.code.maxLength">
              {{$t("message.country.country_code_character")}}
            </b-form-invalid-feedback>
            <has-error :form="form" field="name"></has-error>
          </b-col>
        </b-form-row>
        <b-form-row class="my-2">
          <b-col>
            <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{ 'is-invalid': form.errors.has('code_a3')}"
                v-model="$v.form.code_a3.$model"
                placeholder="Country code_a3"
                :state="validateState('code_a3')"
            ></b-form-input>
            <b-form-invalid-feedback v-if="!$v.form.code_a3.maxLength">
              {{$t("message.country.country_code_a3_character")}}
            </b-form-invalid-feedback>
            <has-error :form="form" field="name"></has-error>
          </b-col>
          <b-col>
            <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{ 'is-invalid': form.errors.has('code_n3')}"
                v-model="$v.form.code_n3.$model"
                placeholder="Country code_n3"
                :state="validateState('code_n3')"
            ></b-form-input>
            <b-form-invalid-feedback v-if="!$v.form.code_n3.maxLength">
              {{$t("message.country.country_code_n3_character")}}
            </b-form-invalid-feedback>
            <has-error :form="form" field="name"></has-error>
          </b-col>
        </b-form-row>
        <b-form-row class="my-2">
          <b-col>
            <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{ 'is-invalid': form.errors.has('lat')}"
                v-model="form.lat"
                placeholder="Country Latitude"
            ></b-form-input>
            <has-error :form="form" field="lat"></has-error>
          </b-col>
          <b-col>
            <b-form-input
                class="form-control form-control-solid h-auto"
                :class="{ 'is-invalid': form.errors.has('long')}"
                v-model="form.long"
                placeholder="Country Longitude"
            ></b-form-input>
            <has-error :form="form" field="long"></has-error>
          </b-col>
        </b-form-row>

        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              {{ editMode ? this.$t('message.country.update') : this.$t('message.country.submit') }}
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
import ApiService from "@/core/services/api.service";
import Datatable from "../helper/Datatable";
import {maxLength, required} from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],
  name: "Country",
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'Name', name: 'name'},
      {label: 'Code', name: 'code'},
      {label: 'Code a3'},
      {label: 'Code n3'},
      {label: 'Latitude'},
      {label: 'Longitude'},
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
        code: '',
        code_a3: '',
        code_n3: '',
        lat: '',
        long: '',
      }),
      loadActive: false,
      projects: {},
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
      code: {
        required,
        maxLength: maxLength(100)
      },
      code_a3: {
        maxLength: maxLength(100)
      },
      code_n3: {
        maxLength: maxLength(200)
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
      let url = 'country?page=' + page;
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
    countryChecked(e, f) {
      e = e ? 1 : 0;
      this.form.get('country/' + f + '/' + e)
        .catch((e) => {
          swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
        })
    },
    createCountry() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('country')
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.country.country_add_successfully')
            });
          })
          .catch((e) => {

          })
    },
    updateCountry() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.put('country/' + this.form.id)
          .then((e) => {
            this.loadData();
            this.form.reset();
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t('message.country.country_update_successfully')
            });
          })
          .catch((e) => {

          })
    },
    deleteCountry(id) {
      swal.fire({
        title: this.$t('message.country.are_you_sure'),
        text: this.$t('message.country.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.country.delete_it')
      }).then((result) => {
        if (result.value) {
          this.form.delete('country/' + id)
              .then((data) => {
                this.loadData();
                toast.fire({
                  icon: 'success',
                  title: this.$t('message.country.country_deleted_successfully')
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
  }
}
</script>

<style scoped>

</style>
