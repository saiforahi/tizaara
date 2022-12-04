<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.package_payment.supplier")}}
        </span>
        <h3 class="page-title">{{ $t("message.package_payment.package_payment")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div>

        <div class="dataTables_length">
          <label>{{$t("message.color.show")}}
            <select v-model="tableData.length" @change="loadData()">
              <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
            </select>
            {{$t("message.color.entries")}}
          </label>
        </div>
      </div>

      <div class="dataTables_filter">
        <label>{{$t("message.color.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>
      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects.data" :key="project.id">
          <td>{{ i+1 }}</td>
          <td>{{ project.company_basic_info.name }}</td>
          <td>{{ project.user.full_name }}</td>
          <td>{{ project.company_basic_info.office_space }}</td>
          <td>{{ project.message }}</td>
          <td>
            <CBadge v-if="project.status === 0" color="warning">{{'Pending' }}</CBadge>
            <CBadge v-else-if="project.status === 1" color="success">{{'Verified' }}</CBadge>
            <CBadge v-else-if="project.status === 2" color="danger">{{'Declined' }}</CBadge>
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <template>
                <CButton size="sm" v-if="project.status===0" color="primary" @click="deletePackagePayment(project.id,1)">
                  verify
                </CButton>
                <CButton size="sm" v-if="project.status===1" color="danger" @click="deletePackagePayment(project.id,2)">
                  Decline
                </CButton>
              </template>

            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{$t("message.color.showing")}} {{ projects.from }} {{$t("message.color.to")}} {{ projects.to }} {{$t("message.color.of")}} {{ projects.total }} {{$t("message.color.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2" @pagination-change-page="loadData">
            <span slot="prev-nav">{{$t("message.color.previous")}}</span>
            <span slot="next-nav">{{$t("message.color.next")}}</span>
          </pagination>
        </div>
      </div>
    </div>
    <!-- End Datatable -->
  </div>
</template>

<script>
import Datatable from "./../helper/Datatable";
import ApiService from "@/core/services/api.service";

export default {
  name: "PackagePayment",
  components: {
    datatable: Datatable
  },
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'Company Name'},
      {label: 'Supplier Name'},
      {label: 'Company Address'},
      {label: 'Message'},
      {label: 'Status'},
      {label: 'Action'}
    ];

    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      loadActive: false,
      projects: {},
      columns: columns,
      sortKey: 'id',
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
  methods: {
    loadData(page = 1) {
      this.loadActive = true;
      this.projects = {};
      let url = 'user/all/verify/requests?page=' + page;
      this.tableData.draw++;
      ApiService.get(url, '', {params: this.tableData})
          .then((data) => {
            console.log('pro',data.data)
            this.loadActive = false;
            let response = data.data;
            this.projects = response;
            this.serial_no = response.from;
          })
          .catch(() => {

          });
    },
    sortBy(key) {
      if (this.columns_exist.indexOf(key) === -1)
        return true;
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
      this.tableData.column = this.getIndex(this.columns, 'id', key);
      this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
      this.loadData();
    },
    getIndex(array, key, value) {
      return array.findIndex(i => i[key] == value)
    },
    deletePackagePayment(id,status) {
      swal.fire({
        title: this.$t('message.package_payment.are_you_sure'),
        text: this.$t('message.package_payment.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Change'
      }).then((result) => {
        if (result.value) {
          ApiService.post(`user/verify/request/status/change/${id}/${status}`)
              .then((response) => {
                toast.fire({
                  icon: 'success',
                  title: response.data.message,
                });
                this.loadData();
              })
              .catch(() => {
                swal(this.$t('message.common.error'), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.loadData()
  }
}
</script>

<style scoped>

</style>
