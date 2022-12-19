<template>
  <div
    class="product-list-wrapper"
    style="min-height: 80vh; display: flex; align-items: center"
  >
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif">
      <div v-if="isCheckout" id="checkout" class="row">
        <MembershipSignUp/>
      </div>
      <div v-else class="row">
        <div
          v-for="(data, k) in membershipPlans.data"
          :key="k"
          class="col-md-4 col-sm-6 plan-card"
        >
          <div
            :class="['pricingTable', { blue: k == 1 }, { green: k == 2 }]"
            class="w-100"
          >
            <div class="pricingTable-header">
              <h3 class="title">{{ data.name }}</h3>
              <h5 class="font-weight-bold">{{ data.amount }} BDT</h5>
              <div class="price-value">
                <!--                <span class="amount">$10.99</span>-->
                <span class="duration"
                  >{{ data.duration }}
                  {{ $t("message.membership_plan.days") }}</span
                >
              </div>
            </div>
            <ul class="pricing-content w-50">
              <li>
                <b>{{ data.no_of_allowed_products }}</b>
                {{ $t("message.membership_plan.allowed_products") }}
              </li>
              <li>
                <b>{{ data.no_of_allowed_keywords }}</b>
                {{ $t("message.membership_plan.allowed_keywords") }}
              </li>
            </ul>
            <div class="w-75 mx-auto" v-html="data.benefit"></div>
            <div class="pricingTable-signup">
              <a href="javascript:void(0)" @click="handleSignup(data)">{{
                $t("message.membership_plan.sign_up")
              }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/core/services/api.service";
import MembershipSignUp from './MembershipSignUp.vue'
export default {
  name: "About",
  components:{MembershipSignUp},
  data() {
    return {
      loadActive: false,
      isCheckout: false,
      membershipPlans: {},
    };
  },
  created() {
    this.loadData();
  },
  methods: {
    loadData(page = 1) {
      this.loadActive = true;
      this.membershipPlans = {};
      ApiService.get(`membership-plan?page=${page}`)
        .then(({ data }) => {
          this.membershipPlans = data;
          this.loadActive = false;
        })
        .catch(() => {
          this.loadActive = false;
        });
    },
    handleSignup(plan) {
      this.choosePlan = plan;
      this.isCheckout = true;
    },
    handlePayment() {
      ApiService.post("user/register-membership-plan/" + this.choosePlan.id)
        .then((data) => {
          if (data.data.result === "Error") {
            swal.fire("Failed!", data.data.message, "warning");
          } else {
            swal
              .fire({
                title: this.$t("message.common.register_successfully"),
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ok",
              })
              .then((result) => {
                if (result.value) {
                  this.$router.push({ name: "Dashboard" });
                }
              });
          }
        })
        .catch(() => {
          swal.fire(
            this.$t("message.common.error"),
            this.$t("message.common.something_wrong"),
            "warning"
          );
        });
    },
  },
};
</script>

<style scoped>
.plan-card {
  margin-bottom: 20px;
}

.pricingTable {
  font-family: "Ubuntu", sans-serif;
  text-align: center;
  position: relative;
  z-index: 1;
}

.pricingTable:before,
.pricingTable:after {
  content: "";
  background: linear-gradient(to right, #fe6d94, #f7ba81);
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
  background: linear-gradient(to right, #fe6d94, #f7ba81);
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
  background: linear-gradient(-135deg, #fe6d94, #f7ba81);
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
  background: linear-gradient(to right, #896bef, #60cee7);
}

.pricingTable.blue .pricingTable-signup a {
  background: linear-gradient(-135deg, #896bef, #60cee7);
}

.pricingTable.green:before,
.pricingTable.green .title {
  background: linear-gradient(to right, #30cebf, #47aa40);
}

.pricingTable.green .pricingTable-signup a {
  background: linear-gradient(-135deg, #30cebf, #47aa40);
}

@media only screen and (max-width: 990px) {
  .pricingTable {
    margin-bottom: 40px;
  }
}
</style>
