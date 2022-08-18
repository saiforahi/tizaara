<template>
  <div class="card">
    <div class="card-header">
      {{ $t("message.port.port_info") }}
      <a href="javascript:void(0)" v-if="mode==='port'" @click.prevent="mode = 'port_add'" class="float-right">
        <i class="fas fa-plus-square" style="color: #03a9f3"></i>
        {{ $t("message.port.add") }}</a>
    </div>
    <div v-if="mode ==='port'" class="card-body">
      <div class="container">
        <div class="row">
          <div class="table-responsive">
            <table id="table" class="table table-hover" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">#SL</th>
                  <th scope="col">Port Name</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(port,key) in nearest_ports">
                  <th>{{key+1}}</th>
                  <th>{{ port.name}}</th>
                  <td>
                    <div class="edit-delete" style="cursor: pointer">
                      <span class="text-primary cursor-pointer" @click="edit(port)" @click.prevent="mode = 'port_update'">
                        <i class="fas fa-edit 2x mr-2" aria-hidden="true"></i>
                      </span>
                      <span class="text-danger cursor-pointer custom_padding" @click="remove(port.id)">
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
    <PortAdd v-if="mode==='port_add'" @view="mode = 'port'"/>
    <PortEdit :port="port" v-if="mode==='port_update'" @view="mode = 'port'"/>
  </div>
</template>

<script>

import PortEdit from "./PortEdit";
import PortAdd from "./PortAdd";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import {COMPANY_FACTORY_LIST} from "../../../../core/services/store/module/factory";
import {NEAREST_PORT_LIST} from "../../../../core/services/store/module/nearestPort";
import ApiService from "../../../../core/services/api.service";

export default {

  name: "Port",
  data() {
    return {
      mode: 'port',//this mode for add
      editText: false,
      port:{},
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
    this.$store.dispatch(NEAREST_PORT_LIST);
  },
  methods: {
    edit(port){
      this.port=port;
    },
    /*
    * method for remove
    * */
    remove(id){
      swal({
        title: 'Are you sure?',
        text: "You want to remove this!",
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
          ApiService.delete(`company/nearest/port/remove${id}`)
              .then((response) => {
                this.$toaster.success(response.data.message);
                this.$store.dispatch(NEAREST_PORT_LIST);
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
    ...mapGetters(['nearest_ports']),
  },
  components: {
    PortAdd,PortEdit
  }


}
</script>

<style scoped>

</style>
