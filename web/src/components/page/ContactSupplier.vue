<template>
  <div class="container">
    <div class="contact-supplier">
      <router-link to="/">
        <b-img-lazy :src="showImage($store.getters.generalList.logo)" width="200px" height="70px" class="logo"
                    alt="Logo">
        </b-img-lazy>
      </router-link>
      <h2 class="supplier">Contact supplier</h2>
    </div>
    <form>
      <div class="main-warp">
        <div class="company-info">
          <div class="col-sm-12">
            <i class="far fa-user"></i>
            <span v-if="product.user" style="margin-left: 10px; line-height: 1">{{ product.user.full_name }}</span>
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
              <img class="product-img" :src="showImage(product.thumbnail_img)"/>
              <router-link to="" style="margin-left: 10px">
                {{ product.name }}
              </router-link>
            </div>
          </div>
          <div class="col-sm-2">
            <b-form-input v-model="form.product_qty" size="sm" type="number"></b-form-input>
          </div>
          <div class="col-sm-4">
            <v-select v-model="form.product_unit" :options="Object.values(unitList)" label="name"
                      placeholder="Unit" :reduce="name => name.id"></v-select>
          </div>
        </div>
        <!-- <div class="product-attributes" style="display: block" v-if="1 == 1">
          <div class="col-sm-12" style="margin-bottom: 10px">
            Product attributes
          </div>
          <div
            class="col-sm-12"
            style="color: #aaa; font-size: 12px; margin-bottom: 10px"
          >
            You can fill in the following product attributes to get a quotation
            from the seller within 24 hours
          </div>
          <div class="product-type">
            <div class="col-sm-6">
              <label>Product Type</label><br />
              <select
                class="form-control"
                style="
                  border: 1px solid #1686cc;
                  border-radius: 4px;
                  border-color: #c8d2e0;
                  color: #ccc;
                  height: 40px;
                "
              >
                <option value="" disabled selected>Please select</option>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Theme</label><br />
              <select
                class="form-control"
                style="
                  border: 1px solid #1686cc;
                  border-radius: 4px;
                  border-color: #c8d2e0;
                  color: #ccc;
                  height: 40px;
                "
              >
                <option value="" disabled selected>Please select</option>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
              </select>
            </div>
          </div>
          <div class="product-des" style="margin: 10px 0px">
            <div class="col-sm-4">
              <label>Length</label><br />
              <select
                class="form-control"
                style="
                  border: 1px solid #1686cc;
                  border-radius: 4px;
                  border-color: #c8d2e0;
                  color: #ccc;
                  height: 40px;
                "
              >
                <option value="" disabled selected>Please select</option>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
              </select>
            </div>
            <div class="col-sm-4">
              <label>Connector Type</label><br />
              <select
                class="form-control"
                style="
                  border: 1px solid #1686cc;
                  border-radius: 4px;
                  border-color: #c8d2e0;
                  color: #ccc;
                  height: 40px;
                "
              >
                <option value="" disabled selected>Please select</option>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
              </select>
            </div>
            <div class="col-sm-4">
              <label>Certification</label><br />
              <select
                class="form-control"
                style="
                  border: 1px solid #1686cc;
                  border-radius: 4px;
                  border-color: #c8d2e0;
                  color: #ccc;
                  height: 40px;
                "
              >
                <option value="" disabled selected>Please select</option>
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
              </select>
            </div>
          </div>
        </div> -->
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
                    <span style="margin-left: 10px" @click="$refs.browsePhoto.click()">Add attachment</span>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col text-right" style="text-align: right; padding: 12px 20px">
          <button type="button" class="btn btn-primary btn-lg submit" @click="submit">
            Send inquiry now
          </button>
        </div>
      </div>
    </form>
    <div class="footer" style="margin: 20px 0px">
      <div data-role="ifm-forward"></div>
      <p class="control-item">
        <input type="checkbox" name="toRfq" id="respond-in-oneday"/>
        <label for="respond-in-oneday" style="margin-left: 10px">
          <span>
            Recommend matching suppliers if this supplier doesn't contact me on
            Message Center within 24 hours.
          </span>
          <span>
            <i class="msg-icon icon-alisource"></i><strong>RFQ</strong>
          </span>
        </label>
      </p>
      <p class="control-item privacy-card">
        <input type="checkbox" name="agree " id="agree"/>
        <label style="margin-left: 10px; font-size: 16px; font-weight: 700">
          Agree to share <span class="stress">Business Card</span> with supplier.
        </label>
      </p>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import {UNIT_LIST} from "@/core/services/store/module/unit";
import {PRODUCT} from "@/core/services/store/module/product";
import ApiService from "../../core/services/api.service";

export default {
  name: "ContactSupplier",
  data() {
    return {
      form: new Form({
        product_id: this.$route.params.id,
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
    this.$store.dispatch(PRODUCT, this.$route.params.id);
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    submit() {
      if (this.user.id === this.product.user_id) {
        swal.fire("Failed!", this.$t("message.message.can_not_send_message_to_myself"), 'warning')
        return
      }
      this.form.post('user/contact-supplier/' + this.product.id)
          .then((data) => {
            if (data.result === 'Error') {
              swal.fire("Failed!", data.message, 'warning')
            } else {
              swal.fire({
                title: this.$t("message.common.successfully"),
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              }).then((result) => {
                if (result.value) {
                  this.$router.push('/')
                }
              })
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
    ...mapGetters(["unitList", "product"]),
    user() {
      return this.$store.getters.currentUser;
    }
  },
  watch: {
    product(val) {
      this.form.product_unit = val.unit_id
    }
  },
};
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
