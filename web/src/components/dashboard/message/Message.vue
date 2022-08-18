<template>
  <div ref="messages" class="messages">
    <ul>
      <infinite-loading direction="top" @infinite="infiniteHandler">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
      <li v-for="(item, key) in messages" :class="item.sender.id != user.id?'sent':'replies'" :key="key">
        <img :src="showImage(item.sender.photo)" alt=""/>
        <div v-if="item.type =='product'">
          <span hidden>{{ msg = JSON.parse(item.body) }}</span>
          <div @click="loadSingleProduct(msg.slug)" style="border-radius: 6%;" class="card" :style="item.sender.id != user.id?'float: left;cursor: pointer':'float: right;cursor: pointer'">
            <img class="card-img-top img img-fluid" :src="showImage(msg.thumbnail_img)" alt="" style="width: 200px; border-radius: 0%" >
            <div class="card-body">
              <span class="card-text">{{msg.name}}</span><br>
              <span v-if="JSON.parse(msg.colors).length>0" class="card-text">{{'Colors: '+JSON.parse(msg.colors)}}</span><br>
              <span v-if="msg.brand" class="card-text">{{'Brand :'+ msg.brand.name }}</span>
            </div>
          </div>
        </div>
        <p v-if="item.type == 'text'">{{ item.body }}</p>
        <img v-else-if="item.type == 'image'" class="img img-fluid" :src="item.body" style="width: 200px; border-radius: 0%" />
      </li>
    </ul>
  </div>
</template>
<script>

import ApiService from "@/core/services/api.service";
import {api_base_url} from "@/core/config/app";
import {MAKE_MESSAGE_EMPTY, UPDATE_MESSAGES} from "../../../core/services/store/module/message";

export default {
  props: {
    selected: {
      type: Object,
      required: true
    },
    message_type:{
      required: true,
    }
  },
  data() {
    return {
      page: 1,
      message: ''
    }
  },
  methods: {
    /*
    * method for single product load in new tab
    * */
    loadSingleProduct(slug){
      window.open("/single/product/"+slug, "_blank");
    },
    showImage(e) {
      return api_base_url + e;
    },
    infiniteHandler($state) {
      ApiService.get(`user/message/${this.selected.id}/message`, '', {
        params: {
          page: this.page,
        },
      }).then(({data}) => {
        if (data.data.data.length) {
          this.page += 1;
          //console.log(data.data.data);
          data.data.data.forEach(item=>{
            this.$store.dispatch(UPDATE_MESSAGES,item);
          });
          //this.$store.dispatch(UPDATE_MESSAGES,...data.data.data);
          //this.messages.push(...data.data.data);
          $state.loaded();
          this.scrollToEnd();
        } else {
          $state.complete();
        }
      });
    },
    scrollToEnd() {
      let messages = this.$refs.messages;
      messages.scrollTop = messages.scrollHeight
    }
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    },
    messages() {
      let msg = this.$store.getters.messages
      msg = msg.map(item=>{
        if (item.sender.id == this.user.id && item.message_type === this.message_type) return item;
        else if(item.sender.id != this.user.id && item.message_type !== this.message_type) return item;
      }).filter(Boolean);
      return msg;
    },
    receiver() {
      return this.selected?.conversation?.participants.find(e => e.messageable_id !== this.user?.id)?.messageable
    }
  },
  watch: {
    selected() {
      this.$store.dispatch(MAKE_MESSAGE_EMPTY);
      this.page = 1;
      //this.messages = [];
      this.message = '';
      this.infiniteHandler();
    },
    messages() {
      this.$nextTick(() => {
        this.scrollToEnd();
      })
    }
  },
}
</script>
<style lang="scss" scoped>
@import 'src/assets/scss/message';
</style>