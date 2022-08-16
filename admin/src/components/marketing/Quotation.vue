<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{$t("message.quotation.marketing")}}
        </span>
        <h3 class="page-title">{{$t("message.quotation.quotation_list")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(quotationList)" :columns="columns" class="text-center"
                      :options="options">
        <div slot="full_name" slot-scope="props">
          <p v-if="props.row.user">{{props.row.user.full_name}}</p>
        </div>
        <div slot="mobile" slot-scope="props">
          <p v-if="props.row.user">{{props.row.user.mobile}}</p>
        </div>
        <div slot="action" slot-scope="props">
          <CButtonGroup size="sm" class="mx-1">
            <template v-if="props.row.status === 0 && props.row.is_cancel===0">
              <CButton color="primary" @click="accept(props.row.id)">
                Accept
              </CButton>
              <CButton color="danger" @click="cancel(props.row.id)">
                Cancel
              </CButton>
            </template>
            <template v-else-if="props.row.status === 1 && props.row.is_cancel===0">
              <CButton color="secondary" @click="openQuotationEdit(props.row)">
                <font-awesome-icon icon="edit"/>
              </CButton>
              <CButton color="secondary" @click="openSupplierModal(props.row)">
                <i class="fa fa-eye">View</i>
              </CButton>
              <CButton color="secondary" @click="deleteQuotation(props.row.id)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </template>
            <template v-else>
              <span style="color: red">Canceled</span>
            </template>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->
    <!-- Modal -->
    <b-modal id="adminModal" title="Supplier assign"
             hide-footer>
      <b-form @submit.prevent="updateQuotation" @keydown="form.onKeydown($event)">
        <b-form-group label="Email  address :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              v-model="form.email" readonly disabled
              placeholder="Quotation mail"
          ></b-form-input>
        </b-form-group>
        <CRow>
          <CCol col="6" sm="4" md="4" class="mb-3 mb-xl-5">
            {{$t("message.quotation.select_supplier")}}
          </CCol>
          <CCol col="6" sm="8" md="8" class="mb-3 mb-xl-0">
            <v-select label="first_name" :filterable="false" :options="supplier" @search="onSearch"
                      @input="addNewSupplier"
                      :reduce="name => name.id" placeholder="Search Supplier by name, phone number" multiple>
              <template slot="no-options">
                {{$t("message.quotation.type_search_supplier")}}
              </template>
              <template #option="{ first_name, last_name }">
                <div class="d-center">
                  {{ first_name + ' ' + last_name }}
                </div>
              </template>
              <template slot="selected-option" slot-scope="supplier">
                <div class="selected d-center">
                  {{ supplier.first_name + ' ' + supplier.last_name }}
                </div>
              </template>
            </v-select>
          </CCol>
        </CRow>
        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
              <span v-if="form.busy" class="sr-only">{{$t("message.quotation.loading")}}</span>
              Update
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('adminModal')">Close</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->

    <!-- Modal -->
    <b-modal id="supplierModal" title="Assigned Supplier"
             hide-footer>
      <table class="table table-responsive">
        <tr>
          <td>Supplier Name</td>
          <td>Phone Number</td>
          <td>Status</td>
          <td>Response time</td>
        </tr>
        <tr v-for="(his,key) in quotation_history" :key="key">
          <td>{{his.user.full_name}}</td>
          <td>{{his.user.mobile}}</td>
          <td>
            <p :style="his.status==1?'color:green':'color:red'">{{ status[his.status]}}</p>
          </td>
          <td>
            <template v-if="his.response_at">
              {{ moment(his.response_at).startOf('hour').from(his.created_at)}}
            </template>
          </td>
        </tr>
      </table>
    </b-modal>
    <!-- End Modal -->

  </div>
</template>

<script>
import _ from 'lodash';
import {mapGetters} from "vuex";
import {QUOTATION_LIST, QUOTATION_MODIFY, QUOTATION_REMOVE} from "@/core/services/store/module/quotation";
import ApiService from "@/core/services/api.service";
import {QUOTATION_CANCEL} from "../../core/services/store/module/quotation";
import moment from 'moment'
export default {
  name: "quotation",
  data() {
    return {
      moment,
      form: new Form({
        id: '',
        email: '',
        user_ids: '',
      }),
      supplier: [],
      columns: ['full_name','mobile', 'product', 'details', 'quantity', 'action'],
      options: {
        headings: {
          full_name: 'Name',
          mobile: 'Phone Number',
          product: 'Product / Service',
          quantity: 'Quantity',
        },
        sortable: [],
        filterable: ['full_name','mobile', 'product']
      },
      quotation_history:[],
      status: {
        0: 'pending',
        1: 'accept',
        2: 'cancel',
      },
    }
  },
  methods: {
    openQuotationEdit(data) {
      this.form.reset();
      this.form.clear();
      this.form.email = data.user?data.user.email:null;
      this.form.id = data.id;
      this.$bvModal.show('adminModal');
    },
    /*
    * method for supplier modal
    * */
    openSupplierModal(data){
      this.quotation_history=data.quotation_users;
      this.$bvModal.show('supplierModal');
    },
    deleteQuotation(id) {
      swal.fire({
        title:  this.$t("message.quotation.are_you_sure"),
        text: this.$t("message.quotation.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.quotation.delete_it")
      }).then((result) => {
        if (result.value) {
          this.form.delete('quotation/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t("message.common.deleted"),
                      this.$t("message.quotation.quotation_deleted"),
                      this.$t("message.common.succes")
                  )
                  this.$store.commit(QUOTATION_REMOVE, id);
                }
              })
              .catch(() => {
                swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
    /*
    * method for accept request
    * */
    accept(id) {
      swal.fire({
        title:  this.$t("message.quotation.are_you_sure"),
        text: this.$t("message.quotation.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.quotation.yes")
      }).then((result) => {
        if (result.value) {
          this.form.put(`quotation/request/accept${id}`)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t("message.common.succes"),
                      data.data.message,
                  )
                  this.$store.commit(QUOTATION_MODIFY, id);
                }
              })
              .catch(() => {
                swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
    cancel(id) {
      swal.fire({
        title:  this.$t("message.quotation.are_you_sure"),
        text: this.$t("message.quotation.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.quotation.yes")
      }).then((result) => {
        if (result.value) {
          this.form.put(`quotation/request/cancel${id}`)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  swal.fire(
                      data.data.message,
                      this.$t("message.common.succes")
                  )
                  this.$store.commit(QUOTATION_CANCEL, id);
                }
              })
              .catch(() => {
                swal(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      let url = 'user/supplier-search?searchSupplier=' + search;
      ApiService.get(url)
          .then(res => {
            vm.supplier = res.data;
            loading(false);
          });
    }, 350),
    addNewSupplier(e) {
      this.form.user_ids = e;
    },
    updateQuotation() {
      this.form.put('quotation/' + this.form.id)
          .then(() => {
            this.$bvModal.hide('adminModal');
            toast.fire({
              icon: 'success',
              title: this.$t("message.quotation.supplier_assign_successfully")
            });
          })
          .catch((error) => {
            let data = error.response;
            if (data.status === 422) {
              let allData = '', mainData = '';
              $.each(data.data.errors, function (key, value) {
                if ($.isPlainObject(value)) {
                  $.each(value, function (key, value) {
                    allData += value + "<br/>";
                  });
                } else {
                  mainData += value + "<br/>";
                }
              });
              swal.fire({
                title: 'Warning',
                html: mainData,
                type: 'error'
              })
            } else {
              swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            }
          })
    }
  },
  created() {
    this.$store.dispatch(QUOTATION_LIST)
  },
  computed: {
    ...mapGetters(["quotationList"])
  },
}
</script>

<style scoped>

</style>