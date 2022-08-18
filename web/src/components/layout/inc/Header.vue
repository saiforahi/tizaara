<template>
  <div>
    <!-- Registration Modal -->
    <div class="modal fade" id="reg" tabindex="-1" role="dialog" aria-labelledby="reg" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ $t("message.headers.create_account") }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="onSubmit" @keydown="formReg.onKeydown($event)">
            <div class="modal-body">
              <fieldset style="background: #f9f9f9;padding: 10px 15px 0;margin-bottom: 15px;">
                <div class="form-group text-center">
                  <div class="form-check form-check-inline" style="margin-right: 8px;vertical-align: top;">
                    <label class="form-check-label"><b>{{ $t("message.headers.i_am_a") }}</b></label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" v-model="formReg.account_type" id="supplier" value="1">
                    <label class="form-check-label" for="supplier">{{ $t("message.headers.supplier") }}</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" v-model="formReg.account_type" id="buyer" value="2">
                    <label class="form-check-label" for="buyer">{{ $t("message.headers.buyer") }}</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" v-model="formReg.account_type" id="both" value="0">
                    <label class="form-check-label" for="both">{{ $t("message.headers.both") }}</label>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <div class="form-row mb-4">
                  <div class="form-group col-md-6">
                    <b-form-input class="form-control" id="first-name"
                                  :placeholder="$t('message.headers.first_name')" type="text"
                                  v-model="$v.formReg.first_name.$model"
                                  :state="validateState('first_name')"></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.formReg.first_name.required">
                      {{ $t("message.headers.first_name") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.formReg.first_name.maxLength">
                      {{ $t("message.headers.first_name_255_letters") }}
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group col-md-6">
                    <b-form-input class="form-control" id="last-name"
                                  :placeholder="$t('message.headers.last_name')" type="text"
                                  v-model="$v.formReg.last_name.$model"
                                  :state="validateState('last_name')"></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.formReg.last_name.required">
                      {{ $t("message.headers.last_name") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.formReg.last_name.maxLength">
                      {{ $t("message.headers.last_name_255_letters") }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div class="form-group mb-4">
                  <b-form-input class="form-control" id="email-phone"
                                :placeholder="$t('message.headers.email_or_phone_number')" type="text"
                                v-model="$v.formReg.emailOrMobile.$model"
                                :class="{ 'is-invalid': formReg.errors.has('emailOrMobile') }"
                                :state="validateState('emailOrMobile')"></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.formReg.emailOrMobile.required">
                    {{ $t("message.headers.email_address_or_phone_number") }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-if="!$v.formReg.emailOrMobile.maxLength">
                    {{ $t("message.headers.email_or_phone_255_letters") }}
                  </b-form-invalid-feedback>
                  <has-error :form="formReg" field="emailOrMobile"></has-error>
                </div>
                <div class="form-row mb-2">
                  <div class="form-group col-md-6 show_hide_password">
                    <b-form-input class="form-control" id="password"
                                  :placeholder="$t('message.headers.password')" type="password"
                                  v-model="$v.formReg.password.$model"
                                  :state="validateState('password')"></b-form-input>
                    <password v-model="formReg.password" :strength-meter-only="true"/>
                    <b-form-invalid-feedback v-if="!$v.formReg.password.required">
                      {{ $t("message.headers.password") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.formReg.password.minLength">
                      {{ $t("message.headers.password_8_letters") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.formReg.password.maxLength">
                      {{ $t("message.headers.password_16_letters") }}
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group col-md-6 show_hide_password">
                    <b-form-input class="form-control" id="confirm-password"
                                  :placeholder="$t('message.headers.confirm_password')" type="password"
                                  v-model="$v.formReg.confirmPassword.$model"
                                  :state="validateState('confirmPassword')"></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.formReg.confirmPassword.sameAsPassword">
                      {{ $t("message.headers.passwords_identical") }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div class="form-check">
                  <input class="form-check-input" v-model="$v.formReg.termCondition.$model"
                         type="checkbox" value="accept" id="agree"
                         :class="{'is-invalid': termConditionError}">
                  <label class="form-check-label" for="agree">
                    {{ $t("message.headers.agree_our") }} <a href="" style="color: #f05a28">{{
                      $t("message.headers.terms_condition")
                    }}</a>.
                  </label><br>
                  <b-form-invalid-feedback v-if="termConditionError">
                    {{ $t("message.headers.agree_terms_condition") }}
                  </b-form-invalid-feedback>
                </div>
              </fieldset>
            </div>
            <div class="modal-footer border-top-0">
              <div class="form-row">
                <div class="form-group col-md-7">
                  <vue-recaptcha ref="recaptcha" @expired="formReg.robot = false"
                                 @verify="response => response?formReg.robot = true:''"
                                 sitekey="6LdghNAZAAAAAK3iA9gR8N261fMwCTge0IdadAyO" :loadRecaptchaScript="true">
                  </vue-recaptcha>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-9 justify-content-start align-items-center">
                  <a href="" class="facebook-btn">{{ $t("message.headers.sign_up_facebook") }}</a>
                </div>
                <div class="form-group col-md-3 justify-content-end">
                  <button :disabled="formReg.busy || !formReg.robot" type="submit" class="btn reg-btn">
                    <span v-if="formReg.busy" class="spinner-border spinner-border-sm" role="status"
                          aria-hidden="true"></span>
                    <span v-if="formReg.busy" class="sr-only">{{ $t("message.headers.loading") }}</span>
                    <span v-if="!formReg.busy">{{ $t("message.headers.submit") }}</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="form-row justify-content-center">
              <p><span style="color: #aaa">{{ $t("message.headers.already_account") }}</span> <a @click="openLoginModal"
                                                                                                 style="cursor: pointer">{{
                  $t("message.headers.login_now")
                }}</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Verification Modal -->
    <div class="modal fade" id="verify" tabindex="-1" role="dialog" aria-labelledby="reg" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ $t("message.headers.verify_email_address") }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="verificationSubmit" @keydown="formVerify.onKeydown($event)">
            <div class="modal-body">
              <fieldset>
                <div class="form-group row">
                  <label for="staticEmail" class="col-sm-4 text-sm-right col-form-label">{{
                      $t("message.headers.email")
                    }} </label>
                  <div class="col-sm-8">
                    <input type="text" readonly class="form-control-plaintext" v-model="formVerify.emailOrMobile">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="verificationToken" class="col-sm-4 text-sm-right col-form-label">{{
                      $t("message.headers.verification_code")
                    }} </label>
                  <div class="col-sm-5">
                    <input id="verificationToken" type="number" class="form-control"
                           v-model="formVerify.verificationToken"
                           :class="{ 'is-invalid': formVerify.errors.has('verificationToken') }" required>
                    <has-error :form="formVerify" field="verificationToken"></has-error>
                    <small id="passwordHelpBlock" class="form-text text-muted">
                      {{ $t("message.headers.verification_code_your_email") }}
                    </small>
                  </div>
                  <div class="col-md-3">
                    <button @click="verificationResend" :disabled="!timerBusy" type="button"
                            class="btn btn-sm btn-info w-100">
                      <span v-if="!timerBusy">({{ timer }} s)</span>
                      <span v-if="timerBusy">{{ $t("message.headers.resend") }}</span>
                    </button>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="modal-footer border-top-0">
              <div class="form-row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                  <button :disabled="timerBusy || formVerify.busy" type="submit" class="btn btn-danger">
                    <span v-if="formVerify.busy" class="spinner-border spinner-border-sm" role="status"
                          aria-hidden="true"></span>
                    <span v-if="formVerify.busy" class="sr-only">{{ $t("message.headers.loading") }}</span>
                    <span v-if="!formVerify.busy">{{ $t("message.headers.submit") }}</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">{{ $t("message.headers.welcome_back") }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.stop.prevent="loginSubmit">
            <div class="modal-body">
              <div role="alert" v-if="errors.length > 0" class="alert fade alert-danger show">
                <div class="alert-text">
                  {{ errors }}
                </div>
              </div>
              <div class="form-group mb-4">
                <b-form-input class="form-control" id="login-email-phone"
                              :placeholder="$t('message.headers.email_or_phone_number')" type="text"
                              v-model="$v.form.emailOrMobile.$model"
                              :state="validateState2('emailOrMobile')"></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.emailOrMobile.required">
                  {{ $t("message.headers.email_address_phone_number") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.emailOrMobile.maxLength">
                  {{ $t("message.headers.email_or_phone_255_letters") }}
                </b-form-invalid-feedback>
              </div>
              <div class="form-group mb-3">
                <b-form-input class="form-control" id="login-password"
                              :placeholder="$t('message.headers.password')" type="password"
                              v-model="$v.form.password.$model"
                              :state="validateState2('password')"></b-form-input>
                <b-form-invalid-feedback v-if="!$v.form.password.required">
                  {{ $t("message.headers.password") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.password.minLength">
                  {{ $t("message.headers.password_8_letters") }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback v-if="!$v.form.password.maxLength">
                  {{ $t("message.headers.password_16_letters") }}
                </b-form-invalid-feedback>
              </div>
              <div class="form-group">
                <a href="javascript:void(0)" @click="openForgetModal" style="color: #f05a28;text-decoration: underline">{{
                    $t("message.headers.forget_password")
                  }}</a>
              </div>
            </div>
            <div class="modal-footer border-top-0">
              <div class="form-row">
                <div class="form-group col-md-9 justify-content-start align-items-center">
                  <a class="facebook-btn cursor-hand"
                     @click.prevent="loginWithFacebook">{{ $t("message.headers.sign_up_facebook") }}</a>
                  <a class="google-btn cursor-hand"
                     @click.prevent="loginWithGoogle">{{ $t("message.headers.sign_up_google") }}</a>
                </div>
                <div class="form-group col-md-3 justify-content-end align-items-center">
                  <button type="submit" class="btn reg-btn">{{ $t("message.headers.log_in") }}</button>
                </div>
              </div>
            </div>
            <div class="form-row justify-content-center">
              <p><span style="color: #aaa">{{ $t("message.headers.have_not_account") }}</span> <a
                  @click="openRegistrationModal"
                  style="cursor: pointer">{{ $t("message.headers.register_now") }}</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--   Contact modal -->
    <ContactModal/>

    <!-- Forget password Modal -->
    <div class="modal fade" id="forget_password_modal" tabindex="-1" role="dialog" aria-labelledby="login"
         aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="forget_password_title">{{ $t("message.headers.forget_password") }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.stop.prevent="findSubmitMethod()">
            <div class="modal-body">
              <template v-if="forget_password_state==1">
                <div role="alert" v-if="errors.length > 0" class="alert fade alert-danger show">
                  <div class="alert-text">
                    {{ errors }}
                  </div>
                </div>
                <div class="form-group mb-4">
                  <b-form-input class="form-control" id="email"
                                :placeholder="$t('message.headers.enter_your_email_address')" type="text"
                                v-model="$v.formForget.email.$model"
                                :state="validateState3('email')"></b-form-input>
                  <b-form-invalid-feedback v-if="!$v.formForget.email.required">
                    {{ $t("message.headers.email_required") }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-if="!$v.formForget.email.maxLength">
                    {{ $t("message.headers.email_max_255_letters") }}
                  </b-form-invalid-feedback>
                </div>
              </template>
              <template v-else-if="forget_password_state==2">
                <fieldset>
                  <div class="form-group row">
                    <label for="v_email" class="col-sm-4 text-sm-right col-form-label">{{
                        $t("message.headers.email")
                      }} </label>
                    <div class="col-sm-8">
                      <input type="text" id="v_email" readonly class="form-control-plaintext"
                             v-model="formForgetVerify.email">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="verification_code" class="col-sm-4 text-sm-right col-form-label">{{
                        $t("message.headers.verification_code")
                      }} </label>
                    <div class="col-sm-5">
                      <input id="verification_code" type="number" class="form-control"
                             v-model="formForgetVerify.verificationToken"
                             :class="{ 'is-invalid': formForgetVerify.errors.has('verificationToken') }" required>
                      <has-error :form="formForgetVerify" field="verificationToken"></has-error>
                      <small id="password_help_block" class="form-text text-muted">
                        {{ $t("message.headers.verification_code_your_email") }}
                      </small>
                    </div>
                    <div class="col-md-3">
                      <button @click="resentVerifyForForget()" :disabled="!timerBusy" type="button"
                              class="btn btn-sm btn-info w-100">
                        <span v-if="!timerBusy">({{ timer }} s)</span>
                        <span v-if="timerBusy">{{ $t("message.headers.resend") }}</span>
                      </button>
                    </div>
                  </div>
                </fieldset>
              </template>
              <template v-else-if="forget_password_state==3">
                <div class="form-row mb-2">
                  <div class="form-group col-md-6 show_hide_password">
                    <b-form-input class="form-control" id="password"
                                  :placeholder="$t('message.headers.enter_new_password')" type="password"
                                  v-model="$v.formNewPassword.password.$model"
                                  :state="validateState5('password')"></b-form-input>
                    <password v-model="formNewPassword.password" :strength-meter-only="true"/>
                    <b-form-invalid-feedback v-if="!$v.formNewPassword.password.required">
                      {{ $t("message.headers.password") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.formNewPassword.password.minLength">
                      {{ $t("message.headers.password_8_letters") }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-if="!$v.formNewPassword.password.maxLength">
                      {{ $t("message.headers.password_16_letters") }}
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group col-md-6 show_hide_password">
                    <b-form-input class="form-control" id="confirm-password"
                                  :placeholder="$t('message.headers.confirm_password')" type="password"
                                  v-model="$v.formNewPassword.confirmPassword.$model"
                                  :state="validateState5('confirmPassword')"></b-form-input>
                    <b-form-invalid-feedback v-if="!$v.formNewPassword.confirmPassword.sameAsPassword">
                      {{ $t("message.headers.passwords_identical") }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
              </template>
            </div>
            <div class="modal-footer border-top-0">
              <div class="form-row">
                <div class="form-group col-md-3 justify-content-end align-items-center">
                  <button :disabled="timerBusy || forget_busy" type="submit" class="btn btn-danger">
                    <span v-if="forget_busy" class="spinner-border spinner-border-sm" role="status"
                          aria-hidden="true"></span>
                    <span v-if="forget_busy" class="sr-only">{{ $t("message.headers.loading") }}</span>
                    <span v-if="!forget_busy">{{ $t("message.headers.submit") }}</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="form-row justify-content-center">
              <p><span style="color: #aaa">{{ $t("message.headers.have_not_account") }}</span> <a
                  @click="openRegistrationModal"
                  style="cursor: pointer">{{ $t("message.headers.register_now") }}</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="header-wrapper">
      <div class="header-top top-header border-bottom" style="box-sizing: border-box;padding: 0; margin: 0">
        <div class="container">
          <nav class="navbar">
            <div class="wrapper">
              <ul class="nav justify-content-start">
                <li class="nav-item">
                  <a class="nav-link" :href="'mailto:'+generalList.email">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <span> {{ generalList.email }}</span>
                  </a>
                </li>
                <li class="nav-item d-none d-sm-block" style="margin-right: 2px;margin-left: 10px">
                  <a :href="generalList.facebook" v-if="generalList.facebook" target="_blank" class="nav-link px-1"
                     style="color: #3b5998">
                    <i class="fab fa-facebook"></i>
                  </a>
                </li>
                <li class="nav-item d-none d-sm-block">
                  <a :href="generalList.twitter" v-if="generalList.twitter" target="_blank" class="nav-link px-1"
                     style="color: #00ACEE">
                    <i class="fab fa-twitter-square"></i>
                  </a>
                </li>
                <li class="nav-item d-none d-sm-block">
                  <a :href="generalList.instagram" v-if="generalList.instagram" target="_blank" class="nav-link px-1"
                     style="color: #8a3ab9">
                    <i class="fab fa-instagram"></i>
                  </a>
                </li>
                <li class="nav-item d-none d-sm-block">
                  <a :href="generalList.youtube" v-if="generalList.youtube" target="_blank" class="nav-link px-1"
                     style="color: #00aff0">
                    <i class="fab fa-youtube"></i>
                  </a>
                </li>
                <li class="nav-item d-none d-sm-block">
                  <a :href="generalList.google_plus" v-if="generalList.google_plus" target="_blank"
                     class="nav-link px-1" style="color: #0077b5">
                    <i class="fab fa-google-plus"></i>
                  </a>
                </li>
              </ul>
              <ul class="nav justify-content-end">
                <li class="nav-item active">
                  <router-link to="/help-center" style="padding-left: 0" class="nav-link">{{
                      $t("message.headers.help")
                    }} <i class="fa fa-question" style="color: #444;"></i></router-link>
                </li>
                <li class="nav-item dropdown">
                  <a style="padding-left: 0" class="nav-link dropdown-toggle" href="javascript:void(0)"
                     id="navbarDropdown" role="button"
                     data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user" style="color: #444;"></i> {{ $t("message.headers.account") }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="navbarDropdown">
                    <div class="welcome-part overflow-hidden mb-3" v-if="!userAuth">
                      <a class="dropdown-item d-block pb-3 font-weight-bold"
                         href="#">{{ $t("message.headers.welcome_tizaara") }}</a>
                      <a id="sign-up" class="btn active btn-outline-success btn-sm mr-1"
                         style="background: #f05931;border-color: #f05931;"
                         @click="openRegistrationModal">{{ $t("message.headers.join") }}</a>
                      <a id="sign-in" class="btn active btn-outline-success btn-sm"
                         style="background: #f05931;border-color: #f05931;"
                         @click="openLoginModal">{{ $t("message.headers.sign_in") }}</a>
                    </div>
                    <div class="media" v-if="userAuth">
                      <img width="64" height="64" :src="user.photo_type == 0?showImage(user.photo):user.photo"
                           class="mr-3 rounded-circle" alt="...">
                      <div class="media-body">
                        <h5 class="mt-0" style="font-size: 16px">{{ $t("message.headers.welcome") }}<br>
                          <p class="text-ellipsis mb-0" style="max-width: 120px">{{ user.first_name }}
                            {{ user.last_name }}</p></h5>
                        <p class="text-right text-primary p-0 m-0" @click="onLogout"
                           style="font-size: 14px;cursor: pointer">{{ $t("message.headers.sign_out") }}</p>
                      </div>
                    </div>
                    <hr>
                    <router-link v-if="userAuth" to="/dashboard" class="link-2">{{ $t("message.headers.dashboard") }}</router-link>
                    <router-link v-if="userAuth" to="/dashboard/message" class="link-2">{{ $t("message.headers.message_RFQ") }}</router-link>
                    <router-link v-if="userAuth" to="/dashboard/supplier/order" class="link-2">{{ $t("message.headers.my_order") }}</router-link>
                    <router-link to="/my-favorite" class="link-2">{{
                        $t("message.headers.my_favourites")
                      }}
                    </router-link>
                    <router-link v-if="userAuth" to="/dashboard/profile" class="link-2">{{ $t("message.headers.my_account") }}</router-link>
                    <div class="dropdown-divider"></div>
                    <router-link to="/membership-plan" class="link-2">{{
                        $t("message.headers.supplier_membership_plan")
                      }}
                    </router-link>
                    <a href="" class="link-2"><small>{{ $t("message.headers.multipl_quotes") }}</small></a>
                  </div>
                </li>
              </ul>
            </div>
          </nav>

        </div><!-- container end -->
      </div><!-- header-top end -->

      <div class="logo-search-section" style="border-bottom: 2px solid rgba(51, 51, 51, 0.17);">
        <div class="container">
          <div class="row">
            <div class="col-6 col-md-3 col-lg-3 col-xl-3 col-sm-3">
              <router-link to="/">
                <b-img :src="showImage($store.getters.generalList.logo)"
                       v-lazy="showImage($store.getters.generalList.logo)" width="200px" height="70px" class="logo"
                       alt="Logo">
                </b-img>
              </router-link>
            </div>
            <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
              <Search/>
            </div>
            <div class="col-6 col-md-2 col-sm-2 col-lg-2 col-xl-2">
              <div class="add-to-card-wrapper">
                <router-link title="Messages" :to="{name:'Message'}">
                  <i class="far fa-comment-dots"></i> <span
                    class="badge badge-success"
                    style="padding: 3px 4px;margin-left: -3px;margin-top: -4px">{{ this.unseen_message_count }}</span>
                </router-link>
                <a href="javascript:void(0)" title="Orders"><i class="fas fa-gift" style="padding: 3px"></i> <sup
                    style="margin-left: -6px" class="badge badge-success">0</sup></a>
                <router-link :to="{name:'cart.view'}" title="Card">
                  <i class="fas fa-shopping-cart" style="padding: 3px"></i> <sup style="margin-left: -5px"
                                                                                 class="badge badge-success">{{
                    $store.getters.carts.length
                  }}</sup>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState, mapGetters} from "vuex";
import {validationMixin} from "vuelidate";
import {required, maxLength, minLength, sameAs, email} from 'vuelidate/lib/validators';
import Password from 'vue-password-strength-meter';
import VueRecaptcha from 'vue-recaptcha';
import {REGISTER, LOGIN, LOGOUT, VERIFY_AUTH} from "@/core/services/store/auth.module";
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";
import Search from "@/components/layout/inc/Search";
import ContactModal from "../../product/ContactModal";
import {COUNT_UNSEEN_MESSAGE} from "../../../core/services/store/module/message";
import Vue from 'vue'

import Toaster from 'v-toaster'

// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-toaster/dist/v-toaster.css'

// optional set default imeout, the default is 10000 (10 seconds).
Vue.use(Toaster, {timeout: 5000})
export default {
  mixins: [validationMixin],
  name: "Header",
  data() {
    return {
      formReg: new Form({
        account_type: "1",
        first_name: "",
        last_name: "",
        emailOrMobile: "",
        password: "",
        ip_address: "",
        confirmPassword: "",
        termCondition: false,
        robot: false
      }),
      formVerify: new Form({
        username: "nayemislammjm2",
        emailOrMobile: "mdnayemislam890@gmail.com",
        verificationToken: "",
        type: "email",
      }),
      form: {
        emailOrMobile: "",
        password: ""
      },
      termConditionError: false,
      ip_address: "",
      timer: 0,
      timerBusy: false,
      userAuth: false,
      formForget: new Form({
        email: '',
      }),
      formForgetVerify: new Form({
        email: '',
        verificationToken: '',
      }),
      formNewPassword: new Form({
        email: '',
        password: '',
        confirmPassword: '',
      }),
      forget_password_state: 1,
      forget_busy: false,
    };
  },
  validations: {
    formReg: {
      account_type: {
        required
      },
      first_name: {
        required,
        maxLength: maxLength(255)
      },
      last_name: {
        required,
        maxLength: maxLength(255)
      },
      emailOrMobile: {
        required,
        maxLength: maxLength(255)
      },
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(16)
      },
      confirmPassword: {
        sameAsPassword: sameAs('password')
      },
      termCondition: {
        sameAsTerm: sameAs(() => true)
      },
    },
    form: {
      emailOrMobile: {
        required,
        maxLength: maxLength(255)
      },
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(16)
      }
    },
    formForget: {
      email: {
        required,
        email,
        maxLength: maxLength(255)
      },
    },
    formForgetVerify: {
      email: {
        required,
        email,
        maxLength: maxLength(255)
      },
      verificationToken: {
        required,
      }
    },
    formNewPassword: {
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(16)
      },
      confirmPassword: {
        sameAsPassword: sameAs('password')
      },
    },
  },
  created() {
    window.addEventListener('scroll', this.handleScroll);
    this.loadIp();
    Fire.$on('registrationModal', () => {
      this.openRegistrationModal();
    });

    /*unseen message counter*/
    if (this.isAuthenticated) this.$store.dispatch(COUNT_UNSEEN_MESSAGE);
  },
  methods: {
    showImage(e) {
      console.log('url',e)
      return api_base_url + e;
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.formReg[name];
      return $dirty ? !$error : null;
    },
    validateState2(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    validateState3(name) {
      const {$dirty, $error} = this.$v.formForget[name];
      return $dirty ? !$error : null;
    },
    validateState4(name) {
      const {$dirty, $error} = this.$v.formForgetVerify[name];
      return $dirty ? !$error : null;
    },
    validateState5(name) {
      const {$dirty, $error} = this.$v.formNewPassword[name];
      return $dirty ? !$error : null;
    },
    handleScroll() {
      if ($(window).scrollTop() > 10) {
        $(".logo-search-section").css("top", "0");
      } else {
        $(".logo-search-section").css("top", "38px");
      }
    },
    loadIp: function () {
      fetch('https://api.ipify.org?format=json')
          .then(x => x.json())
          .then(ip => this.ip_address = ip.ip);
    },
    openRegistrationModal: function () {
      this.resetForm();
      $('#reg').modal('show');
      $(`#login`).modal('hide');
      $(`#forget_password_modal`).modal('hide');
    },
    verificationTimer() {
      let distance = 60;
      let that = this;
      that.timerBusy = false;
      var x = setInterval(function () {
        if (distance < 1) {
          that.timerBusy = true;
          clearInterval(x);
        }
        that.timer = distance;
        distance -= 1;
      }, 1000)
    },
    verificationResend() {
      this.formVerify.post('user/verify-send')
          .then(() => {
            this.verificationTimer();
          })
          .catch(error => {
            let data = error.response;
            if (data.status === 404) {
              $('#verify').modal('hide');
              swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            }
          })
    },
    verificationSubmit() {
      this.formVerify.post('user/verify')
          .then(({data}) => {
            this.$store.dispatch(REGISTER, data)
                .then(() => {
                  $('#verify').modal('hide');
                  this.$store.dispatch(VERIFY_AUTH);
                })
                .catch((data) => console.log(data));
          })
          .catch(error => {
            let data = error.response;
            if (data.status === 404) {
              $('#verify').modal('hide');
              swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            }
          })
    },
    openVerificationModal: function () {
      this.verificationTimer();
      $('#reg').modal('hide');
      $('#verify').modal('show');
    },
    /*
    * open forget modal
    * */
    openForgetModal: function () {
      //this.resetForm();
      $('#reg').modal('hide');
      $(`#login`).modal('hide');
      $(`#forget_password_modal`).modal('show');
    },

    /*method for submit forget email*/
    submitEmailForForget() {
      this.$v.formForget.$touch();
      if (this.$v.formForget.$anyError) {
        return;
      }
      this.forget_busy = true;
      this.formForget.post('sent/forget/password/request')
          .then((response) => {
            if (response.data.status === 'success') {
              this.forget_password_state = 2;
              this.formForgetVerify.email = this.formForget.email;
              this.formForget.reset();
              this.verificationTimer();
            } else {
              this.$toaster.error(response.data.message);
            }
            this.forget_busy = false;
          })
          .catch((error) => {
            this.$toaster.error(error.response.data);
            this.forget_busy = false;
          })
    },
    /*
    * method for verification token resent
    * */
    resentVerifyForForget() {
      let data = new FormData();
      data.append('email', this.formForgetVerify.email);
      ApiService.post('sent/forget/password/request', data)
          .then((response) => {
            if (response.data.status === 'success') {
              this.$toaster.success(response.data.message);
              this.verificationTimer();
            } else {
              this.$toaster.error(response.data.message);
            }
          })
          .catch((error) => {
            this.$toaster.error(error.response.data);
          })
    },
    /*method for submit with verification code*/
    submitVerifyForForget() {
      this.$v.formForgetVerify.$touch();
      if (this.$v.formForgetVerify.$anyError) {
        return;
      }
      this.forget_busy = true;
      this.formForgetVerify.post('verify/request/verification/token')
          .then((response) => {
            if (response.data.status === 'success') {
              this.forget_password_state = 3;
              this.formNewPassword.email = this.formForgetVerify.email;
              this.formForgetVerify.reset();
            } else {
              this.$toaster.error(response.data.message);
            }
            this.forget_busy = false;
          })
          .catch((error) => {
            this.$toaster.error(error.response.data);
            this.forget_busy = false;
          })
    },
    /*method for submit with new password*/
    submitNewPassForForget() {
      this.$v.formNewPassword.$touch();
      if (this.$v.formNewPassword.$anyError) {
        return;
      }
      this.forget_busy = true;
      this.formNewPassword.post('request/set/new/password')
          .then((response) => {
            if (response.data.status === 'success') {
              this.forget_password_state = 1;
              this.formNewPassword.reset();
              this.$toaster.success(response.data.message);
              $('#reg').modal('hide');
              $(`#forget_password_modal`).modal('hide');
              $(`#login`).modal('show');
            } else {
              this.$toaster.error(response.data.message);
            }
            this.forget_busy = false;
          })
          .catch((error) => {
            this.$toaster.error(error.response.data);
            this.forget_busy = false;
          })
    },
    findSubmitMethod() {
      if (this.forget_password_state == 1) this.submitEmailForForget();
      else if (this.forget_password_state == 2) this.submitVerifyForForget();
      else if (this.forget_password_state == 3) this.submitNewPassForForget();
    },
    openLoginModal: function () {
      this.resetForm();
      $('#reg').modal('hide');
      $(`#login`).modal('show');
    },
    resetForm() {
      this.formReg.reset();
      this.formReg.clear();
      this.termConditionError = false;
      this.form = {
        emailOrMobile: null,
        password: null
      };
      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    onSubmit() {
      this.$v.formReg.$touch();
      this.termConditionError = this.$v.formReg.termCondition.$anyError;
      if (this.$v.formReg.$anyError) {
        return;
      }
      this.formReg.ip_address = this.ip_address;
      this.formReg.post('user/register')
          .then(({data}) => {
            this.formVerify.reset();
            this.formVerify.fill(data);
            this.openVerificationModal();
          })
          .catch(error => {
            let data = error.response;
            if (data.status === 500) {
              $('#reg').modal('hide');
              swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            }
          })
    },
    loginSubmit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }

      const emailOrMobile = this.$v.form.emailOrMobile.$model;
      const password = this.$v.form.password.$model;

      this.$store.dispatch(LOGOUT);

      this.$store.dispatch(LOGIN, {emailOrMobile, password})
          .then(() => {
            this.$store.dispatch(VERIFY_AUTH);
            $(`#login`).modal('hide');
          })
          .catch((data) => console.log(data));

    },
    onLogout() {
      this.$store.dispatch(LOGOUT)
    },
    loginWithGoogle() {
      $('#reg').modal('hide');
      $('#login').modal('hide');
      this.$gAuth.signIn()
          .then(GoogleUser => {
            let userInfo = {
              loginType: 'google',
              token: GoogleUser.getAuthResponse().id_token
            }

            ApiService.post('user/login-google', userInfo)
                .then(({data}) => {
                  this.$store.dispatch(REGISTER, data)
                      .then(() => {
                        this.$store.dispatch(VERIFY_AUTH);
                      })
                      .catch((data) => console.log(data));
                })
                .catch(error => {
                  let data = error.response;
                  if (data.status === 404) {
                    $('#verify').modal('hide');
                    swal.fire("Failed!", data.response.data.error, 'warning')
                  } else if (data.status === 404) {
                    swal.fire(this.$t("message.common.something_wrongs"), data.response.data.error, 'warning')
                  } else {
                    swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
                  }
                })
          })
          .catch(error => {
            console.log('error', error)
          })

    },
    async loginWithFacebook(){
      $('#reg').modal('hide');
      $('#login').modal('hide');
      const { authResponse } = await new Promise(FB.login);
      if (!authResponse) return;
      let userInfo = {
        loginType: 'google',
        token: authResponse.accessToken
      }
      await ApiService.post(`user/login-facebook`, userInfo)
          .then(({data}) => {
            this.$store.dispatch(REGISTER, data)
                .then(() => {
                  this.$store.dispatch(VERIFY_AUTH);
                })
                .catch((data) => console.log(data));
          })
          .catch(error => {
            let data = error.response;
            if (data.status === 404) {
              $('#verify').modal('hide');
              swal.fire("Failed!", data.response.data.error, 'warning')
            } else if (data.status === 404) {
              swal.fire(this.$t("message.common.something_wrongs"), data.response.data.error, 'warning')
            } else {
              swal.fire(this.$t("message.common.error"), this.$t("message.common.something_wrong"), 'warning')
            }
          })
    }
  },
  components: {Password, 'vue-recaptcha': VueRecaptcha, Search, ContactModal},
  computed: {
    ...mapGetters([
      "isAuthenticated", "unseen_message_count", "generalList"
    ]),
    ...mapState({
      errors: state => state.auth.errors
    }),
    user() {
      return this.$store.getters.currentUser;
    },
  },
  watch: {
    user: function () {
      this.userAuth = !!this.isAuthenticated;
    }
  }
}
</script>

<style scoped>
.top-header {
  background-color: whitesmoke;
  box-shadow: inset 0 -1px 0 #33333352;
}

ul {
  list-style-type: none;
}

.navbar {
  padding: 1px;
}

.navbar, ul {
  display: flex;
  flex-direction: row;
}

ul li {
  padding: 0 1px;
}

@media (max-width: 800px) {
  ul li {
    padding: 0 0;
  }
}

.wrapper {
  display: flex;
  justify-content: space-between;
  width: 100%;
}
</style>
