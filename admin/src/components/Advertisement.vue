<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">Advertisement
        </span>
        <h3 class="page-title">Advertisement Manage</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center"></div>
    </div>
    <!-- End Page Header -->
    <!-- Datatable -->
    <div class="card card-body p-0 mt-5">
      <table class="table table-bordered table-hover">
        <thead>
        <tr>
          <th scope="col-1">#</th>
          <th scope="col-3">Advertisement Position</th>
          <th scope="col-3">Banner</th>
          <th scope="col-3">URL</th>
          <th scope="col-2">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Category item left side banner 800 X 300</td>
          <td class="col-3">
            <CImg width="300px" v-if="getAdvertisementByNo(1)" :src="showImage(getAdvertisementByNo(1).banner)"/>
          </td>
          <td class="col-3">
            <a v-if="getAdvertisementByNo(1)" :href="getAdvertisementByNo(1).url">{{getAdvertisementByNo(1).url}}</a>
          </td>
          
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModal(1)">
                <font-awesome-icon icon="plus"/>
              </CButton>
              <CButton color="secondary" @click="deleteBrand(1)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Category item right side banner 800 X 300</td>
          <td class="col-3">
            <CImg width="300px" v-if="getAdvertisementByNo(2)" :src="showImage(getAdvertisementByNo(2).banner)"/>
          </td>
          <td class="col-3">
            <a v-if="getAdvertisementByNo(2)" :href="getAdvertisementByNo(2).url" target="_blank">{{getAdvertisementByNo(2).url}}</a>
          </td>
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModal(2)">
                <font-awesome-icon icon="plus"/>
              </CButton>
              <CButton color="secondary" @click="deleteBrand(2)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Home page banner no 1 800 X 300</td>
          <td class="col-3">
            <CImg width="300px" v-if="getAdvertisementByNo(3)" :src="showImage(getAdvertisementByNo(3).banner)"/>
          </td>
          <td class="col-3">
            <a v-if="getAdvertisementByNo(3)" :href="getAdvertisementByNo(3).url">{{getAdvertisementByNo(3).url}}</a>
          </td>
          
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModal(3)">
                <font-awesome-icon icon="plus"/>
              </CButton>
              <CButton color="secondary" @click="deleteBrand(3)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        <tr>
          <th scope="row">4</th>
          <td>Home page banner no 2 800 X 300</td>
          <td class="col-3">
            <CImg width="300px" v-if="getAdvertisementByNo(4)" :src="showImage(getAdvertisementByNo(4).banner)"/>
          </td>
          <td class="col-3">
            <a v-if="getAdvertisementByNo(4)" :href="getAdvertisementByNo(4).url">{{getAdvertisementByNo(4).url}}</a>
          </td>
          
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModal(4)">
                <font-awesome-icon icon="plus"/>
              </CButton>
              <CButton color="secondary" @click="deleteBrand(4)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        <tr>
          <th scope="row">5</th>
          <td>Home page banner no 3 800 X 300</td>
          <td class="col-3">
            <CImg width="300px" v-if="getAdvertisementByNo(5)" :src="showImage(getAdvertisementByNo(5).banner)"/>
          </td>
          <td class="col-3">
            <a v-if="getAdvertisementByNo(5)" :href="getAdvertisementByNo(5).url">{{getAdvertisementByNo(5).url}}</a>
          </td>
          
          <td>
            <CButtonGroup size="sm" class="mx-1">
              <CButton color="secondary" @click="openModal(5)">
                <font-awesome-icon icon="plus"/>
              </CButton>
              <CButton color="secondary" @click="deleteBrand(5)">
                <font-awesome-icon icon="trash-alt"/>
              </CButton>
            </CButtonGroup>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <!-- End Datatable -->
    <!-- Modal -->
    <b-modal id="brandModal" title="New Advertisement Add" hide-footer>
      <b-form @submit.prevent="createBrand()" @keydown="form.onKeydown($event)">
        <b-form-group label="Banner Image :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-file
              accept="image/jpeg, image/png, image/jpg"
              placeholder="Choose banner image"
              @change="onInputChange" :state="validateState('banner')"
          ></b-form-file>
          <b-form-invalid-feedback v-if="!$v.form.banner.required">
            Please select banner image
          </b-form-invalid-feedback>
          <b-form-invalid-feedback id="fileErrorText" :state="validation"></b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Advertisement URL :"
                      label-cols-sm="5"
                      label-cols-lg="4">
          <b-form-input
              v-model="form.url"
              placeholder="Enter Ads URL"
          ></b-form-input>
        </b-form-group>
        <CRow class="justify-content-end">
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
              <span v-if="form.busy" class="sr-only">{{ $t("message.brand.loading") }}</span>
              Submit
            </CButton>
          </CCol>
          <CCol col="4" sm="4" md="3" class="mb-3 mb-xl-0">
            <CButton block color="dark" @click="$bvModal.hide('brandModal')">Close</CButton>
          </CCol>
        </CRow>
      </b-form>
    </b-modal>
    <!-- End Modal -->
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {required} from "vuelidate/lib/validators";
import {validationMixin} from "vuelidate";
import {ADVERTISEMENT_LIST} from "@/core/services/store/module/advertisement";
import {api_base_url} from "@/core/config/app";

export default {
  mixins: [validationMixin],
  name: "Advertisement",
  data() {
    return {
      validation: true,
      form: new Form({
        add_no: '',
        banner: '',
        url: '',
      })
    }
  },
  validations: {
    form: {
      banner: {
        required
      }
    }
  },
  created(){
    this.$store.dispatch(ADVERTISEMENT_LIST)
  },
  computed:{
    ...mapGetters([
      "advertisementList","getAdvertisementByNo"
    ]),
  },
  methods: {
    openModal(data) {
      this.validation = true;
      this.$v.$reset();
      this.form.reset();
      this.form.clear();
      this.form.add_no = data
      this.$bvModal.show('brandModal');
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    onInputChange(e) {
      const files = e.target.files[0];
      if (!files.type.match('image.*')) {
        $('#fileErrorText').text(files.name + this.$t('message.brand.not_image'));
        this.validation = false;
        return;
      }
      if (files['size'] > 2111775) {
        $('#fileErrorText').text(this.$t('message.brand.uploading_large_file'));
        this.validation = false;
        return;
      }
      this.validation = true;
      let reader = new FileReader();
      reader.onload = (e) => {
        this.form.banner = reader.result
      }
      reader.readAsDataURL(files);
    },
    createBrand() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.post('advertisement')
          .then(({data}) => {
            this.form.reset();
            this.$bvModal.hide('brandModal');
            this.$store.dispatch(ADVERTISEMENT_LIST)
            toast.fire({
              icon: 'success',
              title: 'Advertisement add successfully'
            });
          })
    },
    showImage(e) {
      return api_base_url + e;
    },
    deleteBrand(id) {
      let that = this;
      swal.fire({
        title: this.$t('message.brand.are_you_sure'),
        text: this.$t('message.brand.able_to_revert'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('message.brand.delete_it')
      }).then((result) => {
        if (result.value) {
          that.form.delete('advertisement/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire("Failed!", data.data.message, 'warning')
                } else {
                  this.$store.dispatch(ADVERTISEMENT_LIST)
                  swal.fire(
                      this.$t('message.common.deleted'),
                      'Advertisement has been remove',
                      this.$t('message.common.succes')
                  )
                }

              })
              .catch(() => {
                swal.fire(this.$t('message.common.error'),this.$t('message.common.something_wrong'), 'warning')
              });
        }
      })
    },
  }
}
</script>

<style scoped>

</style>