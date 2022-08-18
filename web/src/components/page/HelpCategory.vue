<template>
  <div class="product-list-wrapper" style="min-height: 80vh;font-family: 'Noto Sans JP', sans-serif;font-size: 12px">
    <div class="container my-4">
      <div class="row">
        <h5 class="font-weight-bold" style="color: #7e7474">{{ $t("message.help.help_center") }}</h5>
      </div>
    </div>

    <b-container>
      <b-row>
        <b-col cols="12" md="4" xl="3">
          <div class="sidebar-item-wrapper">
            <div class="collapse show" id="related-category">
              <ul>
                <li v-for="(category, k) in helpCategoryList" :key="k">
                  <router-link :to="{name: 'help-category', query: { cat: category.slug}}" class="font-weight-bold">
                    {{ category.name }}
                  </router-link>
                  <ul class="pl-2">
                    <li v-for="(subcategory, i) in getHelpSubcategoryById(category.id)" :key="i">
                      <router-link
                          :to="{name: 'help-category', query: { sub: subcategory.slug}}">{{ subcategory.name }}
                      </router-link>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </b-col>
        <b-col cols="12" md="8" xl="9">
          <div style="background-color: white" class="py-2 px-4">
            <div class="border-bottom pb-2">
              <router-link to="/">{{ $t("message.help.home") }}</router-link>
              <router-link v-if="category !== ''" :to="{name: 'help-category', query: { cat: category.slug}}"><i
                  class="fas fa-chevron-right mx-2"></i>
                {{ category.name }}
              </router-link>
              <router-link v-if="subcategory !== ''" :to="{name: 'help-category', query: { sub: subcategory.slug}}"><i
                  class="fas fa-chevron-right mx-2"></i>
                {{ subcategory.name }}
              </router-link>
              <router-link v-if="question !== ''" :to="{name: 'help-category', query: { qu: question.slug}}"><i
                  class="fas fa-chevron-right mx-2"></i>
                {{ question.question }}
              </router-link>
            </div>
            <div>
              <div v-if="child_position === 1 && subcategory_list.length > 0" class="py-2 my-2"
                   style="background-color: #f0f0fa">
                <router-link v-for="(subcategory, k) in subcategory_list" class="text-success mx-3"
                             :to="{name: 'help-category', query: { sub: subcategory.slug}}" :key="k">
                  {{ subcategory.name }}
                </router-link>
              </div>
              <div v-if="child_position !== 3" class="py-2 my-4">
                <div v-for="(question, k) in question_list" :key="k" class="mb-2"><router-link
                             :to="{name: 'help-category', query: { qu: question.slug}}">
                  <i class="fas fa-question-circle text-success"></i> {{ question.question }}
                </router-link></div>
              </div>
              <div v-if="child_position === 3" class="deepselectors" v-html="question.answer"></div>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>


  </div>
</template>

<script>
import {HELP_CATEGORY_LIST, HELP_QUESTION, HELP_SUBCATEGORY_LIST} from "@/core/services/store/module/help";
import {mapGetters} from "vuex";

export default {
  name: "HelpCategory",
  data() {
    return {
      category: '',
      subcategory: '',
      subcategory_list: [],
      question: '',
      question_list: [],
      child_position: '',
    }
  },
  methods: {
    loadQuery() {
      if (this.$route.query.qu) {
        if (this.helpQuestionList.length > 0) {
          this.child_position = 3;
          let question = this.getQuestionBySlug(this.$route.query.qu);
          if (question) {
            this.question = question;
            this.loadCategory();
            this.loadSubcategory();
          } else this.$router.push({name: "error"});
        }
      } else {
        if (this.$route.query.sub) {
          if (this.helpQuestionList.length > 0) {
            this.child_position = 2;
            let subcategory = this.getHelpSubcategoryBySlug(this.$route.query.sub);
            if (subcategory) {
              this.question = '';
              this.category = '';
              this.subcategory = subcategory;
              this.loadCategory();
              this.loadQuestion();
            } else this.$router.push({name: "error"});
          }
        } else {
          if (this.helpCategoryList.length > 0) {
            this.child_position = 1;
            let category = this.getHelpCategoryBySlug(this.$route.query.cat);
            if (category) {
              this.question = '';
              this.subcategory = '';
              this.category = category;
              this.loadSubcategory();
              this.loadQuestion();
            } else this.$router.push({name: "error"});
          }
        }
      }
    },
    loadCategory() {
      if (this.helpCategoryList.length > 0) {
        if (this.child_position === 2) {
          this.category = this.findHelpCategoryById(this.subcategory.category_id);
        }
        if (this.child_position === 3) {
          this.category = this.findHelpCategoryById(this.question.category_id);
        }
      }
    },
    loadSubcategory() {
      if (this.helpSubcategoryList.length > 0) {
        if (this.child_position === 1) {
          this.subcategory_list = this.getHelpSubcategoryById(this.category.id)
        }
        if (this.child_position === 3) {
          this.subcategory = this.findHelpSubcategoryById(this.question.subcategory_id)
        }
      }
    },
    loadQuestion() {
      if (this.helpQuestionList.length > 0) {
        if (this.child_position === 1) {
          this.question_list = this.getQuestionByCategory(this.category.id)
        }
        if (this.child_position === 2) {
          this.question_list = this.getQuestionBySubcategory(this.subcategory.id)
        }
      }
    }
  },
  created() {
    this.loadQuery();
    this.helpCategoryList.length < 1 ? this.$store.dispatch(HELP_CATEGORY_LIST) : '';
    this.helpSubcategoryList.length < 1 ? this.$store.dispatch(HELP_SUBCATEGORY_LIST) : '';
    this.helpQuestionList.length < 1 ? this.$store.dispatch(HELP_QUESTION) : '';
  },
  computed: {
    ...mapGetters(["helpCategoryList", "getTopQuestion", "helpQuestionList", "getHelpSubcategoryById", "helpSubcategoryList", "getQuestionBySlug", "findHelpCategoryById",
      "findHelpSubcategoryById", "getHelpSubcategoryBySlug", "getHelpCategoryBySlug", "getQuestionByCategory", "getQuestionBySubcategory"])
  },
  watch: {
    helpQuestionList() {
      this.loadQuery();
      this.loadQuestion();
    },
    helpSubcategoryList() {
      this.loadSubcategory();
    },
    helpCategoryList() {
      this.loadCategory();
    },
    '$route.query'() {
      this.loadQuery();
    },
  }
}
</script>

<style scoped>

.deepselectors >>> img {
  width: 100%;
}
</style>