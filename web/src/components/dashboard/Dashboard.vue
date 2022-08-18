<template>
  <div class="content">
    <template v-if="isBuyer && isSupplier">
      <h4>Supplying Information</h4>
      <hr>
    </template>

    <!-- Widgets  -->
    <div class="row" v-if="isSupplier">
<!--      Total product-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.total_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--        Approved product-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fa fa-cart-plus" style="color: green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.approved_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.approved_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--      Pending product-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fa fa-shopping-basket color-red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.pending_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--      Total quotation-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.total_quotation}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_quotation")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--      pending quotation-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:yellow" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.pending_quotation}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_quotation")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--      canceled quotation-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.canceled_quotation}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.canceled_quotation")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--      average rating-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-star" style="color: green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{parseFloat(dashboard_supplier.average_rating).toFixed(2)}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.average_rating")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  total reviewed product    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-commenting" style="color: blue" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.total_review}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_review")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  total product favorite   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-heart" style="color: red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_supplier.product_favorite}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.product_favorite")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--      Total sale -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-2">
                <i class="fa fa-suitcase" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_sale")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--      Today sale -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-2">
                <i class="pe-7s-cart" style="color: green"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.today_sale")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  Total revenue    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="pe-7s-cash"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_revenue")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  Today revenue    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="pe-7s-cash" style="color:darkmagenta;"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.today_revenue")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- last moth revenue     -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="pe-7s-cash" style="color:orange"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.last_month_revenue")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--total orders    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  pending orders   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-cart-arrow-down" style="color: #f1c40f" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  canceled orders   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-shopping-cart" style="color: #dc3545" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.canceled_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  return orders   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-history" style="color: #0E63B0" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.return_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- last total transaction     -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-suitcase" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_transaction")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <template v-if="isBuyer && isSupplier">
      <h4>Buying Information</h4>
      <hr>
    </template>
    <div class="row" v-if="isBuyer">
      <!--      Total quotation-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_buyer.total_quotation}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_quotation")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--      pending quotation-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:yellow" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_buyer.pending_quotation}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_quotation")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--      canceled quotation-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_buyer.canceled_quotation}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.canceled_quotation")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--total orders    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  pending orders   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-cart-arrow-down" style="color: #f1c40f" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  canceled orders   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-shopping-cart" style="color: #dc3545" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.canceled_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  return orders   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-history" style="color: #0E63B0" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.return_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- last total transaction     -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-suitcase" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">0</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_transaction")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--      total rated product-->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-star" style="color: green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_buyer.total_rated_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_rated_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--  total reviewed product    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-commenting" style="color: blue" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_buyer.total_reviewed_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_reviewed_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--   favorite product  -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-heart" style="color: red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard_buyer.favorite_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.favorite_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <hr>
    <!-- /Widgets -->
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {
  GET_BUYER_DASHBOARD_INFORMATION,
  GET_SUPPLIER_DASHBOARD_INFORMATION
} from "../../core/services/store/module/dashboard";

export default {
  name: "Dashboard",
  created(){
      if (this.isSupplier) this.$store.dispatch(GET_SUPPLIER_DASHBOARD_INFORMATION);
      if (this.isBuyer) this.$store.dispatch(GET_BUYER_DASHBOARD_INFORMATION);
  },
  computed:{
    ...mapGetters(["isBuyer","isSupplier","dashboard_supplier","dashboard_buyer"]),
  }
}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>
