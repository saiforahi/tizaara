<template>
  <div class="card-body">
    <div class="container">
      <b-form @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="row border-bottom">
          <div class="col-6 my-3">
            <b-form-group id="input-group-1" label-for="name">
              <label v-html="'Company Name ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-building"></i>
                </b-input-group-prepend>
              <b-form-input
                  id="name"
                  v-model="$v.form.name.$model" :state="validateState('name')"
                  :placeholder= "$t('message.company_edit.enter_your_company_name')"
              ></b-form-input>
              <b-form-invalid-feedback v-if="!$v.form.name.required">
                {{ $t("message.company_edits.company_name_required") }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback v-if="!$v.form.name.maxLength">
                {{ $t("message.company_edits.company_name_150_character") }}
              </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-6 my-3">
            <b-form-group id="input-group-2"
                          label-for="display_name">
              <label v-html="'your unique business display name ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-envelope-open-text"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="display_name" @keyup="nameCheck"
                    v-model="$v.form.display_name.$model" :state="validateState('display_name') && !nameStatus"
                    :placeholder= "$t('message.company_edit.enter_your_display_name')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="nameStatus">
                  {{ message }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.display_name.maxLength">
                  {{ $t("message.company_edits.company_name_150_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-6 my-3">
            <div class="form-group">
              <label>{{ $t("message.company_edits.year_of_establishment") }} </label>
              <VueDatePicker  v-model="form.establishment_date" :placeholder= "$t('message.company_edit.choose_your_date_of_birth')" no-header
                             :visible-years-number="50" class="form-control"
                             max-date="2019-31-31"/>
            </div>
          </div>
          <div class="col-12 col-md-6 my-3">
            <b-form-group id="input-group-4" label="Office Space :" label-for="office_space">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-briefcase"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="office_space"
                    v-model="$v.form.office_space.$model" :state="validateState('office_space')"
                    :placeholder= "$t('message.company_edit.enter_your_office_space')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.office_space.maxLength">
                  {{ $t("message.company_edits.office_255_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-12 my-3">
            <b-form-group id="input-group-4" v-slot="{ ariaDescribedby }" label="Business Type :" label-for="business_type">
              <b-form-checkbox-group
                  v-model="form.business_types"
                  id="business_type"
                  :aria-describedby="ariaDescribedby"
              >
                <b-form-checkbox v-for="(type,key) in business_typeList" checked="checked" :value="type.id" :key="key">{{type.name}}</b-form-checkbox>
              </b-form-checkbox-group>
            </b-form-group>
          </div>
        </div>
        <h6 class="mx-1 mt-2">{{ $t("message.company_edits.register_address") }}</h6>
        <div class="row">
          <div class="col-md-6 my-3">
            <b-form-group id="input-group-5" label-for="country">
              <label v-html="'Select Country ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-globe-asia"></i>
                </b-input-group-prepend>
                <b-form-select v-model="$v.form.country.$model" :options="Object.values(countryList)"
                               :state="validateState('country')" value-field="id" size="sm"
                               text-field="name" id="country" @input="countrySelect">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_country") }}</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-invalid-feedback v-if="!$v.form.country.required">
                  {{ $t("message.company_edits.country_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-6 my-3">
            <b-form-group id="input-group-6" label-for="division">
              <label v-html="'Select Division ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-search-location"></i>
                </b-input-group-prepend>
                <b-form-select v-model="$v.form.division.$model" :options="divisionList"
                               :state="validateState('division')" value-field="id" size="sm"
                               text-field="name" id="division" @input="divisionSelect">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_your_division") }}</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-invalid-feedback v-if="!$v.form.division.required">
                  {{ $t("message.company_edits.division_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-3">
            <b-form-group id="input-group-7" label-for="city">
              <label v-html="'Select City ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-building"></i>
                </b-input-group-prepend>
                <b-form-select v-model="$v.form.city.$model" :options="cityList"
                               :state="validateState('city')" value-field="id" size="sm"
                               text-field="name" id="city" @input="citySelect">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_your_city") }}</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-invalid-feedback v-if="!$v.form.city.required">
                  {{ $t("message.company_edits.city_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-3">
            <b-form-group id="input-group-8" label="Select Area :" label-for="area">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-map-marked-alt"></i>
                </b-input-group-prepend>
              <b-form-select v-model="form.area" :options="areaList"
                             value-field="id" size="sm"
                             text-field="name" id="area">
                <template v-slot:first>
                  <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_your_area") }}</b-form-select-option>
                </template>
              </b-form-select>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-3">
            <b-form-group id="input-group-10" label="Zip code :" label-for="zip_code">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fab fa-usps"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="zip_code" size="sm" type="number"
                    :placeholder= "$t('message.company_edit.enter_your_zip_code')"
                    v-model="$v.form.zip_code.$model"
                    :state="validateState('zip_code')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.zip_code.maxLength">
                  {{ $t("message.company_edits.maximum_10_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-6 my-3">
            <b-form-group id="input-group-9" label-for="address">
              <label v-html="'Address ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-home"></i>
                </b-input-group-prepend>
                <b-form-textarea
                    id="address" size="sm"
                    :placeholder= "$t('message.company_edit.for_example')"
                    rows="1" v-model="$v.form.address.$model"
                    :state="validateState('address')"
                ></b-form-textarea>
                <b-form-invalid-feedback v-if="!$v.form.address.required">
                  {{ $t("message.company_edits.leave_empty") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.address.maxLength">
                  {{ $t("message.company_edits.maximum_255_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <h6 class="mx-1 mt-2">{{ $t("message.company_edits.operation_address") }}</h6>
        <div class="row">
          <div class="col">
            <b-form-checkbox
                id="checkbox-1"
                v-model="form.address_type"
                name="checkbox-1"
                value="1" class="float-right"
                unchecked-value="2">
              {{ $t("message.company_edits.same_register_address") }}
            </b-form-checkbox>
          </div>
        </div>
        <div class="row border-bottom">
          <div class="col-md-6 my-3">
            <b-form-group id="input-group-11" label-for="country2">
              <label v-html="'Select Country ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-globe-asia"></i>
                </b-input-group-prepend>
                <b-form-select v-model="$v.form.country2.$model" :options="Object.values(countryList)"
                               value-field="id" size="sm" :state="validateState('country2')"
                               text-field="name" id="country2" @input="countrySelect2" :disabled="form.address_type == 1">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_country") }}</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-invalid-feedback v-if="!$v.form.country2.isUnique">
                  {{ $t("message.company_edits.country_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-6 my-3">
            <b-form-group id="input-group-12" label-for="division2">
              <label v-html="'Select Division ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-search-location"></i>
                </b-input-group-prepend>
                <b-form-select v-model="$v.form.division2.$model" :options="divisionList2"
                               value-field="id" size="sm" :state="validateState('division2')"
                               text-field="name" id="division2" @input="divisionSelect2"
                               :disabled="form.address_type == 1">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_your_division") }}</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-invalid-feedback v-if="!$v.form.division2.isUnique">
                  {{ $t("message.company_edits.division_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-3">
            <b-form-group id="input-group-13" label-for="city2">
              <label v-html="'Select City ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-building"></i>
                </b-input-group-prepend>
                <b-form-select v-model="$v.form.city2.$model" :options="cityList2" :disabled="form.address_type == 1"
                               value-field="id" size="sm" :state="validateState('city2')"
                               text-field="name" id="city2" @input="citySelect2">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_your_city") }}</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-invalid-feedback v-if="!$v.form.city2.isUnique">
                  {{ $t("message.company_edits.city_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-3">
            <b-form-group id="input-group-14" label="Select Area :" label-for="area">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-map-marked-alt"></i>
                </b-input-group-prepend>
                <b-form-select v-model="form.area" :options="areaList2"
                               value-field="id" size="sm" :disabled="form.address_type == 1"
                               text-field="name" id="area">
                  <template v-slot:first>
                    <b-form-select-option value="" disabled>{{ $t("message.company_edits.select_your_area") }}</b-form-select-option>
                  </template>
                </b-form-select>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-4 my-3">
            <b-form-group id="input-group-16" label="Zip code :" label-for="zip_code2">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fab fa-usps"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="zip_code2" size="sm" type="number" :state="validateState('zip_code2')"
                    :placeholder= "$t('message.company_edit.enter_your_zip_code')" :disabled="form.address_type == 1"
                    v-model="$v.form.zip_code2.$model"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.zip_code2.maxLength">
                  {{ $t("message.company_edits.maximum_10_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-md-6 my-3">
            <b-form-group id="input-group-15" label-for="address2">
              <label v-html="'Address ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-home"></i>
                </b-input-group-prepend>
                <b-form-textarea
                    id="address2" size="sm" :state="validateState('address2')"
                    :placeholder= "$t('message.company_edit.for_example')"
                    rows="1" v-model="$v.form.address2.$model" :disabled="form.address_type == 1"
                ></b-form-textarea>
                <b-form-invalid-feedback v-if="!$v.form.address2.isUnique">
                  {{ $t("message.company_edits.address_required") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <div class="row border-bottom">
          <div class="col-12 col-md-6 my-3">
            <b-form-group id="input-group-17" label="Company Website :" label-for="website">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-globe"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="website" type="url"
                    v-model="$v.form.website.$model" :state="validateState('website')"
                    :placeholder= "$t('message.company_edit.company_com')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.website.maxLength">
                  {{ $t("message.company_edits.company_website_255_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-6 my-3">
            <b-form-group id="input-group-26" label-for="email">
              <label v-html="'Company Email ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-envelope-open-text"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="email" type="email"
                    v-model="$v.form.email.$model" :state="validateState('email')"
                    :placeholder= "$t('message.company_edit.mail')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.email.required">
                  {{ $t("message.company_edits.company_email_required") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.email.email">
                  {{ $t("message.company_edits.enter_valid_email") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.email.maxLength">
                  {{ $t("message.company_edits.company_email_150_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-4 my-3">
            <b-form-group id="input-group-18" label-for="phone">
              <label v-html="'Company Phone Number ' + star+':'"></label>
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-blender-phone"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="phone" type="number"
                    v-model="$v.form.phone.$model" :state="validateState('phone')"
                    :placeholder= "$t('message.company_edit.phone')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.phone.required">
                  {{ $t("message.company_edits.company_phone_number_required") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.phone.maxLength">
                  {{ $t("message.company_edits.company_phone_number_15_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-4 my-3">
            <b-form-group id="input-group-19" label="Company Cell :" label-for="cell">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-phone-square-alt"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="cell" type="number"
                    v-model="$v.form.cell.$model" :state="validateState('cell')"
                    :placeholder= "$t('message.company_edit.cell')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.cell.maxLength">
                  {{ $t("message.company_edits.company_cell_15_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-4 my-3">
            <b-form-group id="input-group-20" label="Company Fax :" label-for="fax">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-fax"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="fax" type="number"
                    v-model="$v.form.fax.$model" :state="validateState('fax')"
                    :placeholder="$t('message.company_edit.fax')"
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.fax.maxLength">
                  {{ $t("message.company_edits.company_fax_15_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-4 my-3">
            <b-form-group id="input-group-21" label="Number of Employee :" label-for="number_of_employee">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="far fa-user-circle"></i>
                </b-input-group-prepend>
              <b-form-select id="number_of_employee" v-model="form.number_of_employee" :options="employeeList"
                             size="sm"></b-form-select>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-4 my-3">
            <b-form-group id="input-group-22" label="Ownership types :" label-for="ownership_type">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-font"></i>
                </b-input-group-prepend>
              <b-form-select id="ownership_type" v-model="form.ownership_type" :options="ownerList"
                             size="sm"></b-form-select>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-4 my-3">
            <b-form-group id="input-group-23" label="Annual revenue :" label-for="ownership_type">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-money-bill-wave"></i>
                </b-input-group-prepend>
              <b-form-select id="ownership_type" v-model="form.revenue" :options="revenueList"
                             size="sm"></b-form-select>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <h6 class="mx-1 mt-2">{{ $t("message.company_edits.company_products") }}</h6>
        <div class="row">
          <div class="col-12 col-md-6 mb-3">
            <b-form-group id="input-group-24" label="Company Main product :" label-for="main_product">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-luggage-cart"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="main_product"
                    v-model="$v.form.main_product.$model" :state="validateState('main_product')"
                    placeholder="Phone, Laptop, Computer...."
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.main_product.maxLength">
                  {{ $t("message.company_edits.maximum_255_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <b-form-group id="input-group-24" label="Company others product :" label-for="other_product">
              <b-input-group size="sm">
                <b-input-group-prepend is-text>
                  <i class="fas fa-shopping-bag"></i>
                </b-input-group-prepend>
                <b-form-input
                    id="main_product"
                    v-model="$v.form.other_product.$model" :state="validateState('other_product')"
                    placeholder="Ram. Processor, Memory....."
                ></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.other_product.maxLength">
                  {{ $t("message.company_edits.maximum_255_character") }}
                </b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <b-row class="justify-content-end">
          <b-col cols="12" sm="8" md="6" lg="4" class="my-4">
            <b-button type="submit" variant="success" class="mr-2">{{ $t("message.company_edits.save") }}</b-button>
            <b-button type="button" @click="$emit('view')" class="mr-2" variant="danger">{{ $t("message.company_edits.cancel") }}</b-button>
          </b-col>
        </b-row>
      </b-form>
    </div>
  </div>
</template>

<script>
import {validationMixin} from "vuelidate";
import {required, maxLength, email} from "vuelidate/lib/validators";
import {mapGetters} from "vuex";
import {ACTIVE_BUSINESS_TYPE_LIST} from "../../../../core/services/store/module/businesstype";
import {COMPANY_BASIC_LIST} from "../../../../core/services/store/module/companybasic";
import moment from "moment";
import ApiService from "../../../../core/services/api.service";
export default {
  mixins: [validationMixin],
  name: "CompanyEdit",
  data() {
    return {
      moment,
      star:'<span style="color:red;">*</span>',//for required red color sta
      nameStatus:false,//unique name status check
      message:'This name is already taken',//error message store for non unique name
      form: new Form({
        name: '',
        display_name: '',
        establishment_date: moment(),
        office_space: '',
        business_types:[],
        website: '',
        email: '',
        phone: '',
        cell: '',
        fax: '',
        number_of_employee: '',
        ownership_type: '',
        revenue: '',
        main_product: '',
        other_product: '',
        address_type: 2,
        country: '',
        division: '',
        city: '',
        area: '',
        address: '',
        zip_code: '',
        country2: '',
        division2: '',
        city2: '',
        area2: '',
        address2: '',
        zip_code2: '',
      }),
      divisionList: [],
      cityList: [],
      areaList: [],
      divisionList2: [],
      cityList2: [],
      areaList2: [],
      employeeList: [
        {value: '', text: this.$t("message.company_edits.please_select"), disabled: true},
        {value: '1', text: '1 - 5'},
        {value: '2', text: '5 - 10'},
        {value: '3', text: '1 - 100'}
      ],
      ownerList: [
        {value: '', text: this.$t("message.company_edits.please_select"), disabled: true},
        {value: 'Public', text: 'Public'},
        {value: 'Private', text: 'Private'},
      ],
      revenueList: [
        {value: '', text: this.$t("message.company_edits.please_select"), disabled: true},
        {value: '1', text: '1,00,000 - 5,00,000'},
        {value: '2', text: '5,00,000 - 10,00,000'},
      ],
    }
  },
  validations: {
    form: {
      name: {
        required,
        maxLength: maxLength(150)
      },
      display_name: {
        required,
        maxLength: maxLength(150)
      },
      office_space: {
        maxLength: maxLength(255)
      },
      country: {
        required
      },
      division: {
        required
      },
      city: {
        required
      },
      address: {
        required,
        maxLength: maxLength(255)
      },
      zip_code: {
        maxLength: maxLength(10)
      },
      website: {
        maxLength: maxLength(150)
      },
      email: {
        email,
        required,
        maxLength: maxLength(150)
      },
      phone: {
        required,
        maxLength: maxLength(15)
      },
      cell: {
        maxLength: maxLength(15)
      },
      fax: {
        maxLength: maxLength(15)
      },
      main_product: {
        maxLength: maxLength(255)
      },
      other_product: {
        maxLength: maxLength(255)
      },
      country2: {
        isUnique() {
          if (this.form.address_type == 1) return true
          return this.form.country2 !== '';
        }
      },
      division2: {
        isUnique() {
          if (this.form.address_type == 1) return true
          return this.form.division2 !== '';

        }
      },
      city2: {
        isUnique() {
          if (this.form.address_type == 1) return true
          return this.form.city2 !== '';

        }
      },
      address2: {
        isUnique() {
          if (this.form.address_type == 1) return true
          return this.form.address2 !== '';

        },
        maxLength: maxLength(255)
      },
      zip_code2: {
        maxLength: maxLength(10)
      },
    }
  },
  created() {
      this.$store.dispatch(ACTIVE_BUSINESS_TYPE_LIST);
    let b_types= this.companyBasicInfo.company?this.companyBasicInfo.company.business_types:[];
    this.form.business_types = b_types.map((item,key)=>{
        return item.id;
      });
    },
  methods: {
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    loadInfo(value) {
      if (value.result === 'Success') {
        this.form.fill(value.company);
        this.divisionList = this.getDivisionById(value.company.country);
        this.cityList = this.getCityById(value.company.division);
        this.areaList = this.getAreaById(value.company.city);
        if (value.company.address_type == 2) {
          this.divisionList2 = this.getDivisionById(value.company.country2);
          this.cityList2 = this.getCityById(value.company.division2);
          this.areaList2 = this.getAreaById(value.company.city2);
        }
      }
    },
    countrySelect(e) {
      this.form.division = '';
      this.form.city = '';
      this.form.area = '';
      this.cityList = [];
      this.areaList = [];
      this.divisionList = this.getDivisionById(e);
    },
    countrySelect2(e) {
      this.form.division2 = '';
      this.form.city2 = '';
      this.form.area2 = '';
      this.cityList2 = [];
      this.areaList2 = [];
      this.divisionList2 = this.getDivisionById(e);
    },
    divisionSelect(e) {
      this.form.city = '';
      this.form.area = '';
      this.areaList = [];
      this.cityList = this.getCityById(e);
    },
    divisionSelect2(e) {
      this.form.city2 = '';
      this.form.area2 = '';
      this.areaList2 = [];
      this.cityList2 = this.getCityById(e);
    },
    citySelect(e) {
      this.form.area = '';
      this.areaList = this.getAreaById(e);
    },
    citySelect2(e) {
      this.form.area2 = '';
      this.areaList2 = this.getAreaById(e);
    },
    nameCheck(){
      ApiService.post(`company/user/unique/name/check${this.form.display_name}`).then((response)=>{
          this.nameStatus=response.data;
      }).catch((error)=>{
        //noting to do
      })
    },
    submit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      if (this.nameStatus) return;
      this.form.post('factory')
          .then((response) => {
            this.$toaster.success(response.data.message);
            this.$store.dispatch(COMPANY_BASIC_LIST)
            this.$emit('view');
          }).catch((error)=>{
        this.$toaster.error(error);
      });

    }
  },
  computed: {
    ...mapGetters(["companyBasicInfo","business_typeList", "countryList", "getDivisionById", "getCityById", "getAreaById"]),
  },
  watch: {
    companyBasicInfo: {
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