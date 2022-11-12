<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="row m-0">
        <h3>{{ $t("message.cart.cart") }}</h3>
        <div v-if="carts?.length>0">
          <!--Section: Block Content-->
          <section>
            <!--Grid row-->
            <div class="row">
              <!--Grid column-->
              <div class="col-lg-9">
                <!-- Card -->
                <div class="mb-3">
                  <div class="pt-4 wish-list">
                    <h5 class="mb-4">Cart (<span>{{ carts.length }}</span> items)</h5>
                    <div class="row mb-4" v-for="(cart,key) in carts" :key="key">
                      <div class="col-md-4 col-lg-3 col-xl-3">
                        <div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0">
                          <img class="img-fluid w-100"
                               :src="imgShow(cart.product.thumbnail_img)" alt="">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-xl-9">
                        <div class="row">
                          <div class="col-md-12 d-flex justify-content-between">
                            <div>
                              <h5>{{ cart.product.name }}</h5>
                              <p class="mb-3 text-muted text-uppercase small">{{ cart.variant }}</p>
                            </div>
                            <div>
                              <div class="qty-box col-md-6 col-sm-5">
                                <div class="input-group">
                                  <span class="input-group-prepend">
                                    <button type="button"
                                            @click="quantityChange(0,key,cart.product,cart.available_product_qty)"
                                            data-type="minus" class="btn quantity-left-minus">
                                      <i class="fa fa-minus-circle btn-outline-danger" aria-hidden="true"></i>
                                    </button>
                                  </span>
                                  <input type="number" @change="qtyChange(key,cart.product,cart.available_product_qty)"
                                         name="quantity" v-model.number="quantity=cart.quantity" min="1"
                                         class="form-control input-number">
                                  <span class="input-group-prepend">
                                    <button type="button"
                                            @click="quantityChange(1,key,cart.product,cart.available_product_qty)"
                                            data-type="plus" class="btn quantity-right-plus">
                                      <i class="fa fa-plus-circle btn-outline-danger" aria-hidden="true"></i>
                                    </button>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                              <a href="javascript:void(0)" @click="removeProduct(key)" type="button"
                                 class="card-link-secondary small text-uppercase mr-3"><i
                                  class="fas fa-trash-alt mr-1" style="color: red"></i> Remove item </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr class="mb-4">
                  </div>
                </div>
                <!-- Card -->
                <!-- Card -->
              </div>
              <!--Grid column-->

              <!--Grid column-->
              <div class="col-lg-3">

                <!-- Card -->
                <div class="mb-3">
                  <div class="pt-4">

                    <h5 class="mb-3">{{ $t('message.cart.the_total_amount_of') }}</h5>

                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                        {{ $t('message.cart.sub_total') }}
                        <span>{{ getSubTotal }}</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        {{ $t('message.cart.shipping_charge') }}
                        <span>{{ totalShippingCost }}</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        {{ $t('message.cart.vat') }}
                        <span>{{ totalTax }}</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                        <div>
                          <strong>{{ $t('message.cart.total_amount') }}</strong>
                        </div>
                        <span><strong>
                          {{ getSubTotal + totalTax + totalShippingCost }}
                        </strong></span>
                      </li>
                    </ul>
                    <button type="button" class="btn btn-primary btn-block">
                      <router-link :to="{name:'cart.checkout'}">go to checkout</router-link>
                    </button>
                  </div>
                </div>
                <!-- Card -->

              </div>
              <!--Grid column-->
            </div>
            <!-- Grid row -->
          </section>
          <!--Section: Block Content-->
        </div>
      </div>
    </div>
  </div>
</template>

<script>


import {mapGetters} from "vuex";
import {REMOVE_FORM_CART, UPDATE_CART} from "../../core/services/store/module/cart";

export default {
  name: "Cart",
  data() {
    return {
      quantity: 1,
    }
  },
  created() {
    //this.$store.dispatch(ABOUT_US)
  },
  computed: {
    ...mapGetters(["carts", "getTotal", "getSubTotal", "totalShippingCost", "totalTax"]),
  },
  methods: {
    imgShow(path) {
      return process.env.VUE_APP_API_BASE_URL + path;
    },
    /*
    * remove product from cart
    * */
    removeProduct(index) {
      this.$store.commit(REMOVE_FORM_CART, index);
    },
    /*
    * method for quantity change
    * type =0 minus
    * type =1 plus
    * */
    quantityChange(type, index, product, available_product_qty) {
      /*
      * if order quantity limit is enable
      * */
      if (product.orderQtyLimit && (this.quantity <= product.orderQtyLimitMin || this.quantity >= product.orderQtyLimitMax)) {
        if (type == 0 && (this.quantity - 1) < product.orderQtyLimitMin) {
          this.quantity = parseInt(product.orderQtyLimitMin);
          return;
        } else if (type == 1 && (this.quantity + 1) > product.orderQtyLimitMax) {
          this.quantity = parseInt(product.orderQtyLimitMax);
          return;
        }
      }
      if (type == 0 && this.quantity > 1) {
        this.quantity -= 1;
      } else if (type == 1) {
        if (product.stockManagement == 1 && (product.priceType == 0 || product.priceType == 2)) {
          if (this.product.quantity > this.quantity) this.quantity += 1;
        } else if (product.stockManagement == 1 && product.priceType == 1) {
          if (available_product_qty > this.quantity) this.quantity += 1;
        } else this.quantity += 1;
      }
      this.$store.commit(UPDATE_CART, [index, this.quantity]);
    },
    /*
    * method for on change product qty check
    * */
    qtyChange(index, product, available_product_qty) {
      /*
      * if order quantity limit is enable
      * */
      if (product.orderQtyLimit && (this.quantity < product.orderQtyLimitMin || this.quantity > product.orderQtyLimitMax)) {
        if (this.quantity < product.orderQtyLimitMin) {
          this.quantity = parseInt(product.orderQtyLimitMin);
          //return;
        } else if (this.quantity > product.orderQtyLimitMax) {
          this.quantity = parseInt(product.orderQtyLimitMax);
          //return;
        }
      }
      if (product.stockManagement == 1 && (product.priceType == 0 || product.priceType == 2)) {
        if (product.quantity < this.quantity) this.quantity = parseInt(product.quantity);
      } else if (product.stockManagement == 1 && product.priceType == 1) {
        if (available_product_qty < this.quantity) this.quantity = parseInt(available_product_qty);
      }
      this.$store.commit(UPDATE_CART, [index, this.quantity]);
    },
  }
}
</script>

<style scoped>

</style>