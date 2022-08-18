<template>
  <div class="card">
    <div class="card-header">
      {{ mode ? 'Company basic information' : 'Edit Company basic information' }}
      <a href="#" v-if="mode" @click.prevent="mode = !mode" class="float-right"><i
          class="fas fa-edit " style="color: #03a9f3"></i>
        {{ $t("message.company.edit") }}</a>
    </div>
    <div v-if="mode && companyBasicInfo" class="card-body">
      <div class="container">
        <b-row class="p-2 border-bottom">
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.company_name") }} {{ companyBasicInfo.name }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.display_name") }} {{ companyBasicInfo.display_name }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.establishment_date") }} {{ companyBasicInfo.establishment_date }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.office_space") }} {{ companyBasicInfo.office_space }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.business_type") }} <span v-for="(type,key) in companyBasicInfo.business_types" :key="key">{{type.name}}, </span>
          </b-col>
        </b-row>
        <b-row class="p-2">
          <b-col cols="12">
            <h6>{{ $t("message.company.register_address") }}</h6>
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.country") }} {{ addressFilter(getCountryNameById(companyBasicInfo.country), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.division") }} {{ addressFilter(getDivisionNameById(companyBasicInfo.division), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.city") }} {{ addressFilter(getCityNameById(companyBasicInfo.city), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.area") }} {{ addressFilter(getAreaNameById(companyBasicInfo.area), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.address") }} {{ companyBasicInfo.address }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.zip_code") }} {{ companyBasicInfo.zip_code }}
          </b-col>
        </b-row>
        <b-row v-if="companyBasicInfo.address_type == 2" class="p-2 border-top">
          <b-col cols="12">
            <h6>{{ $t("message.company.operation_address") }}</h6>
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.country") }} {{ addressFilter(getCountryNameById(companyBasicInfo.country2), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.division") }} {{ addressFilter(getDivisionNameById(companyBasicInfo.division2), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.city") }} {{ addressFilter(getCityNameById(companyBasicInfo.city2), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.area") }} {{ addressFilter(getAreaNameById(companyBasicInfo.area2), 'name') }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.address") }} {{ companyBasicInfo.address2 }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.zip_code") }} {{ companyBasicInfo.zip_code2 }}
          </b-col>
        </b-row>
        <b-row class="p-2 border-top">
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.website") }} {{ companyBasicInfo.website }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.email") }} {{ companyBasicInfo.email }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.phone") }} {{ companyBasicInfo.phone }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.cell") }} {{ companyBasicInfo.cell }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.fax") }} {{ companyBasicInfo.fax }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.number_of_employee") }} {{ companyBasicInfo.number_of_employee }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.ownership_type") }} {{ companyBasicInfo.ownership_type }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.revenue") }} {{ companyBasicInfo.revenue }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.main_product") }} {{ companyBasicInfo.main_product }}
          </b-col>
          <b-col cols="12" md="6" class="my-2">
            {{ $t("message.company.other_product") }} {{ companyBasicInfo.other_product }}
          </b-col>
        </b-row>
      </div>
    </div>
    <CompanyEdit v-if="!mode" @view="mode = !mode"/>
  </div>
</template>

<script>
import CompanyEdit from "@/components/dashboard/profile/company/CompanyEdit";
import {mapGetters} from "vuex";

export default {
  name: "Company",
  data() {
    return {
      mode: true,
      editText: false,
    }
  },
  methods: {
    addressFilter(data, key) {
      return data ? data[key] : '';
    }
  },
  components: {
    CompanyEdit
  },
  computed: {
    ...mapGetters(["getDivisionNameById", "getCountryNameById", "getCityNameById", "getAreaNameById"]),
    companyBasicInfo() {
      return this.$store.getters.companyBasicInfo.company;
    },
  },
}
</script>

<style scoped>

</style>