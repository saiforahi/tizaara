<template>
  <div>
    <alert-error :form="form" message="Fill-up all input data."></alert-error>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{ $t("message.product_create.product")}}
        </span>
        <h3 class="page-title">{{ $t("message.product_create.property_create")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <router-link to="/product/properties" class="btn btn-primary" color="info">
            <font-awesome-icon icon="hand-point-left"/>
            {{ $t("message.product_create.back")}}
          </router-link>
        </div>
      </div>
    </div>
    <!-- End Page Header -->


    <b-card>
      <b-form @submit.prevent="editMode ? updateProperties(): createProperties()" @keydown="form.onKeydown($event)">
        <div class="card" style="background-color: #dae2ed;">
          <div class="card-body" style="padding: 12px">
            <c-row>
              <c-col>
                <b-form-select v-model="form.category_id" :options="Object.values(categoryList)" value-field="id"
                               text-field="name" @input="categorySelect" :select-size="18"
                               class="cat-select-design"></b-form-select>
              </c-col>
              <c-col>
                <b-form-select v-if="subcategory.length > 0" v-model="form.sub_category_id" :options="subcategory"
                               :select-size="18" value-field="id" @input="subcategorySelect"
                               text-field="name" class="cat-select-design"></b-form-select>
              </c-col>
              <c-col>
                <b-form-select v-if="subsubcategory.length > 0" v-model="form.sub_subcategory_id"
                               :options="subsubcategory" @input="subsubcategorySelect"
                               :select-size="18" value-field="id"
                               text-field="name" class="cat-select-design"></b-form-select>
              </c-col>
            </c-row>
          </div>
          <div class="card-footer" style="font-size: 13px">
            {{ $t("message.product_create.categories_selected")}}
            {{ catNameShow(form.category_id, 'category') }} {{ catNameShow(form.sub_category_id, 'subcategory') }}
            {{ catNameShow(form.sub_subcategory_id, 'sub-subcategory') }}
            <font-awesome-icon v-if="cat_valid"
                               class="text-success" style="font-size: 15px" icon="check-circle"/>
          </div>
        </div>
        <c-row>
          <c-col md="6">
            <span>{{ $t("message.product_create.property_label")}}</span>
            <p class="text-right text-info font-weight-bold my-0" style="font-size: 12px;cursor: pointer"
               @click="addMoreLabel">
              {{ $t("message.product_create.add_more_label")}}</p>
            <table class="table table-bordered">
              <tr v-for="(volume, k) in form.property_label" :key="k">
                <td>
                  <b-form-input placeholder="Enter property label name"
                                type="text" :disabled="!cat_valid"
                                v-model="volume.name" required
                  ></b-form-input>
                </td>
                <td>
                  <CButton color="secondary" @click="removeMoreLabel(k, volume)">
                    <font-awesome-icon icon="trash-alt"/>
                  </CButton>
                </td>
              </tr>
            </table>
            <div class="form-group form-actions">
              <CButton :disabled="form.busy || !cat_valid" type="submit" color="primary" class="float-right">
                <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{ $t("message.product_create.submit")}}
              </CButton>
            </div>
          </c-col>
        </c-row>
      </b-form>
    </b-card>


  </div>
</template>

<script>
import {SUBSUBCATEGORY_LIST} from "@/core/services/store/module/subsubcategory";
import {SUBCATEGORY_LIST} from "@/core/services/store/module/subcategory";
import {CATEGORY_LIST} from "@/core/services/store/module/category";
import {mapGetters} from "vuex";

export default {
  name: "PropertyCreate",
  data() {
    return {
      cat_valid: false,
      editMode: false,
      form: new Form({
        id: '',
        name: '',
        category_id: '',
        sub_category_id: '',
        sub_subcategory_id: '',
        property_label: [{
          name: ''
        }],
      }),
      subcategory: [],
      subsubcategory: [],

    }
  },
  methods: {
    categorySelect: function (e) {
      if (e !== '' && e !== undefined) {
        this.form.sub_category_id = '';
        this.form.sub_subcategory_id = '';
        this.subcategory = [];
        this.subsubcategory = [];
        this.subcategory = this.getSubcategoryById(e);
        this.cat_valid = !this.subcategory.length > 0
      }
    },
    subcategorySelect(e) {
      if (e !== '' && e !== undefined) {
        this.form.sub_subcategory_id = '';
        this.subsubcategory = [];
        this.subsubcategory = this.getSubsubcategoryById(e);
        this.cat_valid = !this.subsubcategory.length > 0
      }
    },
    subsubcategorySelect(e) {
      if (e !== '' && e !== undefined) {
        this.cat_valid = e !== ''
      }
    },
    catNameShow(id, type) {
      if (type === 'category' && id !== '') {
        let data = this.getCategoryById(id)
        return data ? data.name : '';
      }

      if (type === 'subcategory' && id !== '') {
        let data = this.getSubcategoryNameById(id)
        return data ? '>>' + data.name : '';
      }

      if (type === 'sub-subcategory' && id !== '') {
        let data = this.getSubsubcategoryNameById(id)
        return data ? '>>' + data.name : '';
      }
    },
    addMoreLabel() {
      this.form.property_label.push({
        name: ''
      })
    },
    removeMoreLabel(index, label) {
      let idx = this.form.property_label.indexOf(label);
      if (idx > -1) {
        this.form.property_label.splice(idx, 1);
      }
    },
    createProperties() {
      this.form.post('property')
          .then((e) => {
            swal.fire({
              title: this.$t("message.common.success"),
              text: this.$t("message.product_create.property_create_successfully"), 
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Ok'
            }).then((result) => {
              this.$router.push({name: "Properties"});
            })
          })
    },
  },
  created() {
    this.$store.dispatch(SUBSUBCATEGORY_LIST)
    this.$store.dispatch(SUBCATEGORY_LIST)
    this.$store.dispatch(CATEGORY_LIST)
  },
  computed: {
    ...mapGetters(["categoryList", "getSubcategoryById", "getCategoryById", "getSubcategoryNameById", "getSubsubcategoryById", "getSubsubcategoryNameById"])
  },
}
</script>

<style scoped>

</style>