<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{ $t("message.customer_list.customers") }}
        </span>
        <h3 class="page-title">{{ $t("message.customer_list.customers_list") }}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{ $t("message.customer_list.add_new_customer") }}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="customerList" :columns="columns" :options="options" class="text-center">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="image" slot-scope="props">
          <img :src="props.row.photo_type == 0? showImage(props.row.photo):props.row.photo"
               class="avatar img-fluid img-thumbnail" width="60px">
        </div>
        <div slot="name" slot-scope="props">
          {{ props.row.first_name + ' ' + props.row.last_name }}
        </div>
        <div slot="approval" slot-scope="props">
          <b-button v-if="props.row.status == 3" size="sm">{{$t("message.all_seller.block")}}</b-button>
          <b-form-checkbox v-else value="2" unchecked-value="1" switch v-model="props.row.status"
                           @change="selectApprove(props.row)"></b-form-checkbox>
        </div>
        <div slot="options" slot-scope="props">
          <b-dropdown text="Actions" size="sm" variant="primary" class="m-md-2">
            <b-dropdown-item @click="userUpdateModalOpen(props.row)">{{ $t("message.customer_list.edit") }}
            </b-dropdown-item>
            <b-dropdown-item>{{ $t("message.customer_list.payment_history") }}</b-dropdown-item>
            <b-dropdown-item @click="selectApprove(props.row)">
              {{ props.row.status == 3 ? $t("message.customer_list.enable") : $t("message.customer_list.disable") }}
            </b-dropdown-item>
            <b-dropdown-item @click="deleteCustomer(props.row.id)">Delete</b-dropdown-item>
          </b-dropdown>
        </div>
      </v-client-table>
    </div>
    <!-- End Datatable -->
    <user-add-modal></user-add-modal>

    <user-edit-modal :user_info="selected_user"></user-edit-modal>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import UserAddModal from "../helper/UserAddModal";
import UserEditModal from "../helper/UserEditModal";

export default {
  name: "CustomerList",
  components: {UserAddModal, UserEditModal},
  data() {
    return {
      columns: ['serial', 'image', 'name', 'email','approval', 'options'],
      form: new Form({
        id: '',
      }),
      options: {
        headings: {
          serial: '#',
          name: 'Name',
        },
        sortable: ['name'],
        filterable: ['name', 'email']
      },
      selected_user: {},
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    selectApprove(e) {
      let status = e.status == 2 ? 3 : 2;
      this.$store.dispatch('UPDATE_CUSTOMER_STATUS', {id: e.id, status: status});
    },
    /*user add modal*/
    openModal() {
      this.$bvModal.show('brandModal');
    },
    /*user update modal*/
    userUpdateModalOpen(user) {
      console.log('selected user',user)
      this.selected_user = user;
      this.$emit('change_data', 'sent data');
      this.$bvModal.show('userUpdateModal');
    },
    selectApprove(e) {
      this.$store.dispatch('UPDATE_CUSTOMER_STATUS', {id: e.id, status: e.status});
    },
    deleteCustomer(id) {
      swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          this.form.delete('customer/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t('message.common.error'), data.data.message, 'warning')
                } else {
                  swal.fire(
                      this.$t('message.common.deleted'),
                      'Customer has been deleted.',
                      this.$t('message.common.succes')
                  )
                  this.$store.commit('CUSTOMER_REMOVE', id);
                }
              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'), this.$t('message.common.some_wrong'), 'warning')
              });
        }
      })
    },
  },
  created() {
    if (!this.customerList.length > 0) this.$store.dispatch('CUSTOMER_LIST');
  },
  computed: {
    ...mapGetters(["customerList"])
  }
}
</script>

<style scoped>

</style>
