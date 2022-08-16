<template>
  <CHeader fixed with-subheader light>
    <CToggler
        in-header
        class="ml-3 d-lg-none"
        @click="$store.commit('toggleSidebarMobile')"
    />
    <CToggler
        in-header
        class="ml-3 d-md-down-none"
        @click="$store.commit('toggleSidebarDesktop')"
    />
    <CHeaderBrand class="mx-auto d-lg-none" to="/">
      <CIcon name="logo" height="48" alt="Logo"/>
    </CHeaderBrand>
    <CHeaderNav class="d-md-down-none mr-auto">

    </CHeaderNav>
    <CHeaderNav class="mr-4">
      <CDropdown
          inNav
          class="c-header-nav-items"
          placement="bottom-end"
          add-menu-classes="pt-0"
      >
        <template #toggler>
          <CHeaderNavLink>
            <div class="c-avatar">
              <img src="@/assets/image/avatars/6.jpg"
                  class="c-avatar-img "
              /> <span class="mx-3">{{$t("message.header.admin")}}</span>
            </div>
          </CHeaderNavLink>
        </template>
<!--          <CDropdownItem @click="pageRedirect">
            <CIcon name="cil-user"/>
            {{$t("message.header.profile")}}
          </CDropdownItem>-->
        <CDropdownItem @click="openPasswordModal">
          <CIcon name="cil-settings"/>
          {{$t("message.header.change_password")}}
        </CDropdownItem>
        <CDropdownDivider/>
        <CDropdownItem @click="onLogout">
          <CIcon name="cil-lock-locked"/>
          {{$t("message.header.logout")}}
        </CDropdownItem>
      </CDropdown>
    </CHeaderNav>

    <!-- Modal -->
    <b-modal id="passWordChangeModal" title="Password change"
             hide-footer>
      <form action="javascript:void(0)" method="post" ref="password_change_form" @submit="changePass">
        <CCol md="12">
          <label>Current password</label>
        <b-form-input name="password" type="password" v-model="form.old_password" label="Current password" placeholder="enter current password" required></b-form-input>
        </CCol>
        <CCol md="12">
          <label>New password</label>
          <b-form-input name="new_password" type="password" v-model="form.password" label="new password" placeholder="enter new password" required></b-form-input>
        </CCol>
        <CCol md="12" class="mb-3">
          <label>Confirm password</label>
          <b-form-input name="conform_password" type="password" v-model="form.password_confirmation" label="Conform password" placeholder="enter confirm password" required></b-form-input>
        </CCol>
        <CRow class="justify-content-end">
          <button type="submit" class="btn btn-primary mr-2">Submit</button>
          <button @click="$bvModal.hide('passWordChangeModal')" type="button" class="btn btn-secondary mr-2">Close</button>
        </CRow>
      </form>
    </b-modal>
    <!-- End Modal -->

  </CHeader>
</template>

<script>
import { LOGOUT } from "@/core/services/store/auth.module";
import Vue from 'vue'
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'
Vue.use(Toaster, {timeout: 5000})
export default {
  name: 'Header',
  components: {},
  data(){
    return{
      form:new Form({
        password:'',
        old_password:'',
        password_confirmation:'',
        busy:false,
      })
    }
  },
  methods: {
    pageRedirect(){
      this.$router.push({name:'admin-profile'});
    },
    onLogout() {
      this.$store
          .dispatch(LOGOUT)
          .then(() => this.$router.push({ name: "login" }));
    },
    openPasswordModal(){
      this.$bvModal.show('passWordChangeModal');
    },
    changePass(){
      this.form.post('admin/password/change/request').then((response)=>{
        if (response.data.status ==='success'){
          this.$refs.password_change_form.reset();
          this.$toaster.success(response.data.message);
        }else{
          this.$toaster.error(response.data.message);
        }

      }).catch((error)=>{
        if (error.response.status ==422) this.$toaster.error(error.response.data.errors.password);
        else this.$toaster.success(error.response.data.message);
      })
    }
  },
}
</script>
