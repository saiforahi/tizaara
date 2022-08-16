<template>
  <div>
    <!-- Page Header -->
    <div class="row">
      <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
        <span class="text-uppercase page-subtitle"> {{$t("message.home.frontend_settings")}}
        </span>
        <h3 class="page-title">{{$t("message.home.home_settings")}}</h3>
      </div>
    </div>
    <!-- End Page Header -->

    <CRow class="justify-content-center my-4">
      <CCol sm="12">
        <b-card no-body>
          <b-tabs pills card>
            <b-tab :title="$t('message.home.listing')" active>
              <Listing/>
            </b-tab>
            <b-tab :title="$t('message.home.home_slider')">
              <b-card-text>
                <Slider/>
              </b-card-text>
            </b-tab>
            <b-tab :title="$t('message.home.terms_conditions')">
              <b-card-text>
                <Terms/>
              </b-card-text>
            </b-tab>
            <b-tab :title="$t('message.home.privacy_policy')">
              <b-card-text>
                <Privacy/>
              </b-card-text>
            </b-tab>
            <b-tab :title="$t('message.home.about_us')">
              <b-card-text>
                <About/>
              </b-card-text>
            </b-tab>
            <b-tab :title="$t('message.home.join_sales')">
              <b-card-text>
                <Sales/>
              </b-card-text>
            </b-tab>
            <b-tab :title="$t('message.home.help')">
              <b-card-text>
                <Help/>
              </b-card-text>
            </b-tab>
          </b-tabs>
        </b-card>
      </CCol>
    </CRow>
  </div>
</template>

<script>
import Listing from "@/components/frontend/Home/Listing";
import Slider from "@/components/frontend/Home/Slider";
import Terms from "@/components/frontend/Home/Terms";
import Privacy from "@/components/frontend/Home/Privacy";
import About from "@/components/frontend/Home/About";
import Sales from "@/components/frontend/Home/Sales";
import Help from "@/components/frontend/Home/Help";
import {HOMEBANNER_LIST} from "@/core/services/store/module/homeslider";
import {HOME_CATEGORY_PRODUCT} from "@/core/services/store/module/category";

export default {
  name: "Home",
  components: {Listing, Slider, Terms, Privacy, About, Sales, Help},
  created() {
    this.$store.dispatch(HOMEBANNER_LIST)
    this.$store.dispatch(HOME_CATEGORY_PRODUCT)
  },
}
</script>

<style scoped>

</style>