<template>
  <b-tabs pills card vertical active-nav-item-class="bg-success">

    <b-tab title="Home Banner" active>
      <alert-error :form="form" message="Please select sum image first"></alert-error>
      <CForm @submit.prevent="updateSlider()" @keydown="form.onKeydown($event)">
        <CRow class="my-3">
          <CCol md="12">
            <div role="group" class="d-flex justify-content-center">
              <div class="">
                <vue-upload-multiple-image
                    @before-remove="(index, done, fileList) =>{ done(); form.home_banner = fileList}"
                    @upload-success="(formData, index, fileList) =>{ form.home_banner = fileList}"
                    @edit-image="(formData, index, fileList) =>{ form.home_banner = fileList}"
                    :data-images="home_banner" popupText="Product image, you can add only 9 image"
                    idUpload="myIdUpload" editUpload="myIdEdit" idEdit="myIdEdited"
                    dragText="Drag images (many)." browseText="Select multiple image"
                    primaryText="Product Image" accept="image/jpeg,image/png,image/bmp,image/jpg"
                    :maxImage="9" markIsPrimaryText=""
                ></vue-upload-multiple-image>
              </div>
            </div>
          </CCol>
        </CRow>
        <div class="form-group form-actions mt-3">
          <CButton :disabled="form.busy" type="submit" color="success" class="float-right">
            <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            {{ $t("message.privacy.update")}}
          </CButton>
        </div>
      </CForm>
    </b-tab>
    <b-tab title="Home Category Product">
      <b-form @submit.prevent="updateCategory()" @keydown="category.onKeydown($event)">
        <v-select v-model="category.category_id" :options="Object.values(categoryList)" label="name"
                  placeholder="Select product category"
                  :reduce="name => name.id" multiple>
          <template #option="{ name, icon }">
            <img v-lazy="showImage(icon)" class="mx-2" width="18px" height="18px" alt="Category">
            <em>{{ name }}</em>
          </template>
        </v-select>
        <div class="my-4">
          <draggable class="list-group" v-model="category.category_id" tag="ul" v-bind="dragOptions"
                     @start="isDragging = true" @end="isDragging = false">
            <transition-group type="transition" name="flip-list">
              <li class="list-group-item"
                  v-for="element in category.category_id"
                  :key="element">
                {{ getCategoryById(element).name }}
              </li>
            </transition-group>
          </draggable>
        </div>
        <div class="form-group form-actions mt-3">
          <CButton :disabled="category.busy" type="submit" color="primary" class="float-right">
            <span v-if="category.busy" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            {{ $t("message.privacy.update")}}
          </CButton>
        </div>
      </b-form>
    </b-tab>

  </b-tabs>
</template>

<script>
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import {HOMEBANNER_LIST} from "@/core/services/store/module/homeslider";
import draggable from 'vuedraggable'

export default {
  name: "Slider",
  data() {
    return {
      form: new Form({
        home_banner: [],
      }),
      category: new Form({
        category_id: [],
      }),
      home_banner: [],
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadSlider(data) {
      if (data.home_banner !== '') {
        this.home_banner = [];
        this.form.home_banner = [];
        let photos = JSON.parse(data.home_banner);
        for (let i = 0; i < photos.length; i++) {
          this.home_banner.push({
            path: this.showImage(photos[i]),
            default: 1,
            highlight: 1,
            caption: 'caption to display. receive',
          });
          this.form.home_banner.push({
            path: this.showImage(photos[i]),
            default: 1,
            highlight: 1,
            caption: 'caption to display. receive',
          });
        }
      }
    },
    updateSlider() {
      this.form.post('home-banner')
          .then(() => {
            this.$store.dispatch(HOMEBANNER_LIST)
            toast.fire({
              icon: 'success',
              title: this.$t("message.privacy.home_banner_update_successfully")
            });
          })
    },
    updateCategory() {
      this.category.post('home-category-listing')
          .then(() => {
            toast.fire({
              icon: 'success',
              title: this.$t("message.privacy.home_category_product_listing_successfully")
            });
          })
    },
  },
  components: {
    VueUploadMultipleImage, draggable
  },
  computed: {
    ...mapGetters(["getHomeBanner", "categoryList", "getCatHomeListingId", "getCategoryById"]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    }
  },
  watch: {
    getHomeBanner(data) {
      this.loadSlider(data);
    },
    getCatHomeListingId: function (val) {
      this.category.category_id = val;
    }
  }
}
</script>

<style scoped>

</style>