<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.grope.product")}}
        </span>
        <h3 class="page-title">{{$t("message.grope.products_grouping")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <router-link class="btn btn-primary" to="/product/product-group-create" color="info">
            <font-awesome-icon icon="plus" class="mr-2"/>
            {{$t("message.grope.add_new_group")}}
          </router-link>
        </div>
      </div>
    </div>
    <!-- End Page Header -->
    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div class="dataTables_length"><label>{{$t("message.grope.show")}}
        <select v-model="tableData.length" @change="loadData()">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
        {{$t("message.grope.entries")}}</label></div>
      <div class="dataTables_filter">
        <label>{{$t("message.grope.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>
      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects" :key="project.id">
          <td>{{ i + serial_no }}</td>
          <td>
            {{ project.name }}
          </td>
          <td>
            <b-card v-for="group in project.group" :key="group.id">
              <b-media>
                <template v-slot:aside>
                  <b-img :src="showImage(group.thumbnail_img)" width="64" alt="placeholder"></b-img>
                </template>
                <h5 class="mt-0 text-left">{{ group.name }}</h5>
                <p class="text-left">{{$t("message.grope.sku")}} {{ group.sku }}</p>
              </b-media>
            </b-card>
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary">
                <font-awesome-icon icon="edit"/>
              </CButton>
              <CButton color="secondary">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{$t("message.grope.showing")}} {{ projects.from }} {{$t("message.grope.to")}} {{ projects.to }} {{$t("message.grope.of")}} {{ projects.total }} {{$t("message.grope.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2"
                      @pagination-change-page="loadData">
            <span slot="prev-nav">{{$t("message.grope.previous")}}</span>
            <span slot="next-nav">{{$t("message.grope.next")}}</span>
          </pagination>
        </div>
      </div>
    </div>
    <!-- End Datatable -->


  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import Datatable from "../helper/Datatable";
import {api_base_url} from "@/core/config/app";

export default {
  name: "Grope",
  components: {
    datatable: Datatable
  },
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'Name'},
      {label: 'Product'},
      {label: 'Action'}
    ];

    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      loadActive: false,
      projects: {},
      category: [],
      subcategory: [],
      columns: columns,
      columns_exist: ['name', 'num_of_sale'],
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
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadData(page = 1) {
      this.loadActive = true;
      this.projects = {};
      let url = 'product-group?page=' + page;
      this.tableData.draw++;
      ApiService.get(url, '', {params: this.tableData})
          .then(({data}) => {
            this.loadActive = false;
            let response = data.data;
            let projects = [];
            for (let data in response.data) {
              let url = 'get-product-group?searchProduct=' + response.data[data].group;
              ApiService.get(url)
                  .then(res => {
                    projects.push({
                      id: response.data[data].id,
                      name: response.data[data].name,
                      group: res.data
                    })
                  });
            }
            //console.log(projects);
            if (this.tableData.draw == data.draw) {
              this.projects = projects;
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
    totalQty: function (e) {
      let total = [];
      Object.entries(e).forEach(([key, val]) => {
        total.push(val.qty)
      });
      return total.reduce(function (total, num) {
        return total + num
      }, 0);

    },
    totalPrice: function (e) {
      if (e.length == 0) return;
      let max = e.reduce((a, b) => Number(a.price) > Number(b.price) ? a : b)
      let min = e.reduce((a, b) => Number(a.price) < Number(b.price) ? a : b)
      return max === min ? max.price : min.price + ' - ' + max.price;

    }
  },
  created() {
    this.loadData();
  }
}
</script>

<style scoped>

</style>
