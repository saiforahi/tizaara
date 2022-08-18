<template>
  <div class="offer-counter">
    <span>{{ days }}</span> :
    <span>{{ hours }}</span> :
    <span>{{ minutes }}</span> :
    <span>{{ seconds }}</span>
  </div>
</template>

<script>
export default {
  name: "Timer",
  props: ['starttime', 'endtime', 'trans'],
  data: function () {
    return {
      timer: "",
      wordString: {},
      start: "",
      end: "",
      interval: "",
      days: "",
      minutes: "",
      hours: "",
      seconds: "",
      message: "",
      statusType: "",
      statusText: "",

    };
  },
  created: function () {
    this.wordString = JSON.parse(this.trans);
  },
  mounted() {
    this.start = new Date(this.starttime).getTime();
    this.end = new Date(this.endtime).getTime();
    this.timerCount(this.start, this.end);
    this.interval = setInterval(() => {
      this.timerCount(this.start, this.end);
    }, 1000);
  },
  methods: {
    timerCount: function (start, end) {
      var now = new Date().getTime();

      // Find the distance between now an the count down date
      var distance = start - now;
      var passTime = end - now;

      if (distance < 0 && passTime < 0) {
        this.message = this.wordString.expired;
        this.statusType = "expired";
        this.statusText = this.wordString.status.expired;
        clearInterval(this.interval);
        return;

      } else if (distance < 0 && passTime > 0) {
        this.calcTime(passTime);
        this.message = this.wordString.running;
        this.statusType = "running";
        this.statusText = this.wordString.status.running;

      } else if (distance > 0 && passTime > 0) {
        this.calcTime(distance);
        this.message = this.wordString.upcoming;
        this.statusType = "upcoming";
        this.statusText = this.wordString.status.upcoming;
      }
    },
    calcTime: function (dist) {
      // Time calculations for days, hours, minutes and seconds
      this.days = ("0" + Math.floor(dist / (1000 * 60 * 60 * 24))).slice(-2);
      this.hours = ("0" + Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).slice(-2);
      this.minutes = ("0" + Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60))).slice(-2);
      this.seconds = ("0" + Math.floor((dist % (1000 * 60)) / 1000)).slice(-2);
    }
  }
}
</script>

<style scoped>
span {
  font-size: 18px!important;
}
</style>