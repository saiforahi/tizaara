<template>
  <div class="card">
    <div class="card-header">
      Conversation
    </div>
    <div class="card-body">
      <div class="container">
        <div class="row">
          <div class="col-4 p-md-4">
            <img :src="showImage(personalInfo.photo)" alt="" class="img-fluid rounded">
          </div>
          <div class="col-8 p-md-4">
            <blockquote>
              <h5>{{ personalInfo.full_name }}</h5>
<!--              <small>
                <cite title="Source Title">
                  {{ addressFilter(getDivisionNameById(personalInfo.division), 'name') }}
                  <i class="fas fa-map-marker-alt"></i>
                </cite>
              </small>-->
            </blockquote>
            <p>
              <i class="fas fa-envelope"></i> {{ personalInfo.email }} <br>
              <i class="fas fa-phone"></i> {{ personalInfo.mobile }} <br>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {api_base_url} from "@/core/config/app";
import {mapGetters} from "vuex";

export default {
  name: "SiderPanel",
  props: {
    selected: {
      type: Object,
      required: true
    }
  },
  computed: {
    ...mapGetters(["getDivisionNameById", "countryList"]),
    user() {
      return this.$store.getters.currentUser;
    },
    personalInfo() {
      return this.selected?.conversation?.participants.find(e => e.messageable_id != this.user?.id)?.messageable
    },
  },
  watch: {
    selected() {
    }
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    addressFilter(data, key) {
      return data ? data[key] : '';
    }
  }
}
</script>

<style scoped>

</style>
