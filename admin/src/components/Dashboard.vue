<template>
  <div class="content">
    <h4>Products</h4>
    <hr>
    <div class="row">
      <!--      Total product-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'seller-product'}">
          <div class="card">
            <div class="card-body">
              <div class="stat-widget-five">
                <div class="stat-icon dib flat-color-3">
                  <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                </div>
                <div class="stat-content">
                  <div class="text-left dib">
                    <div class="stat-text"><span class="count">{{dashboard.total_product}}</span></div>
                    <div class="stat-heading">{{ $t("message.dashboard.total_product")}}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </router-link>

      </div>
      <!--        Approved product-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'seller-product'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fa fa-cart-plus" style="color: green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.approved_product}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.approved_product")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--      Pending product-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'seller-product'}">
          <div class="card">
            <div class="card-body">
              <div class="stat-widget-five">
                <div class="stat-icon dib flat-color-3">
                  <i class="fa fa-shopping-basket color-red" aria-hidden="true"></i>
                </div>
                <div class="stat-content">
                  <div class="text-left dib">
                    <div class="stat-text"><span class="count">{{dashboard.pending_product}}</span></div>
                    <div class="stat-heading">{{ $t("message.dashboard.pending_product")}}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </router-link>
      </div>
      <!--  product groups -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Products Group'}">
          <div class="card">
            <div class="card-body">
              <div class="stat-widget-five">
                <div class="stat-icon dib flat-color-3">
                  <i class="far fa-ball-pile"></i>
                </div>
                <div class="stat-content">
                  <div class="text-left dib">
                    <div class="stat-text"><span class="count">{{dashboard.product_groups}}</span></div>
                    <div class="stat-heading">{{ $t("message.dashboard.product_groups")}}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </router-link>
      </div>
      <!--      Brands-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Brand'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-band-aid" style="color: #8a3ab9"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.brands}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.brands")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--      categories-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Category'}">
          <div class="card">
            <div class="card-body">
              <div class="stat-widget-five">
                <div class="stat-icon dib flat-color-3">
                  <i class="fal fa-abacus" style="color: #00c292"></i>
                </div>
                <div class="stat-content">
                  <div class="text-left dib">
                    <div class="stat-text"><span class="count">{{dashboard.categories}}</span></div>
                    <div class="stat-heading">{{ $t("message.dashboard.categories")}}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </router-link>
      </div>
      <!--   sub categories-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Subcategory'}">
          <div class="card">
            <div class="card-body">
              <div class="stat-widget-five">
                <div class="stat-icon dib flat-color-3">
                  <i class="fal fa-abacus" style="color: #0E63B0"></i>
                </div>
                <div class="stat-content">
                  <div class="text-left dib">
                    <div class="stat-text"><span class="count">{{dashboard.sub_categories}}</span></div>
                    <div class="stat-heading">{{ $t("message.dashboard.sub_categories")}}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </router-link>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-12">
        <div class="card mb-1">
          <div class="card-body">
            <div class="card-title">Members</div>
            <vue-chart type="bar" :data="pieData"></vue-chart>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12">
        <div class="card mb-1">
          <div class="card-body" style="background-color: transparent">
            <div class="card-title">Products</div>
            <vue-chart type="bar" :data="pieData2"></vue-chart>
          </div>
        </div>
      </div>

    </div>
    <h4>Members</h4>
    <hr>
    <div class="row">
      <!--   suppliers-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'all-seller'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="far fa-users-crown" style="color: #73AD21"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.suppliers}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.suppliers")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--  pending suppliers-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'all-seller'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-user-clock" style="color: #f1c40f"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.pending_suppliers}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_suppliers")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>

      <!--      Ban supplier-->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'all-seller'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-user-times" style="color: #dc3545"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.ban_users}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.ban_users")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--      premium users -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-user-tie"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.premium_users}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.premium_users")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--      guest users -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-user-secret" style="color: #F7BA81"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.guest_users}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.guest_users")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--      Customers -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'all-customer'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-users" style="color: #3b5998"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.customers}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.customers")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--     Pending Customers -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'all-customer'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-user-clock" style="color: #f1c40f"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.pending_customers}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_customers")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>

      <!--      Membership plan -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'package-payment'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-3">
                <i class="fas fa-user-shield" style="color: #49a342"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.membership_plans}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.membership_plans")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
    </div>
    <h4>RFQ</h4>
    <hr>
    <div class="row">
      <!--      Total RFQ -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Quotation List'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:green" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.total_rfq}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.total_rfq")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--      Pending RFQ -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Quotation List'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:yellow" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.pending_rfq}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.pending_rfq")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--      Cancel RFQ -->
      <div class="col-lg-3 col-md-6">
        <router-link :to="{name:'Quotation List'}">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-4">
                <i class="fa fa-quote-right" style="color:red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.canceled_rfq}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.canceled_rfq")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </router-link>
      </div>
      <!--  graph    -->
      <div class="col-lg-12 col-md-12" >
        <div class="card mb-4" style="max-height: 300px">
          <div class="card-body">
            <div class="card-title">Last 30 days RFQ</div>
            <vue-chart :type="'line'" :data="chartData" :height="70"></vue-chart>
          </div>
        </div>
      </div>
    </div>
    <h4>Orders</h4>
    <hr>
    <div class="row">
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
                  <div class="stat-text"><span class="count">{{dashboard.total_order}}</span></div>
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
                  <div class="stat-text"><span class="count">{{dashboard.pending_order}}</span></div>
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
                  <div class="stat-text"><span class="count">{{dashboard.canceled_order}}</span></div>
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
                  <div class="stat-text"><span class="count">{{dashboard.return_order}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.return_order")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
      <h4>Others</h4>
    <hr>
    <div class="row">
      <!--reviewed    -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fal fa-comments-alt" style="color: #0E63B0"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.reviews}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.reviews")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  favorite   -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fa fa-heart" style="color: red" aria-hidden="true"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.favorites}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.favorites")}}</div>
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
                <i class="fas fa-chart-line" style="color: blue"></i>
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
                <i class="fab fa-sellsy"></i>
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
                <i class="fas fa-box-check" style="color: #1a80ff"></i>
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
                <i class="fas fa-box-check" style="color: #49a342"></i>
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
                <i class="fas fa-check-circle" style="color: #F7BA81"></i>
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
      <!-- subscribers     -->
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="stat-widget-five">
              <div class="stat-icon dib flat-color-1">
                <i class="fas fa-chalkboard-teacher" style="color: #1a80ff"></i>
              </div>
              <div class="stat-content">
                <div class="text-left dib">
                  <div class="stat-text"><span class="count">{{dashboard.subscribers}}</span></div>
                  <div class="stat-heading">{{ $t("message.dashboard.subscribers")}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-12">
        <div class="card mb-1">
          <div class="card-body">
            <div class="card-title">Locations</div>
            <vue-chart type="pie" :data="pieData3" :options="option"></vue-chart>
          </div>
        </div>
      </div>
      <!--  graph    -->
      <div class="col-lg-6 col-md-12" >
        <div class="card mb-4">
          <div class="card-body">
            <div class="card-title">Last 30 days new member registration</div>
            <vue-chart :type="'line'" :data="chartData2" :height="150"></vue-chart>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {DASHBOARD_INFORMATION} from "../core/services/store/module/dashboard";
import Vue from 'vue';
import VueChart from 'vue-chart-js';
Vue.use(VueChart);
export default {
  name: 'Dashboard',
  data(){
    return{
      option:{
        legend:{
          position:'bottom'
        }
      },
      chartData: {
        labels: [],
        datasets: [
          {
            label: 'Reports',
            backgroundColor: 'blue',
            data: []
          },
        ],
      },
      chartData2: {//reg member graph
        labels: [],
        datasets: [
          {
            label: 'Registration',
            backgroundColor: 'green',
            data: []
          },
        ],
      },
      pieData: {
        labels: [],
        datasets: [],
      },
      pieData2: {
        labels:[],
        datasets:[],
      },
      pieData3: {//for location
        labels:[],
        datasets:[],
      },
    }
  },
  methods:{
    pi1Data(){
      this.pieData.labels= ['Supplier','Buyer','Both','Free Member','Baned Member'];
      this.pieData.datasets= [{
        label:'Members',
        backgroundColor: ['green', 'blue', 'magenta','red'],
        data: [this.dashboard.suppliers,this.dashboard.customers,this.dashboard.both,this.dashboard.guest_users,this.dashboard.ban_users],
      },];
    },
    pi2Data(){
      this.pieData2.labels= ['Total','Approved','Pending'];
      this.pieData2.datasets= [{
        label:'Products',
        backgroundColor: ['blue','green', 'red', 'red', 'green', 'red'],
        data: [this.dashboard.total_product,this.dashboard.approved_product,this.dashboard.pending_product],
      },];
    },
    rfqGraph(){
      if(!this.dashboard.rfq) return;
      this.chartData.labels=Object.keys(this.dashboard.rfq).map((field) =>field);
      this.chartData.datasets=[
        {
          label: 'Request',
          drawBorder:true,
          backgroundColor: '#A5D8DD',
          borderColor:['yellow', 'green', 'red', 'blue', 'yellow'],
          data: Object.keys(this.dashboard.rfq).map((field) =>this.dashboard.rfq[field])
        },
      ];
    },
    regGraph(){//reg graph
      if(!this.dashboard.registration) return;
      this.chartData2.labels=Object.keys(this.dashboard.registration).map((field) =>field);
      this.chartData2.datasets=[
        {
          label: 'Registration',
          drawBorder:true,
          backgroundColor: '#A5D8DD',
          borderColor:['yellow', 'green', 'red', 'blue', 'yellow'],
          data: Object.keys(this.dashboard.registration).map((field) =>this.dashboard.registration[field])
        },
      ];
    },
    locationPie(){
      this.pieData3.labels= ['Country','Division','City','Area'];
      this.pieData3.datasets= [{
        backgroundColor: ['yellow','green', 'blue', 'gray', 'green', 'red'],
        data: [this.dashboard.country,this.dashboard.division,this.dashboard.city,this.dashboard.area],
      },];
    },
  },
  created() {
    this.$store.dispatch(DASHBOARD_INFORMATION);
    this.pi2Data();
    this.pi1Data();
    this.rfqGraph();
    this.locationPie();
    this.regGraph();
  },
  computed:{
    dashboard(){
      return this.$store.getters.dashboard;
    }
  },
  watch:{
    dashboard(){
      this.pi2Data();
      this.pi1Data();
      this.rfqGraph();
      this.locationPie();
      this.regGraph();
    }
  }
}
</script>
<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>
<style scoped>
@import 'https://pro.fontawesome.com/releases/v5.10.0/css/all.css';
</style>
