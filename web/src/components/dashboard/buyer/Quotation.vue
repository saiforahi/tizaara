<template>
  <div class="content">
    <!-- Orders -->
    <div class="orders">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <h4 class="box-title">{{ $t("message.sidebar.quotation")}} </h4>
            </div>
            <div class="card-body--">
              <div class="table-stats ov-h">
                <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                    <th class="serial">#</th>
                    <th>{{ $t("message.dashboard.product")}}</th>
                    <th>{{ $t("message.dashboard.quantity")}}</th>
                    <th>{{ $t("message.dashboard.status")}}</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(qtn,key) in quotations" :key="key">
                      <td class="serial">{{key+1}}</td>
                      <td>{{qtn.product}}</td>
                      <td>{{qtn.quantity}}</td>
                      <td>
                        <p :style="qtn.status==1?'color:green':'color:red'">{{status[qtn.status]}}</p>
                      </td>
                      <td>
                        <template v-if="qtn.status==1">
                          <i style="cursor: pointer; color: green" @click="openSupplierModal(qtn)" class="fa fa-eye"></i>
                        </template>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div> <!-- /.table-stats -->
            </div>
          </div> <!-- /.card -->
        </div>  <!-- /.col-lg-8 -->
      </div>
    </div>
    <!-- /.orders -->
    <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="contact_modal_title">Assigned Supplier</h4>
            <button type="button" style="float: right;" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body container col-sm-12">
            <div class="contact-supplier">
              <form method="post" action="javascript:void(0)">
                <div class="main-warp">
                  <div class="product-items">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Supplier Name</th>
                          <th scope="col">Phone Number</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(his,key) in quotation_history" :key="key">
                          <th scope="row">{{key+1}}</th>
                          <th>{{his.user.full_name}}</th>
                          <th>{{his.user.mobile}}</th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import ApiService from "../../../core/services/api.service";

export default {
  name:'rfq',
  data() {
    return {
        quotations:[],
        quotation_history:[],
        status:{
          0:'Pending',
          1:'Accepted',
          2:'Cancel',
        },
    }
  },
  created() {
    this.getQuotations();
  },
  methods:{
    /*
    * method for supplier modal
    * */
    openSupplierModal(data){
      this.quotation_history=data.quotation_users;
      $('#supplierModal').modal('show');
    },
    /*
    * method for get all quotations
    *
    * */
    getQuotations(){
      ApiService.get('quotation/for/buyer').then((response)=>{
          this.quotations=response.data;
      }).catch(()=>{
          this.quotations=[];
      });
    }
  }
}
</script>
<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>