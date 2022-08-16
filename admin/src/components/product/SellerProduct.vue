<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.product.product")}}
        </span>
        <h3 class="page-title">{{$t("message.product.seller_products")}}</h3>
      </div>
<!--      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <router-link class="btn btn-primary" to="/product/product-admin-create" color="info">
            <font-awesome-icon icon="plus" class="mr-2"/>
            {{$t("message.product.add_new_product_service")}}
          </router-link>
        </div>
      </div>-->
    </div>
    <!-- End Page Header -->
    <!-- Datatable -->
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer my-5">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <div class="dataTables_length"><label>Show
        <select v-model="tableData.length" @change="loadData()">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
        {{$t("message.product.entries")}}</label></div>
      <div class="dataTables_filter">
        <label>{{$t("message.product.search")}}
          <input type="search" v-model="tableData.search" @input="loadData()">
        </label>
      </div>
      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody>
        <tr role="row" class="odd" v-for="(project, i) in projects.data" :key="project.id">
          <td>{{ i + serial_no }}</td>
          <td>
            <b-media>
              <template v-slot:aside>
                <b-img :src="getProfilePhoto(project.thumbnail_img)" v-if="project.thumbnail_img" class="border"
                       width="80px"></b-img>
              </template>
              <p>{{ project.name }}</p>
            </b-media>
          </td>
          <td>{{ project.user?project.user.full_name:'' }}</td>
          <td>{{ project.num_of_sale }}</td>
          <td>{{ totalQty(project) }}</td>
          <td>{{ totalPrice(project) }}</td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <a href="#" @click.prevent="approveProduct(project)">
                <CButton color="secondary">
                  <i class="fas fa-check-square"></i>
                  <font-awesome-icon :icon="project.is_approved ? 'ban' : 'check'"/>
                </CButton>
              </a>
              <router-link :to="'/product/product-edit/'+project.slug">
                <CButton color="secondary">
                  <font-awesome-icon icon="edit"/>
                </CButton>
              </router-link>
              <a href="#" @click.prevent="deleteProduct(project.id)">
                <CButton color="secondary">
                  <font-awesome-icon icon="trash-alt"/>
                </CButton>
              </a>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </datatable>
      <div class="dataTables_footer">
        <div class="dataTables_info">
          {{$t("message.product.showing")}} {{ projects.from }} to {{ projects.to }} of {{ projects.total }} {{$t("message.product.entries")}}
        </div>
        <div class="dataTables_paginate paging_simple_numbers">
          <pagination :data="projects" :show-disabled="true" :align="align" :limit="2"
                      @pagination-change-page="loadData">
            <span slot="prev-nav">{{$t("message.product.previous")}}</span>
            <span slot="next-nav">{{$t("message.product.next")}}</span>
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
  name: "Product",
  components: {
    datatable: Datatable
  },
  data() {
    let sortOrders = {};

    let columns = [
      {label: '#', name: '#'},
      {label: 'Name', name: 'name'},
      {label: 'Seller Name', name: 'Seller name'},
      {label: 'Num of Sale'},
      {label: 'Total Stock'},
      {label: 'Base Price'},
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
    getProfilePhoto(e) {
      return api_base_url + e;
    },
    loadData(page = 1) {
      this.loadActive = true;
      this.projects = {};
      let url = 'admin/seller/products?page=' + page;
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
    totalQty: function (product) {
      if (product.stockManagement){
        if(product.priceType ==1 && product.product_stock.length>0){
          let max = Math.max(...product.product_stock.map(d=>d.qty));
          let min = Math.min(...product.product_stock.map(d=>d.qty));
          return min+'-'+max;
        }else{
          return product.quantity;
        }
      }else return 'Unlimited';
    },
    /*
    * method for price calculation
    * */
    totalPrice: function (product) {
      //console.log(product);
      /*if (product.flash_deal_products.length>0){
        let flash = product.flash_deal_products.filter(item=>{
          if (item.flash_deal) return item;
        });
        if (flash.length>0) {
          return flash[flash.length - 1];
        }
      }else*/
      if (product.priceType ==0){
        return product.unit_price;
      }else if(product.priceType ==1 && product.product_stock.length>0){
        let max = Math.max(...product.product_stock.map(d=>d.price));
        let min = Math.min(...product.product_stock.map(d=>d.price));
        return min+'-'+max;
      }else if(product.priceType ==2 && product.price_variation.length>0){
        let max = Math.max(...product.price_variation.map(d=>d.off_price));
        let min = Math.min(...product.price_variation.map(d=>d.off_price));
        return min+'-'+max;
      }
    },
    approveProduct(data) {
      ApiService.post('product-approve/' + data.id)
        .then((data) => {
          if (data.data.result === 'Error') {
            swal.fire(this.$t('message.common.error'), data.data.message, 'warning')
          } else {
            swal.fire(
              data.data.result,
              `Product has been ${data.data.result.toLowerCase()}.`,
              'success'
            )
            this.loadData();
          }
        })
        .catch(() => {
          swal.fire(this.$t('message.common.error'), this.$t('message.common.something_wrong'), 'warning')
        });
    },
    deleteProduct(id) {
      swal.fire({
        title: this.$t('message.product.are_you_sure'),
        text: this.$t('message.product.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.product.delete_it')
      }).then((result) => {
        if (result.value) {
          ApiService.delete('product/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire("Failed!", data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t('message.common.deleted'),
                      this.$t('message.product.product_deleted'),
                      'success'
                  )
                  let index = this.projects.data.findIndex(value => value.id === id)
                  this.projects.data.splice(index, 1);
                }
              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'), this.$t('message.common.something_wrong'), 'warning')
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
