<template>
  <div class="message-input">
    <div class="wrap">
      <input v-model="message" type="text" :placeholder = "$t('message.message.write_your_message')"
             @keydown.enter="submitMessage"/>
      <input
          ref="browsePhoto"
          type="file"
          accept="image/*"
          style="display: none;"
          @change="handleFileUpload"
      >
      <i class="fa fa-paperclip attachment" aria-hidden="true" @click="$refs.browsePhoto.click()"></i>
      <button class="submit" @click="submitMessage"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
    </div>
  </div>
</template>
<script>
import ApiService from "@/core/services/api.service";
import {UPDATE_MESSAGES} from "../../../core/services/store/module/message";
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
  data(){
    return{
      message:'',
    }
  },
  methods:{
    submitMessage() {
      this.sendMessage({
        message: this.message,
        message_type:this.message_type,
      })
          .then(({data}) => {
            this.$store.dispatch(UPDATE_MESSAGES,data);
            //this.messages.push(data);
            this.message = '';
          });
    },
    async handleFileUpload() {
      const file = this.$refs.browsePhoto.files[0]
      const formData = new FormData()
      formData.append('file', file)
      const {data} = await ApiService.post('user/message/upload-attachment', formData, {headers: {'Content-Type': 'multipart/form-data'}});
      this.sendMessage({
        message: data.url,
        type: 'image'
      })
          .then(({data}) => {
            this.$store.dispatch(UPDATE_MESSAGES,data);
            //this.messages.push(data);
          });
    },
    async sendMessage(params = {}) {
      return await ApiService.post(`user/message/${this.selected.id}/message`, {
        ...params
      });
    },
  },
}
</script>
<style lang="scss" scoped>
@import 'src/assets/scss/message';
</style>