<template>
  <aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
      <div id="main-menu" class="main-menu collapse navbar-collapse">
        <ul class="nav navbar-nav" v-if="$store.getters.currentUser.status == 2">
          <li :class="$route.name === 'Dashboard'?'active':''">
            <router-link to="/dashboard"><i class="menu-icon fa fa-laptop"></i>{{ $t("message.sidebar.dashboard") }}</router-link>
          </li>
          <li :class="$route.name === 'Message'?'active':''">
            <router-link to="/dashboard/message"><i class="menu-icon fas fa-sms"></i>{{ $t("message.sidebar.message") }}</router-link>
          </li>
          <li :class="$route.name === 'Profile'?'active':''">
            <router-link to="/dashboard/profile"><i class="menu-icon fas fa-user"></i>{{ $t("message.sidebar.profile") }}</router-link>
          </li>
          <li :class="$route.name === 'password.change'?'active':''">
            <router-link :to="{name:'password.change'}"><i class="menu-icon fas fa-lock"></i>{{ $t("message.sidebar.password_change") }}</router-link>
          </li>
          <template v-if="isSupplier">
            <li class="menu-title">{{ $t("message.sidebar.supplier") }}</li>
            <li class="menu-item-has-children dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i
                  class="menu-icon fa fa-shopping-cart"></i>{{ $t("message.sidebar.product") }}</a>
              <ul class="sub-menu children dropdown-menu">
                <li>
                  <i class="far fa-list-alt"></i>
                  <router-link to="/dashboard/product-list">{{ $t("message.sidebar.product_list") }}</router-link>
                </li>
                <li>
                  <i class="fas fa-cart-plus"></i>
                  <router-link to="/dashboard/product-create">{{ $t("message.sidebar.post_product") }}</router-link>
                </li>
              </ul>
            </li>
            <li :class="$route.name === 'dashboard.supplier.order'?'active':''">
              <router-link :to="{name:'dashboard.supplier.order'}">
                <i class="menu-icon fas fa-cart-plus"></i>
                {{ $t("message.sidebar.orders") }}
              </router-link>
            </li>
            <li class="menu-item-has-children dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i
                  class="menu-icon fas fa-bullhorn"></i>{{ $t("message.sidebar.marketing") }}</a>
              <ul class="sub-menu children dropdown-menu">
                <li>
                  <i class="fas fa-mail-bulk"></i>
                  <router-link to="/dashboard/quotation">{{ $t("message.sidebar.quotation") }}</router-link>
                </li>
              </ul>
            </li>
            <li :class="$route.name === 'dashboard.membership.plan'?'active':''">
              <router-link :to="{name:'dashboard.membership.plan'}">
                <i class="menu-icon fas fa-user-shield"></i>
                {{ $t("message.sidebar.membership_plan") }}
              </router-link>
            </li>
            <li :class="$route.name === 'dashboard.verify.supplier'?'active':''">
              <router-link :to="{name:'dashboard.verify.supplier'}">
                <i class="menu-icon fa fa-check-circle" aria-hidden="true"></i>
                {{ $t("message.sidebar.verify_me") }}
              </router-link>
            </li>
          </template>
          <template>
            <li v-if="isBuyer" class="menu-title">{{ $t("message.sidebar.buyer") }}</li>
            <li :class="$route.name === 'dashboard.buyer.order'?'active':''">
              <router-link :to="{name:'dashboard.buyer.order'}">
                <i class="menu-icon fas fa-cart-plus"></i>
                {{ $t("message.sidebar.orders") }}
              </router-link>
            </li>
            <li :class="$route.name === 'dashboard.buyer.quotation'?'active':''">
              <router-link :to="{name:'dashboard.buyer.quotation'}">
                <i class="menu-icon fa fa-quote-right" aria-hidden="true"></i>
                {{ $t("message.sidebar.quotation") }}
              </router-link>
            </li>
<!--            <li :class="$route.name === 'dashboard.buyer.transaction'?'active':''">
              <router-link :to="{name:'dashboard.buyer.transaction'}">
                <i class="menu-icon fas fa-dolly-flatbed"></i>
                {{ $t("message.sidebar.transaction") }}
              </router-link>
            </li>-->
          </template>

        </ul>
      </div>
    </nav>
  </aside>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "Sidebar",
  computed: {
    ...mapGetters(["isSupplier", "isBuyer"])
  },
}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>
