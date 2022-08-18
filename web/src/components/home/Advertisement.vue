<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-4" v-if="getAdvertisementById(3)">
          <a :target="getAdvertisementById(3).url? '_blank':''"
             :href="getAdvertisementById(3).url? getAdvertisementById(3).url : 'javascript:void(0)'">
            <b-img class="w-100 my-2 img-thumbnail" :src="showImage(getAdvertisementById(3).banner)"
                   v-lazy="showImage(getAdvertisementById(3).banner)"
                   alt="placeholder"></b-img>
          </a>
        </div>
        <div class="col-4" v-if="getAdvertisementById(4)">
          <a :target="getAdvertisementById(4).url? '_blank':''"
             :href="getAdvertisementById(4).url? getAdvertisementById(4).url : 'javascript:void(0)'">
            <b-img class="w-100 my-2 img-thumbnail" :src="showImage(getAdvertisementById(4).banner)"
                   v-lazy="showImage(getAdvertisementById(4).banner)"
                   alt="placeholder"></b-img>
          </a>
        </div>
        <div class="col-4" v-if="getAdvertisementById(5)">
          <a :target="getAdvertisementById(5).url? '_blank':''"
             :href="getAdvertisementById(5).url? getAdvertisementById(5).url : 'javascript:void(0)'">
            <b-img class="w-100 my-2 img-thumbnail" :src="showImage(getAdvertisementById(5).banner)"
                   v-lazy="showImage(getAdvertisementById(5).banner)"
                   alt="placeholder"></b-img>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";

export default {
  name: "Advertisement",
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
  },
  computed: {
    ...mapGetters(["getAdvertisementById"])
  },
}
</script>

<style scoped>

</style>