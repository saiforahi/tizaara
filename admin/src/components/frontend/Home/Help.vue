<template>
  <b-tabs pills card active-nav-item-class="bg-success">
    <b-tab title="Help Category" active>
      <Category/>
    </b-tab>
    <b-tab title="Help Subcategory">
      <Subcategory/>
    </b-tab>
    <b-tab title="Questions">
      <Question/>
    </b-tab>
  </b-tabs>
</template>

<script>
import Category from "@/components/frontend/Home/help/Category";
import Subcategory from "@/components/frontend/Home/help/Subcategory";
import Question from "@/components/frontend/Home/help/Question";

export default {
  name: "Help",
  components: {Category, Subcategory, Question},
}
</script>

<style scoped>

</style>