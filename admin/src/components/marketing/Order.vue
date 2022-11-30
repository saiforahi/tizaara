<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.flash.marketing")}}
        </span>
        <h3 class="page-title">{{$t("message.flash.all_orders")}}</h3>
      </div>
    </div><hr>

    <div class="clearfix"></div>

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="orderList" :columns="columns" class="text-center" :options="options">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="image" slot-scope="props">
          <img v-lazy="showImage(jsonDecode(props.row.product).thumbnail_img)" class="border" width="80px">
        </div>
        <div slot="payment_status" slot-scope="props">
          <template>
            <span v-if="props.row.payment_status==1" class="badge badge-primary">Paid</span>
            <span v-else class="badge badge-warning">Not Paid</span>
          </template>
        </div>
        <div slot="payment_type" slot-scope="props">
          <template v-if="props.payment_type ==1 && props.status ==='Pending'">
              <span style="color:red;">Rejected</span>
          </template>
          <template v-else>{{ payment_type[props.row.payment_type] }}</template>
        </div>
        <div slot="product_name" slot-scope="props">
          {{ jsonDecode(props.row.product).name }}
        </div>
        <div slot="created_at" slot-scope="props">
          {{ moment(props.row.created_at).fromNow() }}
        </div>
        <div slot="approval_status" slot-scope="props">
          <span :style="props.row.approval_status==1?'color:green':props.row.approval_status==2?'color:red':'color:blue'">{{ status[props.row.approval_status] }}</span>
        </div>
        <div slot="order_status" slot-scope="props">
          {{ status[props.row.order_status] }}
        </div>
        <div slot="action" slot-scope="props">
          <CButtonGroup v-if="props.row.approval_status==0 && props.payment_type !=1 && props.status !=='Pending'" size="sm" class="mx-1">
            <CButton color="primary" @click="statusChange(props.row.id,1,(props.index-1))">Approve</CButton>
            <CButton color="danger" @click="statusChange(props.row.id,2,(props.index-1))">Cancel</CButton>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->

  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";
import {ORDER_LIST, ORDER_STATUS_CHANGE} from "../../core/services/store/module/order";
import moment from "moment";
export default {
  name: "Order",
  data() {
    return {
      moment,
      columns: ['serial', 'image', 'product_name', 'supplier.full_name', 'name', 'unit_price','offer_price','quantity',
        'sub_total','shipping_charge','vat','total_amount','payment_status','payment_type','created_at','approval_status','order_status', 'action'],
      options: {
        headings: {
          serial: '#',
          image: 'Image',
          product_name: 'Product name',
          'supplier.full_name': 'Supplier Name',
          name: 'Buyer Name',
          unit_price: 'Unit price',
          offer_price: 'Offer price',
          quantity: 'quantity',
          sub_total: 'Sub Total',
          shipping_charge: 'shipping charge',
          vat: 'tax',
          total_amount: 'Total Amount',
          payment_status: 'Payment Status',
          payment_type: 'Payment Type',
          create_at: 'Created At',
          approval_status: 'Approval Status',
          order_status: 'Order Status',
        },
        sortable: ['name'],
        filterable: ['name', 'meta_title']
      },
      payment_type:{
        0:'Cash On delivery',
        1:'ShurjoPay',
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
  methods: {
    jsonDecode(data){
      let docoded={};
      try {
        docoded =JSON.parse(data);
      }catch (e){
        docoded={};
      }
      return docoded;
    },
    showImage(e) {
      return api_base_url + e;
    },
    changeStatus(e, id) {
      ApiService.post('flash-deals-status', {id: id, status: e});
    },
    statusChange(id,status,index) {
     let message = '';
     if (status==1) message='Yes, approve it';
     if (status==2) message='Yes, cancel it';

      swal.fire({
        title:  this.$t("message.flash.are_you_sure"),
        text: this.$t("message.flash.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: message
      }).then((result) => {
        if (result.value) {
          ApiService.post(`admin/order/status${status}/change${id}`)
              .then((response) => {
                this.$store.commit(ORDER_STATUS_CHANGE, [status,index]);
                this.$toaster.success(response.data.message);
              })
              .catch(() => {
                swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.$store.dispatch(ORDER_LIST);
  },
  computed: {
    ...mapGetters(["orderList"])
  },
}
</script>

<style scoped>

</style>