<template>
  <b-tabs pills card vertical active-nav-item-class="bg-success">
    <b-tab title="Brand" active>
      <b-form @submit.prevent="updateBrand()" @keydown="brand.onKeydown($event)">
        <v-select v-model="brand.brand_id" :options="Object.values(brandList)" label="name"
                  placeholder="Select product category"
                  :reduce="name => name.id" multiple>
        </v-select>
        <div class="my-4">
          <draggable class="list-group" v-model="brand.brand_id" tag="ul" v-bind="dragOptions"
                     @start="isDragging = true" @end="isDragging = false">
            <transition-group type="transition" name="flip-list">
              <li class="list-group-item"
                  v-for="element in brand.brand_id"
                  :key="element">
                {{ getBrandById(element).name }}
              </li>
            </transition-group>
          </draggable>
        </div>
        <div class="form-group form-actions mt-3">
          <CButton :disabled="brand.busy" type="submit" color="success" class="float-right">
            <span v-if="brand.busy" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
             {{ $t("message.listing.update")}}
          </CButton>
        </div>
      </b-form>
    </b-tab>
    <b-tab title="Category">
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
            {{ $t("message.listing.update")}}
          </CButton>
        </div>
      </b-form>
    </b-tab>
    <b-tab title="Sub-category">

      <b-form @submit.prevent="updateSubcategory()" @keydown="subcategory.onKeydown($event)">
        <v-select v-model="subcategory.category_id" :options="Object.values(categoryList)" label="name"
                  placeholder="Select product category" @input="categorySelect"
                  :reduce="name => name.id">
          <template #option="{ name, icon }">
            <img v-lazy="showImage(icon)" class="mx-2" width="18px" height="18px" alt="Category">
            <em>{{ name }}</em>
          </template>
        </v-select>
        <v-select v-model="subcategory.subcategory_id" :options="subcategoryList" label="name"
                  placeholder="Select product Subcategory" class="my-3"
                  :reduce="name => name.id" multiple>
        </v-select>
        <div class="my-4">
          <draggable class="list-group" v-model="subcategory.subcategory_id" tag="ul" v-bind="dragOptions"
                     @start="isDragging = true" @end="isDragging = false">
            <transition-group type="transition" name="flip-list">
              <li class="list-group-item"
                  v-for="element in subcategory.subcategory_id"
                  :key="element">
                {{ getSubcategoryNameById(element).name }}
              </li>
            </transition-group>
          </draggable>
        </div>
        <div class="form-group form-actions mt-3">
          <CButton :disabled="subcategory.busy" type="submit" color="primary" class="float-right">
            <span v-if="subcategory.busy" class="spinner-border spinner-border-sm" role="status"
                  aria-hidden="true"></span>
            {{ $t("message.listing.update")}}
          </CButton>
        </div>
      </b-form>

    </b-tab>
    <b-tab title="Sub-Subcategory">


      <b-form @submit.prevent="updateSubsubcategory()" @keydown="subsubcategory.onKeydown($event)">
        <v-select v-model="subsubcategory.category_id" :options="Object.values(categoryList)" label="name"
                  placeholder="Select product category" @input="categorySelect2"
                  :reduce="name => name.id">
          <template #option="{ name, icon }">
            <img v-lazy="showImage(icon)" class="mx-2" width="18px" height="18px" alt="Category">
            <em>{{ name }}</em>
          </template>
        </v-select>

        <v-select v-model="subsubcategory.subcategory_id" :options="subcategoryList2" label="name"
                  placeholder="Select product Subcategory" class="mt-3" @input="subcategorySelect"
                  :reduce="name => name.id">
        </v-select>

        <v-select v-model="subsubcategory.subsubcategory_id" :options="subsubcategoryList" label="name"
                  placeholder="Select product Sub-Subcategory" class="my-3"
                  :reduce="name => name.id" multiple>
        </v-select>
        <div class="my-4">
          <draggable class="list-group" v-model="subsubcategory.subsubcategory_id" tag="ul" v-bind="dragOptions"
                     @start="isDragging = true" @end="isDragging = false">
            <transition-group type="transition" name="flip-list">
              <li class="list-group-item"
                  v-for="element in subsubcategory.subsubcategory_id"
                  :key="element">
                {{ getSubsubcategoryNameById(element).name }}
              </li>
            </transition-group>
          </draggable>
        </div>
        <div class="form-group form-actions mt-3">
          <CButton :disabled="subsubcategory.busy" type="submit" color="primary" class="float-right">
            <span v-if="subsubcategory.busy" class="spinner-border spinner-border-sm" role="status"
                  aria-hidden="true"></span>
            {{ $t("message.listing.update")}}
          </CButton>
        </div>
      </b-form>


    </b-tab>
  </b-tabs>
</template>

<script>
import {mapGetters} from "vuex";
import {CATEGORY_LIST} from "@/core/services/store/module/category";
import {api_base_url} from "@/core/config/app";
import {SUBSUBCATEGORY_LIST} from "@/core/services/store/module/subsubcategory";
import {SUBCATEGORY_LIST} from "@/core/services/store/module/subcategory";
import {BRAND_LIST} from "@/core/services/store/module/brand";
import draggable from 'vuedraggable'

export default {
  name: "Listing",
  data() {
    return {
      brand: new Form({
        brand_id: [],
      }),
      category: new Form({
        category_id: [],
      }),
      subcategoryList: [],
      subcategory: new Form({
        subcategory_id: [],
        category_id: '',
      }),
      subcategoryList2: [],
      subsubcategoryList: [],
      subsubcategory: new Form({
        subsubcategory_id: [],
        subcategory_id: '',
        category_id: '',
      }),
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    updateBrand() {
      this.brand.post('brand-listing')
          .then(() => {
            toast.fire({
              icon: 'success',
              title: this.$t("message.listing.brand_listing_successfully")
            });
          })
    },
    updateCategory() {
      this.category.post('category-listing')
          .then(() => {
            toast.fire({
              icon: 'success',
              title: this.$t("message.listing.category_cisting_successfully")
            });
          })
    },
    categorySelect(e) {
      this.subcategory.subcategory_id = this.getSubListingId(e);
      this.subcategoryList = this.getSubcategoryById(e);
    },
    categorySelect2(e) {
      this.subsubcategory.subcategory_id = '';
      this.subcategoryList2 = this.getSubcategoryById(e);
    },
    subcategorySelect(e) {
      this.subsubcategory.subsubcategory_id = this.getSubSubListingId(e);
      this.subsubcategoryList = this.getSubsubcategoryById(e);
    },
    updateSubcategory() {
      this.subcategory.post('subcategory-listing')
          .then(() => {
            this.$store.dispatch(SUBCATEGORY_LIST)
            toast.fire({
              icon: 'success',
              title: this.$t("message.listing.subcategory_listing_successfully")
            });
          })
    },
    updateSubsubcategory() {
      this.subsubcategory.post('sub-subcategory-listing')
          .then(() => {
            this.$store.dispatch(SUBSUBCATEGORY_LIST)
            toast.fire({
              icon: 'success',
              title: this.$t("message.listing.sub_subcategory_listing_successfully")
            });
          })
    },
  },
  created() {
    this.$store.dispatch(BRAND_LIST)
    this.$store.dispatch(CATEGORY_LIST)
    this.$store.dispatch(SUBSUBCATEGORY_LIST)
    this.$store.dispatch(SUBCATEGORY_LIST)
  },
  computed: {
    ...mapGetters(["brandList", "categoryList", "getBrandListingId", "getCatListingId", "getSubcategoryById", "getSubListingId", "getSubsubcategoryById", "getSubSubListingId",
      "getCategoryById", "getSubcategoryNameById", "getSubsubcategoryNameById", "getBrandById"]),
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
    getBrandListingId: function (val) {
      this.brand.brand_id = val;
    },
    getCatListingId: function (val) {
      this.category.category_id = val;
    }
  },
  components: {
    draggable,
  },
}
</script>

<style>
.flip-list-move {
  transition: transform 0.5s;
}

.no-move {
  transition: transform 0s;
}

.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}

.list-group {
  min-height: 20px;
}

.list-group-item {
  cursor: move;
}

.list-group-item i {
  cursor: pointer;
}
</style>