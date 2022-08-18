<template>
  <div>
    <div class="weekly-market-wrapper" v-for="(category, k) in homeCategoryProduct" :key="k">
      <div class="container">
        <div class="grid-bg-pad">
          <div class="row">
            <h2 class="page-title"><a href="">{{ category.name }}</a></h2>
          </div>
          <div class="row">
            <div class="col-sm-3 mb-3">
              <div class="custom-feature-grid item-1 rounded"
                   v-bind:style="{ backgroundImage: 'url(' + showImage(category.banner) + ')' }" style="height: 100%; background-position: center;background-repeat: no-repeat;
background-size: cover">
                <h4>{{ category.title }}</h4>
                <router-link :to="'category/'+category.slug" class="btn-more btn-more-bottom border ml-5">
                  {{ $t("message.cat_product.view_more") }}
                </router-link>
              </div>
            </div>
            <div class="col-sm-9">
              <div class="row" v-if="getSubcategoryById(category.id).length > 0">
                <div class="col-sm-6 mb-3" v-for="subcategories in getSubcategoryById(category.id).slice(0, 6)"
                     :key="subcategories.id">
                  <b-card class="h-100 w-100 d-inline-block p-2">
                    <b-media>
                      <template #aside>
                        <b-img style="padding: 5px; width: 100px; margin-right: 10px" v-lazy="showImage(subcategories.banner)"
                               alt="placeholder"></b-img>
                      </template>
                      <h6 class="mt-0" style="font-size: 1.1em">
                        <router-link :to="'category/'+category.slug+'/'+subcategories.slug">{{
                            subcategories.name
                          }}
                        </router-link>
                      </h6>
                      <router-link style="font-size: .9em"
                                   :to="'category/'+category.slug+'/'+subcategories.slug+'/'+subsubcategories.slug"
                                   v-for="subsubcategories in getSubsubcategoryById(subcategories.id).slice(0,5)"
                                   :key="subsubcategories.id">
                        {{ subsubcategories.name }}<br></router-link>
                    </b-media>
                  </b-card>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";

export default {
  name: "Cat_product",
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    jsonDecode(e) {
      return JSON.parse(e);
    },
  },
  computed: {
    ...mapGetters(["homeCategoryProduct", "getSubcategoryById", "getSubsubcategoryById"])
  },
}
</script>

<style scoped>

</style>