<template>
  <div>
    <div class="category-menu-section">
      <div class="container">
        <div class="row">
          <div
            class="col-md-12 col-lg-3 col-xl-3 col-sm-12 col-12"
            id="category"
          >
            <div class="categorie-menu-wrapper">
              <ul>
                <li>
                  <div class="menu-title">
                    <h6 class="mb-0">
                      {{ $t("message.category_menu.categories") }}
                    </h6>
                    <router-link to="/categories">{{
                      $t("message.category_menu.view_all")
                    }}</router-link>
                  </div>
                </li>
                <li
                  v-for="categories in categoryList.slice(0, 13)"
                  :key="categories.id"
                >
                  <router-link :to="'category/' + categories.slug">
                    <img
                      v-lazy="showImage(categories.icon)"
                      v-if="categories.icon"
                      class="cat-image"
                      :class="
                        getSubcategoryById(categories.id).length > 0
                          ? 'dropdown-toggle'
                          : ''
                      "
                    />
                    {{ categories.name }}
                  </router-link>
                  <ul
                    v-if="getSubcategoryById(categories.id).length > 0"
                    class="cat-overflow"
                    style="font-size: 15px"
                  >
                    <li>
                      <div class="card-columns">
                        <div
                          class="card shadow-none border-0"
                          v-for="subcategories in getSubcategoryById(
                            categories.id
                          ).slice(0, 6)"
                          :key="subcategories.id"
                        >
                          <h6
                            class="submenu-title cus-mt p-0 pb-2 ml-3"
                            style="
                              border-bottom: 1px solid #efe9e9;
                              font-size: 14px;
                            "
                          >
                            <router-link
                              :to="
                                'category/' +
                                categories.slug +
                                '/' +
                                subcategories.slug
                              "
                              :key="subcategories.id"
                              >{{ subcategories.name }}
                            </router-link>
                          </h6>
                          <router-link
                            :to="
                              'category/' +
                              categories.slug +
                              '/' +
                              subcategories.slug +
                              '/' +
                              subsubcategories.slug
                            "
                            v-for="subsubcategories in getSubsubcategoryById(
                              subcategories.id
                            ).slice(0, 5)"
                            :key="subsubcategories.id"
                            class="py-0 my-0 sub-menu-last"
                            >{{ subsubcategories.name }}
                          </router-link>
                        </div>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <!-- .col-3 end -->

          <div class="col-md-12 col-lg-9 col-xl-9 col-sm-12 col-12">
            <div class="main-menu">
              <ul class="nav">
                <li class="nav-item">
                  <router-link :to="{ name: 'popular.item' }" class="nav-link">
                    {{ $t("message.category_menu.popular_items") }}
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link
                    :to="{ name: 'verified.suppliers' }"
                    class="nav-link"
                  >
                    {{ $t("message.category_menu.verified_supplier") }}
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link :to="{ name: 'top.suppliers' }" class="nav-link">
                    {{ $t("message.category_menu.top_supplier") }}
                  </router-link>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" v-b-modal.modal-1>{{
                    $t("message.category_menu.submit_RFQ")
                  }}</a>
                </li>
                <li class="nav-item" v-if="!isAuthenticated">
                  <a class="nav-link" @click.prevent="openReg" href="join"
                    >{{ $t("message.category_menu.sell_tizaara") }}
                  </a>
                </li>
              </ul>
            </div>
            <!-- .main-menu end -->
            <div class="deal-of-the-day">
              <div class="banner-image">
                <div>
                  <b-carousel
                    id="carousel-fade"
                    fade
                    img-width="100%"
                    img-height="100%"
                  >
                    <b-carousel-slide
                      v-for="(photo, k) in home_banner"
                      :key="k"
                      :img-src="photo"
                    ></b-carousel-slide>
                  </b-carousel>
                </div>

                <!--              <div class="banner-fixed-feature" style="z-index: 1">
                                <div class="banner-fixed-feature-item">
                                  <i class="fa fa-sm fa-tools"></i>
                                  <div class="item-type">
                                    <h6>{{ $t("message.category_menu.lorem_ipsum_dolor") }}</h6>
                                    <p>{{ $t("message.category_menu.lorem_ipsum_dolor_sit_amet") }}</p>
                                  </div>
                                </div>
                                <div class="banner-fixed-feature-item">
                                  <i class="fa fa-sm fa-tools"></i>
                                  <div class="item-type">
                                    <h6>{{ $t("message.category_menu.lorem_ipsum_dolor") }}</h6>
                                    <p>{{ $t("message.category_menu.lorem_ipsum_dolor_sit_amet") }}</p>
                                  </div>
                                </div>
                                <div class="banner-fixed-feature-item">
                                  <i class="fa fa-sm fa-tools"></i>
                                  <div class="item-type">
                                    <h6>{{ $t("message.category_menu.lorem_ipsum_dolor") }}</h6>
                                    <p>{{ $t("message.category_menu.lorem_ipsum_dolor_sit_amet") }}</p>
                                  </div>
                                </div>
                              </div>
                              -->
              </div>
              <!-- .banner-images end -->
            </div>
            <!-- .deal-of-the-day end -->
          </div>
          <!-- .col-9 end -->
        </div>
        <!-- .row end -->
      </div>
      <!-- .container end -->
    </div>
    <b-modal id="modal-1" size="lg" centered hide-footer body-class="p-0">
      <template #modal-title>
        <center>RFQ Request</center>
      </template>
      <b-container fluid>
        <b-row>
          <b-col
            style="background-color: #0e63b0"
            class="rounded py-3 text-white"
          >
            <img
              src="@/assets/image/rfq_img.png"
              class="img-fluid bg-light"
              alt="RFQ Image"
            />
            <h4 class="text-center mt-2">How it Works</h4>
            <ul class="timeline">
              <li>
                <p>Tell us what you need by filling the form</p>
              </li>
              <li>
                <p>Receive Verified supplier details</p>
              </li>
              <li>
                <p>Compare Quotations and seal the deal</p>
              </li>
            </ul>
          </b-col>
          <b-col cols="8" class="py-3">
            <h3 class="mb-4">{{ $t("message.quotations.multiple_quotes") }}</h3>
            <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
              <div class="form-group mb-3">
                <b-form-input
                  type="text"
                  class="form-control"
                  id="product"
                  v-model="$v.form.product.$model"
                  :placeholder="
                    $t('message.quotations.enter_product_service_name')
                  "
                  :state="validateState('product')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.product.required">
                  {{ $t("message.quotations.leave_empty") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.product.maxLength">
                  {{ $t("message.quotations.maximum_255_character") }}
                </b-form-invalid-feedback>
              </div>
              <div class="form-row mb-2">
                <div class="form-group col-md-6">
                  <b-form-input
                    type="number"
                    class="form-control"
                    placeholder="Quantity"
                    v-model="$v.form.quantity.$model"
                    id="quantity"
                    :state="validateState('quantity')"
                  ></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.form.quantity.maxLength">
                    {{ $t("message.quotations.maximum_10_character") }}
                  </b-form-invalid-feedback>
                </div>
                <div class="form-group col-md-6">
                  <b-form-select
                    v-model="form.unit_id"
                    :options="Object.values(unitList)"
                    :state="validateState('unit_id')"
                    size="sm"
                    text-field="name"
                    value-field="id"
                    id="country"
                  >
                    <template v-slot:first>
                      <b-form-select-option value="" disabled
                        >{{ $t("message.quotations.select_units") }}
                      </b-form-select-option>
                    </template>
                  </b-form-select>
                </div>
              </div>
              <div class="form-group mb-3">
                <b-form-textarea
                  id="textarea"
                  v-model="form.details"
                  placeholder="Briefly describe what you looking to buy..."
                  rows="3"
                  :state="validateState('details')"
                  max-rows="6"
                ></b-form-textarea>
                <b-form-invalid-feedback v-if="!$v.form.details.maxLength">
                  {{ $t("message.quotations.maximum_255_character") }}
                </b-form-invalid-feedback>
              </div>
              <button type="submit" class="btn btn-main">
                {{ $t("message.quotations.request_for_quotations") }}
              </button>
            </form>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<script>
import { api_base_url } from "@/core/config/app";
import { mapGetters } from "vuex";
import { maxLength, required } from "vuelidate/lib/validators";
import { validationMixin } from "vuelidate";
import { UNIT_LIST } from "@/core/services/store/module/unit";

export default {
  name: "category_menu",
  mixins: [validationMixin],
  data() {
    return {
      home_banner: [],
      form: new Form({
        product: "",
        details: "",
        quantity: null,
        unit_id: "",
      }),
    };
  },
  validations: {
    form: {
      product: {
        required,
        maxLength: maxLength(191),
      },
      details: {
        maxLength: maxLength(500),
      },
      quantity: {
        required,
        maxLength: maxLength(10),
      },
      unit_id: {
        required,
      },
    },
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    openReg() {
      Fire.$emit("registrationModal");
    },
    loadSlider(data) {
      if (!$.isEmptyObject(data)) {
        if (data.home_banner !== "") {
          this.home_banner = [];
          let photos = JSON.parse(data.home_banner);
          for (let i = 0; i < photos.length; i++) {
            this.home_banner.push(this.showImage(photos[i]));
          }
        }
      }
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      if (!this.isAuthenticated) {
        this.$bvModal.hide("modal-1");
        $("#reg").modal("hide");
        $(`#login`).modal("show");
      } else {
        this.form
          .post("quotation")
          .then(() => {
            this.$v.$reset();
            this.form.reset();
            toast.fire({
              icon: "success",
              title: this.$t("message.quotations.quotation_send_successfully"),
            });
          })
          .catch(() => {
            swal.fire(
              this.$t("message.common.error"),
              this.$t("message.common.something_wrong"),
              "warning"
            );
          })
          .finally(() => this.$bvModal.hide("modal-1"));
      }
    },
  },
  computed: {
    ...mapGetters([
      "getHomeBanner",
      "getSubcategoryById",
      "categoryList",
      "getSubsubcategoryById",
      "isAuthenticated",
      "unitList",
    ]),
  },
  created() {
    this.loadSlider(this.getHomeBanner);
    this.$store.dispatch(UNIT_LIST);
  },
  watch: {
    getHomeBanner(data) {
      this.loadSlider(data);
    },
  },
};
</script>

<style scoped>
.cat-image {
  width: 16px;
  opacity: 0.6;
  margin-right: 10px;
}

.cat-overflow {
  overflow: auto;
}

.cat-overflow::-webkit-scrollbar {
  width: 4px;
  background-color: #f5f5f5;
}

.cat-overflow::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(234, 217, 217, 0.3);
  background-color: #555;
}

ul.timeline {
  list-style-type: none;
  position: relative;
}

ul.timeline:before {
  content: " ";
  background: #d4d9df;
  display: inline-block;
  position: absolute;
  left: 20px;
  width: 2px;
  height: 100%;
  z-index: 400;
}

ul.timeline > li {
  margin: 10px 0;
  padding-left: 5px;
}

ul.timeline > li:before {
  content: " ";
  background: white;
  display: inline-block;
  position: absolute;
  border-radius: 50%;
  border: 3px solid #22c0e8;
  left: 11px;
  width: 20px;
  height: 20px;
  z-index: 400;
}
</style>
