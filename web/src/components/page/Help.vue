<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="container">
        <div class="row">
          <h5 class="font-weight-bold" style="color: #7e7474">{{ $t("message.help.help_center") }}</h5>
        </div>
      </div>


      <b-container style="color: #7e7474">
        <b-row>
          <b-col cols="12" class="mb-4">
            <p class="text-center" style="text-decoration: underline;text-decoration-color: #f05931;">{{ $t("message.help.top_question") }}</p>
          </b-col>

          <b-col v-for="(question, k) in getTopQuestion" cols="12" md="4" class="mb-3" :key="k">
            <router-link :to="{name: 'help-category', query: { qu: question.slug}}"><i class="fas fa-question-circle font-12"></i> {{question.question}}</router-link>
          </b-col>

        </b-row>
      </b-container>

      <b-container class="mt-5" style="color: #7e7474">
        <b-row>
          <b-col cols="12" class="mb-4">
            <p class="text-center" style="text-decoration: underline;text-decoration-color: #f05931;">{{ $t("message.help.categories") }}</p>
          </b-col>
          <b-col v-for="(category, k) in helpCategoryList" sm="12" md="4" lg="3" class="mb-3" :key="k">
            <router-link :to="{name: 'help-category', query: { cat: category.slug}}">
              <b-card class="p-2 background-change">
                <div>
                  <b-img center :src="showImage(category.icon)" width="40" height="40" rounded="circle"
                         alt="Center image"></b-img>
                </div>
                <p class="text-center mt-2">
                  {{ category.name }}
                </p>
              </b-card>
            </router-link>
          </b-col>


        </b-row>
      </b-container>

    </div>
  </div>
</template>

<script>

import {HELP_CATEGORY_LIST, HELP_QUESTION} from "@/core/services/store/module/help";
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";

export default {
  name: "Help",
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
  },
  created() {
    this.$store.dispatch(HELP_CATEGORY_LIST)
    this.$store.dispatch(HELP_QUESTION)
  },
  computed: {
    ...mapGetters(["helpCategoryList", "getTopQuestion"])
  },
}
</script>

<style scoped>
.font-12 {
  font-size: 12px;
  color: #f05931;
}

.background-change:hover {
  background-color: #f5f6fc;
}
</style>