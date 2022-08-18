<template>
  <div class="card">
    <div class="card-header">
      {{ $t("message.certification.certificate_info") }}
      <a href="javascript:void(0)" v-if="mode==='certificate'" @click.prevent="mode = 'certificate_add'" class="float-right">
        <i class="fas fa-plus-square" style="color: #03a9f3"></i>
        {{ $t("message.certification.add") }}</a>
    </div>
    <div v-if="mode ==='certificate'" class="card-body">
      <div class="container">
        <div class="row">
          <div class="table-responsive">
            <table id="table" class="table table-hover" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">SL</th>
                  <th scope="col">Certification Name</th>
                  <th scope="col">Reference Number</th>
                  <th scope="col">Issued by</th>
                  <th scope="col">Start Date</th>
                  <th scope="col">End Date</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
              <tr v-for="(certificate,key) in certificates">
                  <th>{{ key+1 }}</th>
                  <th>{{ certificate.name }}</th>
                  <td>{{ certificate.reference_number }}</td>
                  <td>{{ certificate.issued_by }}</td>
                  <td>{{ moment(certificate.start_date).format('Y-MM-DD') }}</td>
                  <td>{{ moment(certificate.end_date).format('Y-MM-DD') }}</td>
                  <td>
                    <div class="edit-delete" style="cursor: pointer">
                      <span class="text-primary cursor-pointer" @click="edit(certificate)" @click.prevent="mode = 'certificate_update'">
                        <i class="fas fa-edit mr-2" aria-hidden="true"></i>
                      </span>
                      <span class="text-danger cursor-pointer custom_padding" @click="remove(certificate.id)">
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
    <CertificateAdd v-if="mode==='certificate_add'" @view="mode = 'certificate'"/>
    <CertificateEdit :certificate="certificate" v-if="mode==='certificate_update'" @view="mode = 'certificate'"/>
  </div>
</template>

<script>

import CertificateEdit from "./CertificateEdit";
import CertificateAdd from "./CertificateAdd";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import {CERTIFICATE_LIST} from "../../../../core/services/store/module/certificate";
import moment from "moment";
import ApiService from "../../../../core/services/api.service";
export default {

  name: "Certification",
  data() {
    return {
      moment,
      mode: 'certificate',//this mode for add
      editText: false,
      certificate:{},//selected certificate
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
    this.$store.dispatch(CERTIFICATE_LIST);
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    addressFilter(data, key) {
      return data ? data[key] : '';
    },
    edit(certificate){
      this.certificate=certificate;
    },
    /*
    * method for remove certificate
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
          ApiService.delete(`certificates/remove${id}`)
              .then((response) => {
                this.$toaster.success(response.data.message);
                this.$store.dispatch(CERTIFICATE_LIST);
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
    ...mapGetters(['certificates']),
  },
  components: {
    CertificateEdit,CertificateAdd
  }


}
</script>

<style scoped>

</style>
