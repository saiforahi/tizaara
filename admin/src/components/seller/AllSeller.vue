<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.all_seller.seller")}}
        </span>
        <h3 class="page-title">{{$t("message.all_seller.all_seller")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <CButton block color="info" @click="openModal">{{$t("message.all_seller.add_new_seller")}}</CButton>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Datatable -->
    <div id="people" class="dataTables_wrapper no-footer my-5">
      <v-client-table :data="sellerList" :columns="columns" :options="options" class="text-center">
        <div slot="serial" slot-scope="props">
          {{ props.index }}
        </div>
        <div slot="image" slot-scope="props">
          <img :src="props.row.photo_type == 0? showImage(props.row.photo):props.row.photo" class="avatar img-fluid img-thumbnail" width="60px">
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
            <b-dropdown-item>{{$t("message.all_seller.payment_history")}}</b-dropdown-item>
            <b-dropdown-item @click="userUpdateModalOpen(props.row)">{{$t("message.all_seller.edit")}}</b-dropdown-item>
            <b-dropdown-item @click="changeStatus(props.row.id)">
              {{props.row.status ==2?'Ban this seller':'Unban this seller'}}
            </b-dropdown-item>
            <b-dropdown-item @click="deleteSeller(props.row.id)">Delete</b-dropdown-item>
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
import {validationMixin} from "vuelidate";
import UserAddModal from "../helper/UserAddModal";
import UserEditModal from "../helper/UserEditModal";
import ApiService from "../../core/services/api.service";
export default {
  name: "AllSeller",
  mixins: [validationMixin],
  components:{UserAddModal, UserEditModal},
  data() {
    return {
      form: new Form({
        id: '',
      }),
      validation: true,
      editMode: false,
      columns: ['serial', 'image', 'name', 'email', 'approval', 'options'],
      options: {
        headings: {
          serial: '#',
          name: 'Name',
        },
        sortable: ['name'],
        filterable: ['name', 'email']
      },
      selected_user:{},
    }
  },
  methods: {
    openModal() {
      this.$bvModal.show('brandModal');
    },
    /*user update modal*/
    userUpdateModalOpen(user) {
      this.selected_user=user;
      this.$emit('change_data','sent data');
      this.$bvModal.show('userUpdateModal');
    },
    showImage(e) {
      return api_base_url + e;
    },
    selectApprove(e) {
      this.$store.dispatch('UPDATE_STATUS', {id: e.id, status: e.status});
    },
    changeStatus(id){
      swal.fire({
        title: this.$t('message.brand.are_you_sure'),
        text: 'you want to do this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, do it'
      }).then((result) => {
        if (result.value) {
          ApiService.post(`seller/status/change${id}`)
              .then((response) => {
                this.$store.dispatch('SELLER_LIST');
                swal.fire(
                    response.data.message,
                    this.$t('message.common.succes')
                )
              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'),this.$t('message.common.something_wrong'), 'warning')
              });
        }
      })
    },
    deleteSeller(id) {
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
                  this.$store.commit('SELLER_REMOVE', id);
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
    if (!this.sellerList.length > 0) this.$store.dispatch('SELLER_LIST');
  },
  computed: {
    ...mapGetters(["sellerList"])
  }
}
</script>

<style scoped>

</style>
