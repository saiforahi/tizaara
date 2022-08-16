<template>
  <router-view></router-view>
</template>

<script>
import {GENERAL_LIST} from "@/core/services/store/general.module";

export default {
  name: "Auth",
  created() {
    this.$store.dispatch(GENERAL_LIST)
  },
}
</script>

<style scoped>

</style>
