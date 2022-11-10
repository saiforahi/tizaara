<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="row">
        <h3>{{ $t("message.cart.checkout") }}</h3>
        <div>
          <div class="container">
            <div class="row">

              <div class="col-md-4 order-md-2 mb-4 mt-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                  <span class="text-muted">Your cart</span>
                  <span class="badge badge-secondary badge-pill">{{ carts.length }}</span>
                </h4>
                <ul class="list-group mb-3 sticky-top">
                  <li v-for="(cart,key) in carts" class="list-group-item d-flex justify-content-between lh-condensed" :key="key">
                    <div>
                      <span class="my-0">{{ cart.product.name }}</span><br>
                      <span class="text-muted"> <strong>{{ cart.quantity+' X '+cart.discount_price }}</strong></span>
                    </div>
                    <h6 class="text-muted">{{ (cart.discount_price*cart.quantity) }}</h6>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <span class="my-0">{{ $t('message.cart.sub_total') }}</span>
                     </div>
                    <span class="text-muted">{{ getSubTotal }}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <span class="my-0">{{ $t('message.cart.shipping_charge') }}</span>
                    </div>
                    <span class="text-muted">{{ totalShippingCost }}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <span class="my-0">{{ $t('message.cart.vat') }}</span>
                    </div>
                    <span class="text-muted">{{ totalTax }}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <h6 class="my-0">{{ 'Total (BDT)' }}</h6>
                    </div>
                    <strong>{{ getSubTotal+totalTax+totalShippingCost }}</strong>
                  </li>
                </ul>
              </div>

              <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Billing address</h4>
                <form class="needs-validation" novalidate="" method="post" action="javascript:void(0)">
                  <div class="row">
                    <div class="col-md-6 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.first_name') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model="$v.form.first_name.$model" type="text"
                                        id="export_started_year" size="sm" :state="validateState('first_name')"
                                        :placeholder= "$t('message.checkout.first_name')"
                          ></b-form-input>
                          <b-form-invalid-feedback v-if="!$v.form.first_name.required">
                            {{ $t("message.checkout.first_name_required") }}
                          </b-form-invalid-feedback>
                          <b-form-invalid-feedback v-if="!$v.form.first_name.maxLength">
                            {{ $t("message.checkout.first_name_max") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-6 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.last_name') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model="$v.form.last_name.$model" type="text"
                                        id="export_started_year" size="sm" :state="validateState('last_name')"
                                        :placeholder= "$t('message.checkout.last_name')"
                          ></b-form-input>
                          <b-form-invalid-feedback v-if="!$v.form.last_name.required">
                            {{ $t("message.checkout.last_name_required") }}
                          </b-form-invalid-feedback>
                          <b-form-invalid-feedback v-if="!$v.form.last_name.maxLength">
                            {{ $t("message.checkout.last_name_max") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-6 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.email') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model="$v.form.email.$model" type="email"
                                        id="email" size="sm" :state="validateState('email')"
                                        :placeholder= "$t('message.checkout.email')"
                          ></b-form-input>
                          <b-form-invalid-feedback v-if="!$v.form.email.required">
                            {{ $t("message.checkout.email_required") }}
                          </b-form-invalid-feedback>
                          <b-form-invalid-feedback v-else-if="!$v.form.email.maxLength">
                            {{ $t("message.checkout.email_max") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-6 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.phone_number') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model="$v.form.phone_number.$model"
                                        id="email" size="sm" :state="validateState('phone_number')"
                                        :placeholder= "$t('message.checkout.phone_number')"
                          ></b-form-input>
                          <b-form-invalid-feedback v-if="!$v.form.phone_number.required">
                            {{ $t("message.checkout.phone_number_required") }}
                          </b-form-invalid-feedback>
                          <b-form-invalid-feedback v-else-if="!$v.form.phone_number.maxLength">
                            {{ $t("message.checkout.phone_number_max") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-6 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.address_l1') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model="$v.form.address_l1.$model"
                                        id="email" size="sm" :state="validateState('address_l1')"
                                        :placeholder= "$t('message.checkout.address_l1')"
                          ></b-form-input>
                          <b-form-invalid-feedback v-if="!$v.form.address_l1.required">
                            {{ $t("message.checkout.address_l1_required") }}
                          </b-form-invalid-feedback>
                          <b-form-invalid-feedback v-else-if="!$v.form.address_l1.maxLength">
                            {{ $t("message.checkout.maximum_char_is_191") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-6 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.address_l2') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model="form.address_l2"
                                        id="address_l2" size="sm"
                                        :placeholder= "$t('message.checkout.address_l2')"
                          ></b-form-input>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-4 my-2">
                      <b-form-group id="input-group-7" label-for="annual_revenue_id">
                        <label>{{ $t('message.checkout.country') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-money-bill-alt"></i>
                          </b-input-group-prepend>
                          <b-form-select :options="countryList" @change="dropChange('country')" v-model="$v.form.country_id.$model"
                                         :state="validateState('country_id')" value-field="id" size="sm"
                                         text-field="name">
                            <template v-slot:first>
                              <b-form-select-option :value="null" disabled>{{ $t("message.checkout.country") }}</b-form-select-option>
                            </template>
                          </b-form-select>
                          <b-form-invalid-feedback v-if="!$v.form.country_id.required">
                            {{ $t("message.checkout.country_required") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-4 my-2">
                      <b-form-group id="input-group-7" label-for="annual_revenue_id">
                        <label>{{ $t('message.checkout.division') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-money-bill-alt"></i>
                          </b-input-group-prepend>
                          <b-form-select :options="getDivisionById(form.country_id)" @change="dropChange('division')" v-model="$v.form.division_id.$model"
                                         :state="validateState('division_id')" value-field="id" size="sm"
                                         text-field="name">
                            <template v-slot:first>
                              <b-form-select-option :value="null" disabled>{{ $t("message.checkout.division") }}</b-form-select-option>
                            </template>
                          </b-form-select>
                          <b-form-invalid-feedback v-if="!$v.form.division_id.required">
                            {{ $t("message.checkout.division_required") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-4 my-2">
                      <b-form-group id="input-group-7" label-for="annual_revenue_id">
                        <label>{{ $t('message.checkout.city') }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-money-bill-alt"></i>
                          </b-input-group-prepend>
                          <b-form-select :options="getCityById(form.division_id)" v-model="$v.form.city_id.$model"
                                         :state="validateState('city_id')" value-field="id" size="sm"
                                         text-field="name">
                            <template v-slot:first>
                              <b-form-select-option :value="null" disabled>{{ $t('message.checkout.city')  }}</b-form-select-option>
                            </template>
                          </b-form-select>
                          <b-form-invalid-feedback v-if="!$v.form.city_id.required">
                            {{ $t('message.checkout.city_required')  }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                    <div class="col-md-4 my-2">
                      <b-form-group id="input-group-1" label-for="export_started_year">
                        <label>{{ $t('message.checkout.zip_code')  }}</label>
                        <b-input-group size="sm">
                          <b-input-group-prepend is-text>
                            <i class="fas fa-vote-yea"></i>
                          </b-input-group-prepend>
                          <b-form-input v-model.number="$v.form.zip.$model" type="number"
                                        id="zip" size="sm" :state="validateState('zip')"
                                        :placeholder= "$t('message.checkout.zip_code')"
                          ></b-form-input>
                          <b-form-invalid-feedback v-if="!$v.form.zip.required">
                            {{ $t("message.checkout.zip_code_required") }}
                          </b-form-invalid-feedback>
                        </b-input-group>
                      </b-form-group>
                    </div>
                  </div>
                  <hr class="mb-4">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="shipping_a_s_a_b_address" :checked="shipping_a_s_a_b_address" id="same-address">
                    <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Save this information for next time</label>
                  </div>
                  <hr class="mb-4">
                  <div v-if="!shipping_a_s_a_b_address">
                    <h4 class="mb-3">Shipping address</h4>
                    <div class="row">
                      <div class="col-md-6 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.first_name') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model="$v.form.s_first_name.$model" type="text"
                                          id="export_started_year" size="sm" :state="validateState('s_first_name')"
                                          :placeholder= "$t('message.checkout.first_name')"
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.s_first_name.required">
                              {{ $t("message.checkout.first_name_required") }}
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-if="!$v.form.s_first_name.maxLength">
                              {{ $t("message.checkout.maximum_char_is_191") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-6 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.last_name') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model="$v.form.s_last_name.$model" type="text"
                                          id="export_started_year" size="sm" :state="validateState('s_last_name')"
                                          :placeholder= "$t('message.checkout.last_name')"
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.s_last_name.required">
                              {{ $t("message.checkout.last_name_required") }}
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-if="!$v.form.s_last_name.maxLength">
                              {{ $t("message.checkout.maximum_char_is_191") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-6 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.email') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model="$v.form.s_email.$model" type="email"
                                          id="email" size="sm" :state="validateState('s_email')"
                                          :placeholder= "$t('message.checkout.email')"
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.s_email.required">
                              {{ $t("message.checkout.email_required") }}
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-else-if="!$v.form.s_email.maxLength">
                              {{ $t("message.checkout.maximum_char_is_191") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-6 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.phone_number') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model="$v.form.s_phone_number.$model"
                                          id="email" size="sm" :state="validateState('s_phone_number')"
                                          :placeholder= "$t('message.checkout.phone_number')"
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.s_phone_number.required">
                              {{ $t("message.checkout.phone_number_required") }}
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-else-if="!$v.form.s_phone_number.maxLength">
                              {{ $t("message.checkout.phone_number_max_char_over") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-6 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.address_l1') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model="$v.form.s_address_l1.$model"
                                          id="email" size="sm" :state="validateState('s_address_l1')"
                                          :placeholder= "$t('message.checkout.address_l1')"
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.s_address_l1.required">
                              {{ $t("message.checkout.address_l1_required") }}
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-else-if="!$v.form.s_address_l1.maxLength">
                              {{ $t("message.checkout.maximum_char_is_191") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-6 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.address_l2') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model="form.s_address_l2"
                                          id="address_l2" size="sm"
                                          :placeholder= "$t('message.checkout.address_l2')"
                            ></b-form-input>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-4 my-2">
                        <b-form-group id="input-group-7" label-for="annual_revenue_id">
                          <label>{{ $t('message.checkout.country') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-money-bill-alt"></i>
                            </b-input-group-prepend>
                            <b-form-select :options="countryList" @change="dropChange2('country')" v-model="$v.form.s_country_id.$model"
                                           :state="validateState('s_country_id')" value-field="id" size="sm"
                                           text-field="name">
                              <template v-slot:first>
                                <b-form-select-option :value="null" disabled>{{ $t("message.checkout.country") }}</b-form-select-option>
                              </template>
                            </b-form-select>
                            <b-form-invalid-feedback v-if="!$v.form.s_country_id.required">
                              {{ $t("message.checkout.country_required") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-4 my-2">
                        <b-form-group id="input-group-7" label-for="annual_revenue_id">
                          <label>{{ $t('message.checkout.division') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-money-bill-alt"></i>
                            </b-input-group-prepend>
                            <b-form-select :options="getDivisionById(form.s_country_id)" @change="dropChange2('division')" v-model="$v.form.s_division_id.$model"
                                           :state="validateState('s_division_id')" value-field="id" size="sm"
                                           text-field="name">
                              <template v-slot:first>
                                <b-form-select-option :value="null" disabled>{{ $t("message.checkout.division") }}</b-form-select-option>
                              </template>
                            </b-form-select>
                            <b-form-invalid-feedback v-if="!$v.form.s_division_id.required">
                              {{ $t("message.checkout.division_required") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-4 my-2">
                        <b-form-group id="input-group-7" label-for="annual_revenue_id">
                          <label>{{ $t('message.checkout.city') }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-money-bill-alt"></i>
                            </b-input-group-prepend>
                            <b-form-select :options="getCityById(form.s_division_id)" v-model="$v.form.s_city_id.$model"
                                           :state="validateState('s_city_id')" value-field="id" size="sm"
                                           text-field="name">
                              <template v-slot:first>
                                <b-form-select-option :value="null" disabled>{{ $t("message.checkout.city") }}</b-form-select-option>
                              </template>
                            </b-form-select>
                            <b-form-invalid-feedback v-if="!$v.form.s_city_id.required">
                              {{ $t("message.checkout.city_required") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                      <div class="col-md-4 my-2">
                        <b-form-group id="input-group-1" label-for="export_started_year">
                          <label>{{ $t('message.checkout.zip_code')  }}</label>
                          <b-input-group size="sm">
                            <b-input-group-prepend is-text>
                              <i class="fas fa-vote-yea"></i>
                            </b-input-group-prepend>
                            <b-form-input v-model.number="$v.form.s_zip.$model" type="number"
                                          id="zip" size="sm" :state="validateState('s_zip')"
                                          :placeholder= "$t('message.checkout.zip_code')"
                            ></b-form-input>
                            <b-form-invalid-feedback v-if="!$v.form.s_zip.required">
                              {{ $t("message.checkout.zip_code_required") }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </div>
                    </div>
                    <hr class="mb-4">
                  </div>
                  <!--    card sections      -->
                  <h4 class="mb-3">Payment</h4>
                  <div class="d-block my-3">
                    <div class="custom-control custom-radio" >
                      <span @click="is_cash_on_delivery=true">
                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked="" required="">
                        <label class="custom-control-label" for="credit">Cash on Delivery</label>
                      </span>
                    </div>
                    <!-- <div class="custom-control custom-radio">
                      <span @click="is_cash_on_delivery=false">
                        <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required="" disabled>
                        <label class="custom-control-label" for="debit">SSL Commerze</label>
                      </span>
                    </div> -->
                    <div class="custom-control custom-radio">
                      <span @click="is_cash_on_delivery=false">
                        <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required="">
                        <label class="custom-control-label" for="debit">Bank / Bkash / Nagad</label>
                      </span>
                    </div>
                  </div>
<!--                  <div v-if="!is_cash_on_delivery">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="cc-name">Name on card</label>
                        <input type="text" class="form-control" id="cc-name" placeholder="" required="">
                        <small class="text-muted">Full name as displayed on card</small>
                        <div class="invalid-feedback"> Name on card is required </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="cc-number">Credit card number</label>
                        <input type="text" class="form-control" id="cc-number" placeholder="" required="">
                        <div class="invalid-feedback"> Credit card number is required </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 mb-3">
                        <label for="cc-expiration">Expiration</label>
                        <input type="text" class="form-control" id="cc-expiration" placeholder="" required="">
                        <div class="invalid-feedback"> Expiration date required </div>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="cc-cvv">CVV</label>
                        <input type="text" class="form-control" id="cc-cvv" placeholder="" required="">
                        <div class="invalid-feedback"> Security code required </div>
                      </div>
                    </div>
                  </div>-->
                  <hr class="mb-4">
                  <button v-if="carts.length>0" class="btn btn-primary btn-lg btn-block" id="sslczPayBtn" @click="checkout" type="submit">Continue to checkout</button>
<!--                  <button class="btn btn-primary btn-lg btn-block" id="sslczPayBtn" @click="sslCommerze"
                          token="if you have any token validation"
                          postdata="your javascript arrays or objects which requires in backend"
                          order="If you already have the transaction generated for current order"
                          :endpoint=" '/pay-via-ajax'"> Pay Now
                  </button>-->
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {validationMixin} from "vuelidate";
import {required, maxLength,email} from "vuelidate/lib/validators";
import {COUNTRY_LIST} from "../../core/services/store/module/country";
import {CITY_LIST} from "../../core/services/store/module/city";
import {mapGetters} from "vuex";
import {DIVISION_LIST} from "../../core/services/store/module/division";
import {api_base_url} from "../../core/config/app";
import {CLEAR_CART} from "../../core/services/store/module/cart";
import shurjopay from 'shurjopay'

export default {
  name: "CheckOut",
  mixins: [validationMixin],
  data(){
    return{
      is_cash_on_delivery:true,
      shipping_a_s_a_b_address:false,
      form:new Form({
        first_name:'',
        last_name:'',
        email:'',
        phone_number:'',
        address_l1:'',
        address_l2:'',
        country_id:null,
        city_id:null,
        division_id:null,
        zip:'',
        has_shipping_address:false,
        s_first_name:'',
        s_last_name:'',
        s_email:'',
        s_phone_number:'',
        s_address_l1:'',
        s_address_l2:'',
        s_country_id:null,
        s_city_id:null,
        s_division_id:null,
        s_zip:'',
        is_cash_on_delivery:true,
        carts:[],
        pay_amount:0,
        user_id:null,
      }),
      cities:[],
      areas:[],
    }
  },
  validations: {
    form: {
      first_name: {
        required,
        maxLength: maxLength(50)
      },
      last_name: {
        required,
        maxLength: maxLength(50)
      },
      email: {
        required,email,
      },
      phone_number:{
        required,
      },
      address_l1:{
        required,
      },
      country_id:{
        required,
      },
      city_id:{
        required,
      },
      division_id:{
        required,
      },
      zip:{
        required,
      },
      s_first_name: {
        required,
        maxLength: maxLength(50)
      },
      s_last_name: {
        required,
        maxLength: maxLength(50)
      },
      s_email: {
        required,email,
      },
      s_phone_number:{
        required,
      },
      s_address_l1:{
        required,
      },
      s_country_id:{
        required,
      },
      s_city_id:{
        required,
      },
      s_division_id:{
        required,
      },
      s_zip:{
        required,
      }
    }
  },
  created() {
    console.log(this.$route.query.order_id)
    if(this.countryList<1) this.$store.dispatch(COUNTRY_LIST);
    if(this.divisionList<1) this.$store.dispatch(DIVISION_LIST);
    if(this.cityList<1) this.$store.dispatch(CITY_LIST);
    this.form.user_id=this.user.id;
    var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
    script.src = "https://sandbox.sslcommerz.com/sslcommerz-4.1.0.min.js?" + Math.random().toString(36).substring(7);
    // script.src = "https://seamless-epay.sslcommerz.com/sslcommerz-4.1.0.min.js?" + Math.random().toString(36).substring(7);
    tag.parentNode.insertBefore(script, tag);
  },

  computed: {
    ...mapGetters(["countryList","cityList","divisionList","getCityById","getDivisionById",
      "carts","getTotal","getSubTotal","totalShippingCost","totalTax","user"]),
  },
  methods:{

    dropChange(name){
      if (name==='country'){
        this.form.division_id=null;
        this.form.city_id=null;
      }else if(name==='division') this.form.city_id=null;
    },
    dropChange2(name){
      if (name==='country'){
        this.form.s_division_id=null;
        this.form.s_city_id=null;
      }else if(name==='division') this.form.s_city_id=null;
    },
    validateState(name){
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    checkout(){
      this.submit();
      // else this.sslCommerze();
      // else this.shurjopay();
      // else window.open("http://localhost:8000/shurjo?amount=100&user="+this.user_id)
    },
    shurjopay(transaction_id){
      let total_amount = this.getSubTotal+this.totalTax+this.totalShippingCost
      let city = this.cityList.find(a=>a.id==this.form.city_id).name
      let division = this.divisionList.find(a=>a.id==this.form.division_id).name
      let country = this.countryList.find(a=>a.id==this.form.country_id).code
      console.log('city',city,)
      if (this.shipping_a_s_a_b_address){
        this.form.s_first_name=this.form.first_name;
        this.form.s_last_name=this.form.last_name;
        this.form.s_email=this.form.email;
        this.form.s_phone_number=this.form.phone_number;
        this.form.s_address_l1=this.form.address_l1;
        this.form.s_address_l2=this.form.address_l2;
        this.form.s_country_id=this.form.country_id;
        this.form.s_city_id=this.form.city_id;
        this.form.s_division_id=this.form.division_id;
        this.form.s_zip=this.form.zip;
      }
      window.location.replace(process.env.VUE_APP_API_BASE_URL+"shurjo?amount="+total_amount+"&user="+this.user_id+"&name="+this.form.first_name+" "+this.form.last_name+"&phone="+this.form.phone_number+"&email="+this.form.email+"&address="+this.form.address_l1+"&city="+city+"&division="+division+"&country="+country+"&order_id="+transaction_id+"&zip="+this.form.zip)
    },
    /*
    * method for ssl commerze open
    * */
    sslCommerze(){

      /*this.form2.post('pay-via-ajax').then((response)=>{
        console.log(response.data);
      }).catch((error)=>{
        console.log(error);
      })*/
      if (this.shipping_a_s_a_b_address){
        this.form.s_first_name=this.form.first_name;
        this.form.s_last_name=this.form.last_name;
        this.form.s_email=this.form.email;
        this.form.s_phone_number=this.form.phone_number;
        this.form.s_address_l1=this.form.address_l1;
        this.form.s_address_l2=this.form.address_l2;
        this.form.s_country_id=this.form.country_id;
        this.form.s_city_id=this.form.city_id;
        this.form.s_division_id=this.form.division_id;
        this.form.s_zip=this.form.zip;
      }
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.carts=this.carts;
      this.form.pay_amount=this.getSubTotal+this.totalTax+this.totalShippingCost;
      window.sslczPayBtn( api_base_url+'pay-via-ajax',this.form);
    },
    submit() {
      if (this.shipping_a_s_a_b_address){
        this.form.s_first_name=this.form.first_name;
        this.form.s_last_name=this.form.last_name;
        this.form.s_email=this.form.email;
        this.form.s_phone_number=this.form.phone_number;
        this.form.s_address_l1=this.form.address_l1;
        this.form.s_address_l2=this.form.address_l2;
        this.form.s_country_id=this.form.country_id;
        this.form.s_city_id=this.form.city_id;
        this.form.s_division_id=this.form.division_id;
        this.form.s_zip=this.form.zip;
      }
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }
      this.form.carts=this.carts;
      this.form.post('checkout')
          .then((response) => {
            if (!this.is_cash_on_delivery) {
              this.shurjopay(response.data.data.transaction_id)
            }
            else{
              this.$toaster.success(response.data.message);
              this.$router.push({name:'checkout.success'});
              this.$store.commit(CLEAR_CART);
            }
            
          }).catch((error)=>{
            console.log(error)
            if (error?.response?.status==422) this.$toaster.error(error.response.data?.message);
            else this.$toaster.error(error);
      })
    },
  },
  watch:{
    user(){
      this.form.user_id=this.user.id;
    }
  }
}
</script>

<style scoped>
.bd-placeholder-img {
  font-size: 1.125rem;
  text-anchor: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
@media (min-width: 768px) {
  .bd-placeholder-img-lg {
    font-size: 3.5rem;
  }
}
</style>