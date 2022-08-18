<template>
  <div class="card">
    <div class="card-header">
      {{ $t("message.factory.factory_info") }}
      <a href="javascript:void(0)" v-if="mode==='factory'" @click.prevent="mode = 'factory_add'" class="float-right">
        <i class="fas fa-plus-square" style="color: #03a9f3"></i>
        {{ $t("message.factory.add") }}</a>
    </div>
    <div v-if="mode ==='factory'" class="card-body">
      <div class="container">
        <div class="row">
          <div class="table-responsive">
            <table id="table" class="table table-hover" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">#SL</th>
                  <th scope="col">Name</th>
                  <th scope="col">Factory space</th>
                  <th scope="col">Staff number</th>
                  <th scope="col">RND staff</th>
                  <th scope="col">Production line</th>
                  <th scope="col">Annual output</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(factory,key) in company_factories">
                  <th>{{ key+1 }}</th>
                  <td>{{ factory.name }}</td>
                  <td>{{ factory.factory_space }}</td>
                  <td>{{factory.staff_number?factory.staff_number.number:'' }}</td>
                  <td>{{factory.rnd_staff?factory.rnd_staff.name:'' }}</td>
                  <td>{{factory.production_line?factory.production_line.name:'' }}</td>
                  <td>{{factory.annual_output?factory.annual_output.output:'' }}</td>
                  <td>
                    <div class="edit-delete" style="cursor: pointer">
                      <span class="text-primary cursor-pointer" @click="edit(factory)" @click.prevent="mode = 'factory_update'">
                        <i class="fas fa-edit mr-2" aria-hidden="true"></i>
                      </span>
                      <span class="text-danger cursor-pointer custom_padding" @click="remove(factory.id)">
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
    <FactoryAdd v-if="mode==='factory_add'" @view="mode = 'factory'"/>
    <FactoryEdit :factory="factory" v-if="mode==='factory_update'" @view="mode = 'factory'"/>
  </div>
</template>

<script>

import FactoryEdit from "./FactoryEdit";
import FactoryAdd from "./FactoryAdd";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import axios from "axios";
import {CERTIFICATE_LIST} from "../../../../core/services/store/module/certificate";
import {RND_QC_STAFF_PRODUCTION_OUTPUT_LIST} from "../../../../core/services/store/module/rndQcPO";
import {COMPANY_FACTORY_LIST} from "../../../../core/services/store/module/factory";
import ApiService from "../../../../core/services/api.service";

export default {

  name: "Factory",
  data() {
    return {
      mode: 'factory',//this mode for add
      editText: false,
      factory:{},
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
    this.$store.dispatch(COMPANY_FACTORY_LIST);
    this.$store.dispatch(RND_QC_STAFF_PRODUCTION_OUTPUT_LIST);
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    addressFilter(data, key) {
      return data ? data[key] : '';
    },
    edit(factory) {
      this.factory=factory;
    },
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
          ApiService.delete(`company/factory/info/remove${id}`)
              .then((response) => {
                this.$toaster.success(response.data.message);
                this.$store.dispatch(COMPANY_FACTORY_LIST);
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
    ...mapGetters(['company_factories']),
  },
  components: {
    FactoryEdit,FactoryAdd
  }


}
</script>

<style scoped>

</style>
