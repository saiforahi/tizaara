<template>
  <div class="weekly-market-wrapper our-brand-wrapper">
    <div class="container">
      <div class="grid-bg-pad">
        <div class="row">
          <h2 class="page-title">{{ $t("message.testimonial.our_happy_customer") }}</h2>
        </div>

        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <div v-for="(testimonial, index) in getTestimonial" :key="index" :class="index===0?'carousel-item active':'carousel-item'">
              <div class="row">
                <div class="col-md-5 ml-md-5 ml-sm-0 col-sm-6 verticalLine">
                  <h4>{{ testimonial.name }}</h4>
                  <h4 class="subheading">{{ testimonial.profession }}</h4>
                  <p class="text-muted" v-html="testimonial.message"></p>
                </div>
                <div class="col-md-4 col-sm-6 how-img">
                  <video-embed ref="youtube" :src="testimonial.video"
                               style="height: 350px; border-radius: 15px"></video-embed>
                </div>
              </div>
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" style="color: #f1c40f">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <div>
          <h4>{{ $t("message.testimonial.more_review") }}</h4>
          <hooper :itemsToShow="4">
            <slide v-for="(testimonial, index) in getTestimonial" :key="index" :index="index">
              <video-embed :ref="'youtube'+index" :src="testimonial.video"
                           class="slide-video"></video-embed>
            </slide>
          </hooper>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {TESTIMONIAL_LIST} from "@/core/services/store/module/testimonial";
import {Hooper, Slide} from 'hooper';
import 'hooper/dist/hooper.css';

export default {
  name: "Testimonial",
  created() {
    this.getTestimonial.length < 1 ? this.$store.dispatch(TESTIMONIAL_LIST) : '';
  },
  computed: {
    ...mapGetters(["getTestimonial", "getSingleTestimonial"])
  },
  components: {Hooper, Slide}
}
</script>

<style scoped>
.slide-video{
  padding: 5px;
  border-radius: 10px;
}
.hooper-slide {
  margin: 10px;
}
.verticalLine {
  border-left: thick solid #ff0000;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
  height: 50px;
  width: 50px;
  outline: black;
  background-size: 50%, 50%;
  border-radius: 50%;
  border: 1px solid black;
  background-image: none;
}

.carousel-control-next-icon:after
{
  content: '>';
  font-size: 35px;
  color: red;
}

.carousel-control-prev-icon:after {
  content: '<';
  font-size: 35px;
  color: red;
}
</style>