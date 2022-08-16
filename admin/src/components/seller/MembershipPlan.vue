<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle">{{$t("message.membership_plan.seller")}}</span>
        <h3 class="page-title">{{$t("message.membership_plan.seller_membership_plan")}}</h3>
      </div>
      <div class="col-12 col-sm-6 d-flex align-items-center">
        <div class="mx-auto ml-sm-auto mr-sm-0">
          <c-button block color="info" @click="openModal">{{$t("message.membership_plan.add_membership_plan")}}</c-button>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <c-row class="justify-content-around">
      <vue-element-loading :active="loadActive" background-color="white" spinner="bar-fade-scale"/>
      <c-col col="12" sm="12" md="4" class="mb-3" v-for="(data, k) in membershipPlans.data" :key="k">
        <div class="card">
          <div class="card-body text-center">
            <p class="mb-3 h6 fw-600">{{ data.name }}</p>
            <p class="h4">{{ data.amount }} BDT</p>
            <p class="fs-15">{{$t("message.membership_plan.allowed_products")}}
              <b class="text-bold">{{ data.no_of_allowed_products }}</b>
            </p>
            <p class="fs-15">{{$t("message.membership_plan.allowed_keywords")}}
              <b class="text-bold">{{ data.no_of_allowed_keywords }}</b>
            </p>
            <p class="fs-15">{{$t("message.membership_plan.duration")}}
              <b class="text-bold">{{ data.duration }} {{$t("message.membership_plan.days")}}</b>
            </p>
            <p class="fs-15" v-html="data.benefit"></p>
            <div class="mar-top">
              <button @click="openModalEdit(data)" class="btn btn-sm btn-info mx-1">{{$t("message.membership_plan.edit")}}</button>
              <button @click="deleteData(data.id)" class="btn btn-sm btn-danger mx-1">{{$t("message.membership_plan.delete")}}</button>
            </div>
          </div>
        </div>
      </c-col>
    </c-row>
    <pagination :data="membershipPlans" :limit="membershipPlans.per_page" align="right" show-disabled @pagination-change-page="loadData">
      <span slot="prev-nav">{{$t("message.membership_plan.previous")}}</span>
      <span slot="next-nav">{{$t("message.membership_plan.next")}}</span>
    </pagination>

    <!-- Modal -->
    <membership-plan-modal ref="membershipPlanModal" @success="loadData" />
    <!-- End Modal -->
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import ApiService from "@/core/services/api.service";
import MembershipPlanModal from './membership-plan/Modal'

export default {
  name: "MembershipPlan",
  components: {
    MembershipPlanModal
  },
  data() {
    return {
      loadActive: false,
      membershipPlans: {}
    }
  },
  methods: {
    openModal() {
      this.$refs.membershipPlanModal.openModal();
    },
    openModalEdit(data) {
      this.$refs.membershipPlanModal.openModalEdit(data);
    },
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
    deleteData(id) {
      swal.fire({
        title: this.$t("message.membership_plan.are_you_sure"),
        text: this.$t("message.membership_plan.able_to_revert"),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t("message.membership_plan.delete_it")
      }).then((result) => {
        if (result.value) {
          ApiService.delete('membership-plan/' + id)
              .then((data) => {
                if (data.data.result === 'Error') {
                  swal.fire(this.$t("message.common.error"), data.data.message, 'warning')
                } else {
                  this.loadData();
                  swal.fire(
                      this.$t("message.common.deleted"),
                      this.$t("message.membership_plan.seller_membership_plan_deleted"),
                      this.$t("message.common.succes")
                  )
                }

              })
              .catch(() => {
                swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
              });
        }
      })
    },
  },
  created() {
    this.loadData();
    // if (!this.sellerMembershipPlanList.length > 0) this.$store.dispatch('SELLER_MEMBERSHIP_PLAN_LIST');
  },
  computed: {
    // ...mapGetters(["sellerMembershipPlanList"])
  },
}
</script>

<style scoped>

</style>
