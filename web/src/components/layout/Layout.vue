<template>
  <div>
    <Header/>
    <router-view :key="$route.path"></router-view>
    <Footer/>
  </div>
</template>

<script>
import Header from './inc/Header'
import Footer from './inc/Footer'

export default {
  name: "Layout",
  components: {
    Header,
    Footer
  }
}
</script>

<style scoped>

</style>
