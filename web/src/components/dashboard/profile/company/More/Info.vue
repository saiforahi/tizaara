<template>
  <div class="card">
    <div class="card-header">
      {{ mode ? $t("message.company_info.company_details") : $t("message.company_info.edit_your_company_details") }}
      <a href="#" v-if="mode" @click.prevent="mode = !mode" class="float-right">
        <i class="fas fa-edit" style="color: #03a9f3"></i>
        {{ $t("message.company_info.edit") }}</a>
    </div>
    <div v-if="mode && companyBasicInfo && companyDetails" class="card-body">
      <div class="container">
        <b-row class="p-2">
          <b-col cols="12">
            <div>
              <b-img center rounded v-lazy="showImage(companyDetails.company_logo)" alt="Center image"
                     width="100px"></b-img>
            </div>
            <h6 class="text-center">{{ companyBasicInfo.name }}</h6>
            <p class="text-center">{{
                addressFilter(getDivisionNameById(companyBasicInfo.division), 'name') + ', ' + addressFilter(getCityNameById(companyBasicInfo.city), 'name')
              }}</p>
          </b-col>
          <b-col cols="12">
            <h6>{{ $t("message.company_info.about_us") }}</h6>
            <hr>
            <p>{{ companyDetails.about_us }}</p>
          </b-col>
          <b-col cols="12">
            <h6>{{ $t("message.company_info.mission") }}</h6>
            <hr>
            <p>{{ companyDetails.mission }}</p>
          </b-col>
          <b-col cols="12">
            <h6>{{ $t("message.company_info.vision") }}</h6>
            <hr>
            <p>{{ companyDetails.vision }}</p>
            <hr>
          </b-col>
        </b-row>
      </div>
      <b-container class="bg-dark">
        <b-row>
          <b-col cols="3" class="my-2" v-for="(photo, k) in company_photos" :key="k">
            <b-img thumbnail fluid v-lazy="photo" alt="Image 1"></b-img>
          </b-col>
        </b-row>
      </b-container>
    </div>
    <Edit v-if="!mode" @view="mode = !mode"/>
  </div>
</template>

<script>
import Edit from "@/components/dashboard/profile/company/More/Edit";
import {api_base_url} from "@/core/config/app";
import {mapGetters} from "vuex";

export default {
  name: "Info",
  data() {
    return {
      mode: true,
      company_photos: '',
    }
  },
  components: {
    Edit
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    addressFilter(data, key) {
      return data ? data[key] : '';
    },
    loadInfo(data) {
      if (!$.isEmptyObject(data)) {
        if (data.company_photos !== '') {
          this.company_photos = [];
          let photos = JSON.parse(data.company_photos);
          for (let i = 0; i < photos.length; i++) {
            this.company_photos.push(this.showImage(photos[i]));
          }
        }
      }
    }
  },
  computed: {
    ...mapGetters(["getDivisionNameById", "getCountryNameById", "getCityNameById", "getAreaNameById"]),
    companyBasicInfo() {
      return this.$store.getters.companyBasicInfo.company;
    },
    companyDetails() {
      return this.$store.getters.companyBasicInfo.company_details;
    },
  },
  watch: {
    'companyDetails': {
      handler(value) {
        this.loadInfo(value);
      },
      immediate: true
    },
  }
}
</script>

<style scoped>

</style>