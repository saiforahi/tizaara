<template>
  <div class="modal fade" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="contact_modal_title">Contact Supplier</h4>
          <button type="button" style="float: right;" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body container col-sm-12">
            <div class="contact-supplier">
              <form method="post" action="javascript:void(0)" @submit="submit">
                <div class="main-warp">
                  <div class="company-info">
                    <div class="col-sm-12">
                      <i class="far fa-user"></i>
                      <span v-if="contact_product.user" style="margin-left: 10px; line-height: 1">{{ contact_product.user.full_name }}</span>
                      <i class="fas fa-angle-down" style="float: right"></i>
                    </div>
                  </div>
                  <div class="product-items">
                    <div class="col-sm-6">Product information</div>
                    <div class="col-sm-2" style="margin-left: 5px">Quantity</div>
                    <div class="col-sm-4" style="margin-left: 5px">Unit</div>
                  </div>
                  <div class="product-detail">
                    <div class="col-sm-6">
                      <div class="product-info">
                        <img class="product-img" :src="showImage(contact_product.thumbnail_img)"/>
                        <router-link to="" style="margin-left: 10px">
                          {{ contact_product.name }}
                        </router-link>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <b-form-input v-model.number="form.product_qty" size="sm" type="number" required></b-form-input>
                    </div>
                    <div class="col-sm-4">
                      <v-select v-model="form.product_unit" :options="Object.values(unitList)" label="name"
                                placeholder="Unit" :reduce="name => name.id" name="product_unit" required></v-select>
                    </div>
                  </div>
                  <div class="message">
                    <b-form-textarea v-model="form.text" size="sm"
                                     placeholder="Enter product details such as color, size, materials etc. and other specification requirements to receive an accurate quote."
                                     rows="4" required>
                    </b-form-textarea>
                  </div>
                  <div class="add-attachment">
                    <div class="ui-uploader-content">
                      <div class="ui-uploader-info" style="display: none"></div>
                      <div class="ui-uploader-list">
                        <div class="ui-uploader-list ui-uploader-img-list util-clearfix">
                          <li v-for="(item, i) in attachments" :key="i" class="ui2-uploader-file-item util-clearfix ui-uploader-complete">
                            <a class="ui2-file-wrap">
                              <i class="onepage-icon-v3 icon-file-jpeg"></i>
                              <span class="ui2-file-name">{{ item.name }}</span>
                            </a>
                          </li>
                          <div style="display: inline-block; margin: 0px; float: left; position: relative;">
                            <input
                                ref="browsePhoto"
                                type="file"
                                accept="image/*"
                                style="display: none;"
                                @change="handleFileUpload"
                            >
                            <span class="view-detail" style="margin: 0px">
                              <i class="fas fa-plus"></i>
                              <span style="margin-left: 10px;cursor: pointer;" @click="$refs.browsePhoto.click()">Add attachment</span>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="footer" style="margin: 20px 0px">
                    <b-form-checkbox value="1">Recommend matching suppliers if this supplier doesn't contact me on
                      Message Center within 24 hours.</b-form-checkbox>
                    <b-form-checkbox value="2"> Agree to share <span class="stress">Business Card</span> with supplier.</b-form-checkbox>
                  </div>
                  <div class="col text-right" style="text-align: right; padding: 12px 20px">
                    <button type="submit" class="btn btn-primary btn-lg submit">
                      Send Message
                    </button>
                  </div>
                </div>
              </form>

            </div>
          </div>
      </div>
    </div>
  </div>

</template>
<script>
import ApiService from "../../core/services/api.service";
import {mapGetters} from "vuex";
import {UNIT_LIST} from "../../core/services/store/module/unit";
import {api_base_url} from "../../core/config/app";
export default {
  name:'contact-modal',
  data() {
    return {
      form: new Form({
        product_id: null,
        product_qty: 1,
        product_unit: '',
        text: '',
        file: ''
      }),
      attachments: []
    };
  },
  created() {
    this.$store.dispatch(UNIT_LIST)
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    submit() {
      if (this.user.id == this.contact_product.user_id) {
        $('#contact_modal').modal('hide');
        swal.fire("Failed!", this.$t("message.message.can_not_send_message_to_myself"), 'warning')
        return;
      }else if (this.user.account_type ==1){
        $('#contact_modal').modal('hide');
        swal.fire("Failed!", this.$t("message.message.can_not_send_message_supplier"), 'warning')
        return;
      }
      this.form.post('user/contact-supplier/' + this.contact_product.id)
          .then((data) => {
            if (data.result === 'Error') {
              swal.fire("Failed!", data.message, 'warning')
            } else {
              $('#contact_modal').modal('hide');
              swal.fire({
                title: this.$t("message.common.successfully"),
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              });
            }
          })
    },
    handleFileUpload() {
      const file = this.$refs.browsePhoto.files[0]
      this.attachments = this.$refs.browsePhoto.files
      const formData = new FormData()
      formData.append('file', file)
      ApiService.post('user/message/upload-attachment', formData, {headers: {'Content-Type': 'multipart/form-data'}})
          .then(({data}) => {
            this.form.file = data.url
          });
    }
  },
  computed: {
    ...mapGetters(["unitList", "contact_product"]),
    user() {
      return this.$store.getters.currentUser;
    }
  },
  watch: {
    contact_product(val) {
      this.form.product_id = val.id;
      this.form.product_unit = val.unit?val.unit.id:null;
    }
  },
}
</script>
<style scoped>
.ui-footer-seo {
  text-align: center;
  padding: 10px;
}

.view-detail:hover {
  color: #ff751a;
  text-decoration: underline;
}

.view-detail {
  color: #1686cc;
  text-decoration: none;
}

.submit {
  background: #ff7519;
  color: #fff;
  border-color: #ff7519;
}

.message {
  width: 100%;
  box-sizing: border-box;
  min-height: 120px;
  padding: 20px;
  resize: none;
  max-width: 100%;
  display: grid;
}

.product-detail,
.product-type,
.product-des {
  display: flex;
}

.product-img {
  width: 48px;
  height: 48px;
  float: left;
  margin-right: 12px;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

.contact-supplier {
  margin: 20px 0;
  display: flex;
}

.supplier {
  font-size: 14px;
  margin-left: 10px;
}

.company-info,
.product-detail,
.product-attributes {
  padding: 12px 20px;
  display: flex;
  border-bottom: 1px solid #c8d2e0;
}

.add-attachment {
  border-bottom: none;
  padding: 12px 20px;
}

.send-item {
  border-bottom: none;
  padding: 12px 20px;
  display: flex;
}

.main-warp {
  border: 1px solid #c8d2e0;
  border-radius: 3px;
}

.ui-footer-policy,
.ui-footer-copyright,
.ui-footer-seo-brand {
  color: #666;
}

.product-items,
.product-attributes {
  color: #666;
  display: flex;
  font-size: 12px;
  padding: 12px 20px 10px 20px;
}
</style>