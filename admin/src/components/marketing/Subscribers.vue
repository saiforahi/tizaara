<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{$t("message.quotation.marketing")}}
        </span>
        <h3 class="page-title">{{$t("message.quotation.subscriber_list")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="subscribers" :columns="columns" class="text-center"
                      :options="options">
        <div slot="full_name" slot-scope="props">
          <p v-if="props.row.email">{{props.row.email}}</p>
        </div>
        <div slot="action" slot-scope="props">
          <CButtonGroup size="sm" class="mx-1">
            <template>
              <CButton color="danger" @click="remove(props.row.id)">
                Remove
              </CButton>
            </template>
          </CButtonGroup>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->
  </div>
</template>

<script>
import _ from 'lodash';
import {mapGetters} from "vuex";
import ApiService from "@/core/services/api.service";
import moment from 'moment'
import {SUBSCRIBER_LIST, SUBSCRIBER_REMOVE} from "../../core/services/store/module/subscriber";
import Vue from 'vue'
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'
Vue.use(Toaster, {timeout: 5000})
export default {
  name: "subscriber",
  data() {
    return {
      moment,
      supplier: [],
      columns: ['email', 'action'],
      options: {
        headings: {
          email: 'Email',
          action: 'Action',
        },
        sortable: [],
        filterable: ['email']
      },
      quotation_history:[],
    }
  },
  methods: {
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
    remove(id) {
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
          ApiService.delete(`remove/subscriber/from/admin${id}`)
              .then((response) => {
                this.$store.commit(SUBSCRIBER_REMOVE, id);
                this.$toaster.success(response.data.message);
              })
              .catch(() => {
                this.$toaster.error(this.$t("message.common.something_wrong"));
              });
        }
      })
    },
  },
  created() {
    this.$store.dispatch(SUBSCRIBER_LIST)
  },
  computed: {
    ...mapGetters(["subscribers"])
  },
}
</script>

<style scoped>

</style>