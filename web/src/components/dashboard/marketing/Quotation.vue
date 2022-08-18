<template>
  <div class="content pr-md-5">
    <div class="animated fadeIn">
      <!-- Page Header -->
      <div class="row mb-3">
        <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.quotation.marketing") }}
        </span>
          <h3 class="page-title">{{ $t("message.quotation.quotation_list") }}</h3>
        </div>
      </div>
      <!-- End Page Header -->

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
                 :items="quotationList"
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
          <template #cell(created_at)="row">
            {{ moment(timeFind(row.item),'YYYYMMDD').fromNow() }}
          </template>
          <!-- A custom formatted data column cell -->
          <template #cell(status)="row">
            <p :style="statusFind(row.item)==1?'color:green':'color:red'">{{ status[statusFind(row.item)] }}</p>
          </template>
          <template #cell(actions)="row">
            <template v-if="statusFind(row.item) ==0">
              <b-button size="sm" @click="accept(idFind(row.item))" class="mr-1 btn-primary">
                Accept
              </b-button>
              <b-button size="sm" @click="cancel(idFind(row.item))" class="mr-1 btn-danger">
                Cancel
              </b-button>
            </template>
            <template v-if="statusFind(row.item) ==1">
              <b-button size="sm" class="mr-1 btn-danger" @click="viewBuyer(row.item)">
                <i class="fa fa-eye"></i>
              </b-button>
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
    </div>

    <!-- cancel modal   -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="contact_modal_title">Cancel RFQ</h4>
            <button type="button" style="float: right;" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body container col-sm-12">
            <div class="contact-supplier">
              <form method="post" action="javascript:void(0)" @submit="submitCancelForm">
                <div class="main-warp">
                  <div class="message">
                    <b-form-textarea v-model="note" size="sm" name="note"
                                     placeholder="Write reason for why you cancel RFQ"
                                     rows="4" required>
                    </b-form-textarea>
                  </div>
                </div>
                <div class="col text-right" style="text-align: right; padding: 12px 20px">
                  <button type="submit" class="btn btn-primary btn-lg submit">
                    Submit
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="buyerModal" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="buyer_modal_title">Buyer Information</h4>
            <button type="button" style="float: right;" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body container col-sm-12">
            <div class="contact-supplier">
              <form method="post" action="javascript:void(0)">
                <div class="main-warp">
                  <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>{{selected_buyer.user?selected_buyer.user.full_name:''}}</th>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <th>{{selected_buyer.user?selected_buyer.user.email:''}}</th>
                    </tr>
                    <tr>
                      <th>Phone Number</th>
                      <th>{{selected_buyer.user?selected_buyer.user.mobile:''}}</th>
                    </tr>
                    </thead>
                  </table>
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
import {QUOTATION_LIST} from "@/core/services/store/module/quotation";
import {mapGetters} from "vuex";
import ApiService from "../../../core/services/api.service";
import moment from "moment";
export default {
  name: "quotation",
  data() {
    return {
      moment,
      filter:null,
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 20,50, { value: 100, text: "Show a lot" }],
      fields: [
        {
          label: 'SL',
          key: 'SL',
          sortable: false
        },
        {
          key: 'product',
          sortable: true
        },
        {
          key: 'details',
          sortable: false
        },
        {
          key: 'quantity',
          sortable: false
        },
        {
          key: 'unit.name',
          label: 'Unite',
          sortable: true,
        },
        {
          key: 'created_at',
          label: 'Request at',
          sortable: false,
        },
        {
          key: 'status',
          label: 'Status',
          sortable: false,
        },
        { key: 'actions', label: 'Actions', sortable: false, }
      ],
      status:{
        0:'Pending',
        1:'Accepted',
        2:'Canceled',
      },
      note:'',
      cancel_id:null,
      selected_buyer:{},
    }
  },
  created() {
    this.$store.dispatch(QUOTATION_LIST)
    const plugin = document.createElement("script");
    plugin.setAttribute(
        "src",
        "https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"
    );
    plugin.async = true;
    document.head.appendChild(plugin);
  },
  methods:{
    statusFind(data){
      return data.quotation_users?data.quotation_users[0].status:null;
    },
    idFind(data){
      return data.quotation_users?data.quotation_users[0].id:null;
    },
    timeFind(data){
      return data.quotation_users?data.quotation_users[0].created_at:null;
    },
    /*
    * method for accept quotations
    * */
    accept(id){
      swal({
        title: 'Are you sure?',
        text: "You want to accept this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, accept it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          ApiService.post(`supplier/quotation/accept${id}`)
              .then((response) => {
                this.$toaster.success(response.data.message);
                this.$store.dispatch(QUOTATION_LIST);
              }).catch((error)=>{
                this.$toaster.error(error);
          });
        }
      })
    },
    /*
    * method for cancel quotation
    * */
    cancel(id){
      this.note='';
      this.cancel_id=id;
      $('#cancelModal').modal('show');
    },
    submitCancelForm(){
        ApiService.post(`supplier/quotation/cancel${this.cancel_id}/${this.note}`).then((response)=>{
          this.$toaster.success(response.data.message);
          $('#cancelModal').modal('hide');
          this.cancel_id=null;
          this.$store.dispatch(QUOTATION_LIST);
        }).catch((error)=>{
          this.$toaster.error(error.response.data.message);
        })
    },
    /*
    * method for view buyer information
    * */
    viewBuyer(data){
      this.selected_buyer=data;
      $('#buyerModal').modal('show');
    },
  },
  computed: {
    ...mapGetters(["quotationList"]),
  }
}
</script>

<style scoped>

</style>