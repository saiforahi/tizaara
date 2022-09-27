<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">SELLER</span>
        <h3 class="page-title">All EcommerceZone Requests</h3>
      </div>
      <!-- <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="$router.push({name: 'Flash Deals Create'})">{{$t("message.flash.create_new_flash_deal")}}</CButton>
        </div>
      </div> -->
    </div><hr>
    <!-- End Page Header -->
    <!-- <b-button class="float-right" @click="$router.push({name: 'Supplier Flash Deals'})">{{$t("message.flash.request_from_supplier")}}
    </b-button> -->

    <div class="clearfix"></div>

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="Object.values(ecom_zone_requestsList)" :columns="columns" class="text-center" :options="options">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="logo" slot-scope="props">
          <img v-lazy="showImage(props.row.banner)" class="border" width="80px">
        </div>
        <div slot="status" slot-scope="props">
          <b-form-checkbox @change="e => changeStatus(e, props.row.id)" v-model="props.row.status" name="check-button" switch value="1" unchecked-value="0"></b-form-checkbox>
        </div>
        <div slot="action" slot-scope="props">
          <CButtonGroup size="sm" class="mx-1">
            <CButton color="secondary" @click="destroy(props.row.id)">
              <font-awesome-icon icon="trash-alt"/>
            </CButton>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->

  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {ECOM_ZONE_LIST,ECOM_ZONE_REQUEST_REMOVE} from "@/core/services/store/module/ecommerce_requests";
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";

export default {
  name: "EcomRequests",
  data() {
    return {
      columns: ['serial', 'product_name', 'user_name', 'status', 'action'],
      options: {
        headings: {
          serial: '#',
          title: 'Title',
          logo: 'Banner',
          start_date: 'Start Date',
          end_date: 'End Date'
        },
        sortable: ['name'],
        filterable: ['name', 'meta_title']
      }
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    changeStatus(e, id) {
      ApiService.post('ecom-zone-status', {id: id, status: e});
    },
    destroy(id) {
      let that = this;
      swal.fire({
        title:  this.$t("message.flash.are_you_sure"),
        text: this.$t("message.flash.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.flash.delete_it")
      }).then((result) => {
        if (result.value) {
          ApiService.delete('ecom-zone-request/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  swal.fire(
                      "Ecom Zone Request deleted",
                      this.$t("message.flash.flash_deleted"),
                      this.$t("message.common.succes")
                  )
                  this.$store.commit(ECOM_ZONE_REQUEST_REMOVE,id);
                }

              })
              .catch(() => {
                swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.ecom_zone_requestsList.length < 1 ? this.$store.dispatch(ECOM_ZONE_LIST) : '';
  },
  computed: {
    ...mapGetters(["ecom_zone_requestsList"])
  },
}
</script>

<style scoped>

</style>