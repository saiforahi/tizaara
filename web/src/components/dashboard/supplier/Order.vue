<template>
  <div class="content">
    <!-- Orders -->
    <div class="orders">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <h4 class="box-title">{{ $t("message.sidebar.orders") }} </h4>
            </div>
            <div >
              <b-col lg="4" class="my-1">
                <b-form-group
                    label="Search"
                    label-for="filter-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                >
                  <b-input-group size="sm">
                    <b-form-input
                        id="filter-input"
                        v-model="filter"
                        type="search"
                        placeholder="Type to Search"
                    ></b-form-input>

                    <b-input-group-append>
                      <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-table striped hover
                       :items="orders"
                       :fields="fields"
                       :filter="filter"
                       :current-page="currentPage"
                       :per-page="perPage"
                       stacked="md"
                       show-empty
                       small
              >
                <!-- A custom formatted data column cell -->
                <template #cell(SL)="row">
                  {{ row.index+1 }}
                </template>
                <template #cell(payment_type)="row">
                  {{ payment_type[row.item.payment_type] }}
                </template>
                <template #cell(created_at)="row">
                  {{ moment(row.created_at).fromNow() }}
                </template>
                <!-- A custom formatted data column cell -->
                <template #cell(status)="row">
                  <p :style="row.item.order_status !=2?'color:green':'color:red'">{{ status[row.item.order_status] }}</p>
                </template>
                <template #cell(view)="row">
                  <template>
                    <i class="fa fa-eye" style="color: green; cursor: pointer"></i>
                  </template>
                </template>
                <template #cell(action)="row">
                  <template v-if="row.item.order_status ==0">
                    <button @click="statusChange(row.item.id,1,(row.index))" type="button" class="btn btn-primary">
                      <span style="color: black">Accept</span>
                    </button>
                    <button @click="statusChange(row.item.id,2,(row.index))" type="button" class="btn btn-danger">
                      <span style="color: black">Cancel</span>
                    </button>
                  </template>
                  <template v-if="row.item.order_status==1">
                    <button @click="statusChange(row.item.id,3,(row.index))" type="button" class="btn btn-primary">
                      <span style="color: black">Shipping</span>
                    </button>
                  </template>
                  <template v-if="row.item.order_status==3">
                    <button @click="statusChange(row.item.id,4,(row.index))" type="button" class="btn btn-primary">
                      <span style="color: black">Delivered</span>
                    </button>
                  </template>
                </template>
              </b-table>
              <b-row>
                <b-col sm="5" md="3" class="my-1">
                  <b-form-group
                      label="Per page"
                      label-for="per-page-select"
                      label-cols-sm="6"
                      label-cols-md="4"
                      label-cols-lg="3"
                      label-align-sm="right"
                      label-size="sm"
                      class="mb-0"
                  >
                    <b-form-select
                        id="per-page-select"
                        v-model="perPage"
                        :options="pageOptions"
                        size="sm"
                    ></b-form-select>
                  </b-form-group>
                </b-col>
                <b-col sm="5" md="5" class="my-1">
                </b-col>
                <b-col sm="7" md="4" class="my-1">
                  <b-pagination
                      v-model="currentPage"
                      :total-rows="totalRows"
                      :per-page="perPage"
                      align="fill"
                      size="sm"
                      class="my-0"
                  ></b-pagination>
                </b-col>
              </b-row>
            </div>
          </div> <!-- /.card -->
        </div>  <!-- /.col-lg-8 -->
      </div>
    </div>
    <!-- /.orders -->
  </div>
</template>
<script>
import ApiService from "../../../core/services/api.service";
import {
  BButton,BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
  BModal, BForm,BRow, BCol,
} from 'bootstrap-vue'
import moment from "moment";
import {COMPANY_TRADE_LIST} from "../../../core/services/store/module/companyTrade";
export default {
  name: 'order',
  components:{
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BButton,
    BModal,
    BForm,BRow, BCol,
  },
  data() {
    return {
      moment,
      orders: [],
      filter:null,
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 20,50,100],
      fields: [
        {
          label: 'SL',
          key: 'SL',
          sortable: false
        },
        {
          label: 'Buyer Name',
          key: 'name',
          sortable: true
        },
        {
          label: 'Invoice No',
          key: 'transaction_id',
          sortable: true
        },
        {
          label: 'Unit Price',
          key: 'offer_price',
          sortable: true
        },
        {
          key: 'quantity',
          sortable: false
        },
        {
          key: 'sub_total',
          sortable: false
        },
        {
          key: 'shipping_charge',
          sortable: false
        },
        {
          label: 'Tax',
          key: 'vat',
          sortable: false
        },
        {
          key: 'total_amount',
          sortable: false
        },
        {
          key: 'payment_type',
          sortable: false
        },
        {
          key: 'created_at',
          label: 'Order at',
          sortable: false,
        },
        {
          key: 'status',
          label: 'Status',
          sortable: false,
        },
        { key: 'view', label: 'view', sortable: false, },
        {
          key: 'action',label: 'Action',sortable: false,},
      ],
      payment_type:{
        0:'Cash On delivery',
        1:'SSL Commerze',
      },
      status:{
        0:'Pending',
        1:'Approved',
        2:'Canceled',
        3:'Shipping',
        4:'Delivered',
      },
    }
  },
  created() {
    this.getOrders();
    const plugin = document.createElement("script");
    plugin.setAttribute(
        "src",
        "https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"
    );
    plugin.async = true;
    document.head.appendChild(plugin);
  },
  methods: {
    getOrders() {
      ApiService.post('get/supplier/order/information').then((response) => {
        this.orders = response.data;
      }).catch((error) => {
        this.orders = [];
      })
    },
    /*
    * method for order status change
    * */
    statusChange(id,status,index){
      let message='';
      if (status ==1) message='Yes,approve it'
      else if (status ==2) message='Yes,cancel it'
      else if (status ==3) message='Yes,shipping it'
      else if (status ==4) message='Yes,delivered it'
      swal({
        title: 'Are you sure?',
        text: "You want to change this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: message,
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          ApiService.post(`supplier/order/status${id}/change${status}`)
              .then((response) => {
                this.$toaster.success(response.data.message);
                this.getOrders();
              }).catch((error)=>{
            this.$toaster.error(error);
          });
        } else if (
            // Read more about handling dismissals
            result.dismiss === swal.DismissReason.cancel
        ) {
          swal(
              'Cancelled',
              'Your data is safe :)',
              'error'
          )
        }
      })
    }
  }
}
</script>
<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>