<template>
  <div class="product-list-wrapper" style="min-height: 80vh;display: flex;align-items: center;">
    <div class="container">
      <div v-if="isCheckout" id="checkout" class="row">
        <div class="col-md-12">
          <div class="checkout-panel">
            <div class="panel-body">
              <h2 class="title">Checkout</h2>
              <div class="payment-method">
                <label for="card" class="method card">
                  <div class="card-logos">
                    <img src="/images/visa_logo.png"/>
                    <img src="/images/mastercard_logo.png"/>
                  </div>
                  <div class="radio-input">
                    <input id="card" type="radio" name="payment">
                    Pay with credit card
                  </div>
                </label>
                <label for="paypal" class="method paypal">
                  <img src="/images/paypal_logo.png"/>
                  <div class="radio-input">
                    <input id="paypal" type="radio" name="payment">
                    Pay with PayPal
                  </div>
                </label>
              </div>
              <div class="input-fields">
                <div class="column-1">
                  <label for="cardholder">Cardholder's Name</label>
                  <input type="text" id="cardholder"/>
                  <div class="small-inputs">
                    <div>
                      <label for="date">Valid thru</label>
                      <input type="text" id="date" placeholder="MM / YY"/>
                    </div>
                    <div>
                      <label for="verification">CVV / CVC *</label>
                      <input type="password" id="verification"/>
                    </div>
                  </div>
                </div>
                <div class="column-2">
                  <label for="cardnumber">Card Number</label>
                  <input type="password" id="cardnumber"/>
                  <span class="info">* CVV or CVC is the card security code, unique three digits number on the back of your card separate from its number.</span>
                </div>
              </div>
            </div>
            <div class="panel-footer">
              <button class="btn back-btn" @click="isCheckout = false">Back</button>
              <button class="btn next-btn" @click="handlePayment">Submit</button>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="row">
        <div v-for="(data, k) in membershipPlans.data" :key="k" class="col-md-4 col-sm-6 plan-card">
          <div :class="['pricingTable', {'blue': k == 1}, {'green': k == 2}]" class="w-100">
            <div class="pricingTable-header">
              <h3 class="title">{{ data.name }}</h3>
              <h5 class="font-weight-bold">{{ data.amount }} BDT</h5>
              <div class="price-value">
                <!--                <span class="amount">$10.99</span>-->
                <span class="duration">{{ data.duration }} {{ $t("message.membership_plan.days") }}</span>
              </div>
            </div>
            <ul class="pricing-content w-50">
              <li><b>{{ data.no_of_allowed_products }}</b> {{ $t("message.membership_plan.allowed_products") }}</li>
              <li><b>{{ data.no_of_allowed_keywords }}</b> {{ $t("message.membership_plan.allowed_keywords") }}</li>
            </ul>
            <div class="w-75 mx-auto" v-html="data.benefit"></div>
            <div class="pricingTable-signup">
              <a href="javascript:void(0)" @click="handleSignup(data)">{{ $t("message.membership_plan.sign_up") }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from '@/core/services/api.service'

export default {
  name: "About",
  data() {
    return {
      loadActive: false,
      isCheckout: false,
      membershipPlans: {}
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    loadData(page = 1) {
      this.loadActive = true;
      this.membershipPlans = {};
      ApiService.get(`membership-plan?page=${page}`)
          .then(({data}) => {
            this.membershipPlans = data;
            this.loadActive = false;
          })
          .catch(() => {
            this.loadActive = false;
          });
    },
    handleSignup(plan) {
      this.choosePlan = plan
      this.isCheckout = true
    },
    handlePayment() {
      ApiService.post('user/register-membership-plan/' + this.choosePlan.id)
          .then((data) => {
            if (data.data.result === 'Error') {
              swal.fire("Failed!", data.data.message, 'warning')
            } else {
              swal.fire({
                title: this.$t("message.common.register_successfully"),
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
              }).then((result) => {
                if (result.value) {
                  this.$router.push({name: "Dashboard"})
                }
              })
            }
          })
          .catch(() => {
            swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
          });
    }
  }
}
</script>

<style scoped>
.plan-card {
  margin-bottom: 20px;
}

.pricingTable {
  font-family: 'Ubuntu', sans-serif;
  text-align: center;
  position: relative;
  z-index: 1;
}

.pricingTable:before,
.pricingTable:after {
  content: '';
  background: linear-gradient(to right, #FE6D94, #F7BA81);
  height: calc(100% - 45px);
  width: 80%;
  border-radius: 0 0 200px 200px;
  transform: translateX(-50%);
  position: absolute;
  left: 50%;
  top: 10px;
  z-index: -1;
}

.pricingTable:after {
  background: #fff;
  width: calc(80% - 10px);
  height: calc(100% - 51px);
}

.pricingTable .pricingTable-header {
  color: #222;
  margin: 0 0 35px 0;
}

.pricingTable .title {
  color: #fff;
  background: linear-gradient(to right, #FE6D94, #F7BA81);
  font-size: 33px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  text-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
  padding: 17px 10px;
  margin: 0 0 25px;
  border-radius: 50px;
}

.pricingTable .price-value .amount {
  font-size: 50px;
  font-weight: 700;
  display: block;
}

.pricingTable .price-value .duration {
  font-size: 16px;
  display: block;
}

.pricingTable .pricing-content {
  text-align: left;
  padding: 0;
  margin: 0 0 30px;
  list-style: none;
  display: inline-block;
}

.pricingTable .pricing-content li {
  color: #333;
  font-size: 16px;
  font-weight: 500;
  padding: 0 0 0 28px;
  margin: 0 0 15px;
  position: relative;
}

.pricingTable .pricing-content li:last-child {
  margin: 0;
}

.pricingTable .pricing-content li:before {
  content: "\f00c";
  color: #fff;
  background: #5dbb54;
  font-family: "Font Awesome 5 Free";
  font-size: 12px;
  font-weight: 900;
  text-align: center;
  line-height: 21px;
  width: 20px;
  height: 20px;
  border-radius: 50px;
  position: absolute;
  top: 1px;
  left: 0;
}

.pricingTable .pricing-content li.disable:before {
  content: "\f00d";
  background: #ed4444;
  padding-right: 1px;
}

.pricingTable .pricingTable-signup a {
  color: #fff;
  background: linear-gradient(-135deg, #FE6D94, #F7BA81);
  font-size: 22px;
  font-weight: 600;
  text-transform: capitalize;
  letter-spacing: 1px;
  width: 100px;
  height: 100px;
  padding: 20px 15px;
  margin: 0 auto;
  border-radius: 50px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  display: block;
  transition: all 0.5s;
}

.pricingTable .pricingTable-signup a:hover {
  text-shadow: 5px 5px 0px rgba(0, 0, 0, 0.15);
  box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.15);
}

.pricingTable.blue:before,
.pricingTable.blue .title {
  background: linear-gradient(to right, #896BEF, #60CEE7);
}

.pricingTable.blue .pricingTable-signup a {
  background: linear-gradient(-135deg, #896BEF, #60CEE7);
}

.pricingTable.green:before,
.pricingTable.green .title {
  background: linear-gradient(to right, #30CEBF, #47AA40);
}

.pricingTable.green .pricingTable-signup a {
  background: linear-gradient(-135deg, #30CEBF, #47AA40);
}

@media only screen and (max-width: 990px) {
  .pricingTable {
    margin-bottom: 40px;
  }
}
</style>
