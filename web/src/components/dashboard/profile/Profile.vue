<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="tab" role="tabpanel">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" :class="profileType==='personal'?'active':''">
                  <a href="javascript:void(0)" aria-controls="personal" role="tab" data-toggle="tab" @click.prevent="profileType = 'personal'">{{ $t("message.profile.personal_info") }}</a>
                </li>
                <li role="presentation" :class="profileType==='company'?'active':''">
                  <a href="javascript:void(0)" aria-controls="company" role="tab" data-toggle="tab" @click.prevent="profileType = 'company'">{{ $t("message.profile.company_basic") }}</a>
                </li>
                <li v-if=" companyBasicInfo" role="presentation" :class="profileType==='company-more'?'active':''">
                  <a href="javascript:void(0)" aria-controls="company-more" role="tab" data-toggle="tab" @click.prevent="profileType = 'company-more'">{{ $t("message.profile.company_more") }}</a>
                </li>
                <li v-if="companyBasicInfo" role="presentation" :class="profileType==='certification'?'active':''">
                  <a href="javascript:void(0)" aria-controls="certification" role="tab" data-toggle="tab" @click.prevent="profileType = 'certification'">{{ $t("message.profile.certification") }}</a>
                </li>
                <li v-if="companyBasicInfo" role="presentation" :class="profileType==='factory-details'?'active':''">
                  <a href="javascript:void(0)" aria-controls="factory-details" role="tab" data-toggle="tab" @click.prevent="profileType = 'factory-details'">{{ $t("message.profile.factory_details") }}</a>
                </li>
                <li v-if="companyBasicInfo" role="presentation" :class="profileType==='trade-details'?'active':''">
                  <a href="javascript:void(0)" aria-controls="trade-details" role="tab" data-toggle="tab" @click.prevent="profileType = 'trade-details'">{{ $t("message.profile.trade_port") }}</a>
                </li>
<!--                <li role="presentation">
                  <a href="javascript:void(0)" aria-controls="nearest-por" role="tab" data-toggle="tab" :class="profileType==='nearest-port'?'active':''" @click.prevent="profileType = 'nearest-port'">{{ $t("message.profile.nearest_port") }}</a>
                </li>-->
              </ul>
              <!-- Tab panes -->
<!-- some change
-->

              <div class="tab-content tabs">
                <Personal v-if="profileType==='personal'"/>
                <Company v-if="profileType==='company'"/>
                <Info v-if="profileType==='company-more'"/>
                <Certification v-if="profileType==='certification'"/>
                <Factory v-if="profileType==='factory-details'"/>
                <Trade v-if="profileType==='trade-details'"/>
                <Port v-if="profileType==='nearest-port'"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Personal from './personal/Personal'
import {COUNTRY_LIST} from "@/core/services/store/module/country";
import {DIVISION_LIST} from "@/core/services/store/module/division";
import {CITY_LIST} from "@/core/services/store/module/city";
import {AREA_LIST} from "@/core/services/store/module/area";
import {PERSONAL_LIST} from "@/core/services/store/module/personal";
import Company from "@/components/dashboard/profile/company/Company";
import {COMPANY_BASIC_LIST} from "@/core/services/store/module/companybasic";
import Info from "@/components/dashboard/profile/company/More/Info";
import Certification from "./certification/Certification";
import Factory from "./factory/Factory";
import Trade from "./trade/Trade";
import Port from "./nearestPort/Port";

export default {
  name: "Profile",
  data() {
    return {
      profileType: 'personal'
    }
  },
  components: {Company, Personal, Info,Certification,Factory,Trade,Port},
  created() {
    this.$store.dispatch(PERSONAL_LIST)
    this.$store.dispatch(COUNTRY_LIST)
    this.$store.dispatch(DIVISION_LIST)
    this.$store.dispatch(CITY_LIST)
    this.$store.dispatch(AREA_LIST)
    this.$store.dispatch(COMPANY_BASIC_LIST)
  },
  computed:{
    user(){
      return this.$store.getters.user;
    },
    companyBasicInfo(){
      return this.$store.getters.companyBasicInfo;
    }
  }
}
</script>
<style scoped>
  a:hover,
  a:focus{
    text-decoration: none;
    outline: none;
  }
  .tab{ font-family: 'Barlow', sans-serif; }
  .tab .nav-tabs{
    background: linear-gradient(#e9e9e9,#fff);
    padding: 8px;
    margin: 0 0 8px;
    border: none;
    border-radius: 20px 20px 0 0;
  }
  .tab .nav-tabs li a{
    color: #555;
    background: #fff;
    font-weight: 600;
    text-transform: capitalize;
    padding: 6px 20px 8px;
    margin: 0 5px 0 0;
    border: none;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease 0.3s;
  }
  .tab .nav-tabs li.active a,
  .tab .nav-tabs li a:hover,
  .tab .nav-tabs li.active a:hover{
    color: #fff;
    background: gray;
    border: none;
  }
  .tab .nav-tabs li a:before{
    content: "";
    background-color: gray;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    opacity: 0;
    transform: perspective(350px) rotateY(-70deg) scale(0.5);
    position: absolute;
    top: 0;
    left: 50px;
    z-index: -1;
    transition: left 0.3s ease 0s, opacity 0.3s ease 0s,transform 0.3s ease 0.2s;
  }
  .tab .nav-tabs li.active a:before,
  .tab .nav-tabs li a:hover:before{
    opacity: 1;
    transform: perspective(350px) rotateY(0) scale(1);
    left: 0;
  }
  .tab .tab-content{
    color: #555;
    background: linear-gradient(#fff,#e9e9e9);
    font-size: 16px;
    letter-spacing: 1px;
    line-height: 25px;
    padding: 20px;
    border-radius: 0 0 20px 20px;
    position: relative;
  }
  @media only screen and (max-width: 479px){
    .tab .nav-tabs li{
      width: 100%;
      text-align: center;
      margin: 0 0 10px;
    }
    .tab .nav-tabs li:last-child{ margin-bottom: 0; }
    .tab .nav-tabs li a{ margin: 0; }
  }
</style>
