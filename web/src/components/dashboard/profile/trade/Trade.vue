<template>
  <div>
    <div class="card">
      <div class="card-header">
        {{ $t("message.trade.trade_info") }}
        <a href="javascript:void(0)" v-if="mode==='trade'" @click.prevent="mode = 'trade_add'" class="float-right">
          <i class="fas fa-plus-square" style="color: #03a9f3"></i>
          {{ $t("message.trade.add") }}</a>
      </div>
      <div v-if="mode ==='trade'" class="card-body">
        <div class="container">
          <div class="row">
            <div class="table-responsive">
              <table id="table" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">#SL</th>
                    <th scope="col">Annual revenue</th>
                    <th scope="col">Export Started year</th>
                    <th scope="col">Export percentage</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(trade,key) in company_trades">
                    <th>{{key+1}}</th>
                    <td>{{trade.annual_revenue?trade.annual_revenue.revenue:'' }}</td>
                    <td>{{trade.export_started_year }}</td>
                    <td>{{trade.export_percent?trade.export_percent.percent:'' }}</td>
                    <td>
                      <div class="edit-delete" style="cursor: pointer">
                        <span class="text-primary cursor-pointer" @click="edit(trade)" @click.prevent="mode = 'trade_update'">
                          <i class="fas fa-edit mr-2" aria-hidden="true"></i>
                        </span>
                        <span class="text-danger cursor-pointer custom_padding" @click="remove(trade.id)">
                          <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        </span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <TradeAdd v-if="mode==='trade_add'" @view="mode = 'trade'"/>
      <TradeEdit :trade="trade" v-if="mode==='trade_update'" @view="mode = 'trade'"/>
    </div>
    <div><br></div>
    <Port />
  </div>
</template>

<script>

import TradeAdd from "./TradeAdd";
import TradeEdit from "./TradeEdit";
import Port from "../nearestPort/Port";
import {mapGetters} from "vuex";
import {
  COMPANY_ANNUAL_REVENUE_EXPORT_PERCENT_LIST,
  COMPANY_TRADE_LIST
} from "../../../../core/services/store/module/companyTrade";
import ApiService from "../../../../core/services/api.service";

export default {

  name: "Trade",
  data() {
    return {
      mode: 'trade',//this mode for add
      editText: false,
      trade:{},
    }
  },
  created() {
    const plugin = document.createElement("script");
    plugin.setAttribute(
        "src",
        "https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"
    );
    plugin.async = true;
    document.head.appendChild(plugin);
    this.$store.dispatch(COMPANY_TRADE_LIST);
    this.$store.dispatch(COMPANY_ANNUAL_REVENUE_EXPORT_PERCENT_LIST);
  },
  methods: {
    edit(trade){
      this.trade=trade;
    },
    /*
    * method for remove trade info
    * */
    remove(id){
      swal({
        title: 'Are you sure?',
        text: "You want to delete this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          ApiService.delete(`company/trade/info/remove${id}`)
              .then((response) => {
                this.$toaster.success(response.data.message);
                this.$store.dispatch(COMPANY_TRADE_LIST);
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
  },
  computed: {
    ...mapGetters(['company_trades']),
  },
  components: {
    TradeAdd,TradeEdit,Port
  }


}
</script>

<style scoped>

</style>
