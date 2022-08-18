<template>
  <div  style="background-color: white;" class="container">
    <div id="frame" >
      <div id="sidepanel">
        <div v-if="user.account_type ==0">
          <a href="javascript:void(0)" :class="user_type==='supplier'?'btn btn-outline-primary active':'btn btn-outline-primary'" @click="messageSwitcher('supplier')" style="display: inline-block;
                                                    margin-left: 20%;
                                                    padding: 2% 5%;
                                                    border:springgreen;
                                                    border-radius: 30%">
            {{ $t("message.message.supplier") }}</a>
          <a href="javascript:void(0)" :class="user_type==='buyer'?'btn btn-outline-primary active':'btn btn-outline-primary'" @click="messageSwitcher('buyer')" style="display: inline-block;
                                                    padding: 2% 5%;
                                                    border-radius: 30%">
            {{ $t("message.message.buyer") }}</a>
        </div>
        <div id="search">
          <label for="search_inp"><i class="fas fa-search"></i></label>
          <input v-model="search" @input="searchContact" type="text" id="search_inp" :placeholder= "$t('message.sidebar.search_contacts')"/>
        </div>
        <div id="contacts">
          <ul>
            <li v-for="(item, key) in conversations" :class="selected.id == item.id?'contact active':'contact'"
                :key="key" @click="selectConversation(item)">
              <div class="wrap">
                <img :src="showImage(receiver(item.conversation.participants).photo)" height="60px" alt=""/>
                <div class="meta">
                  <p class="name">{{ companyFind(item.conversation.participants)?companyFind(item.conversation.participants).name:'' }}</p>
                  <p class="preview">{{ receiver(item.conversation.participants).full_name }}</p>
                  <p class="preview">{{ item.conversation.last_message.body }}</p>
                </div>
              </div>
            </li>
            <infinite-loading ref="infiniteLoading" @infinite="infiniteHandler">
              <div slot="no-more"></div>
              <div slot="no-results"></div>
            </infinite-loading>
          </ul>
        </div>
      </div>
      <div class="content">
        <template v-if="selected">
          <div class="contact-profile">
            <product :selected="selected" />
            <p>4.5/5 <i class="fa fa-star" aria-hidden="true"></i></p>
            <p>
              <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
              <i class="fa fa-check-circle fa-1x" aria-hidden="true"></i>Mobile |
              <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
              <i class="fa fa-check-circle fa-1x" aria-hidden="true"></i>Email |
              GST<i class="fa fa-check-circle" aria-hidden="true"></i>
            </p>
          </div>
          <Message :selected="selected" :message_type="user_type" @toggle-sidepanel="sidepanel = !sidepanel"/>
          <ComposeMessage :message_type="user_type" :selected="selected" @toggle-sidepanel="sidepanel = !sidepanel"/>
        </template>
        <template v-if="!selected">
          <div class="container-fluid mt-100" style="border: 2px solid #73AD21;">
            <div class="row">
              <div class="col-md-12">
                <div class="card" style="min-height: 700px;">
                  <div class="card-body cart">
                    <div class="col-sm-12 empty-cart-cls text-center" style="margin-top: 20%;">
                      <i class="fa fa-comments-o fa-5x" style="color: green" aria-hidden="true"></i>
                      <h3><strong>Please select a contact</strong></h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
<!--      <div v-if="sidepanel" id="sidepanel-right">
        <sider-panel v-if="selected" :selected="selected" @sidepanel="(val) => sidepanel = val" />
      </div>-->
    </div>
  </div>

</template>

<script>
import {debounce} from "lodash";
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";
import Message from "./Message";
import ComposeMessage from "./ComposeMessage";
import SiderPanel from "./SiderPanel";
import Product from "./Product";
import {mapGetters} from "vuex";
import {GET_CONVERSATION_TYPE, MAKE_MESSAGE_EMPTY, MAKE_USER_TYPE} from "../../../core/services/store/module/message";
export default {
  name: "Conversation",
  components: {
    Message,
    ComposeMessage,
    SiderPanel,
    Product,
  },
  data() {
    return {
      page: 1,
      conversations: [],
      conversations_main: [],
      search: '',
      selected: '',
      sidepanel: false,
    }
  },
  created() {
    $('body').addClass('open');
    this.$store.dispatch(GET_CONVERSATION_TYPE);
    if (this.user && this.user.accoount_type ==1) this.$store.dispatch(MAKE_USER_TYPE,'supplier');
    else if (this.user && this.user.accoount_type ==2) this.$store.dispatch(MAKE_USER_TYPE,'buyer');
    else this.$store.dispatch(MAKE_USER_TYPE,'supplier');
  },
  methods: {
    messageSwitcher(message_type){
      if(this.user_type !== message_type) {
        this.selected = '';
        this.$store.dispatch(MAKE_MESSAGE_EMPTY);
      }
      this.$store.dispatch(MAKE_USER_TYPE,message_type);
    },
    searchContact: debounce(function (){
      if (this.search) {
        ApiService.get('user/message/search?keyword=' + this.search).then(res => {
          this.conversations_main = res.data.data.data;
        });
      } else {
        this.page = 1
        this.conversations_main = []
        this.selected = ''
        this.$refs.infiniteLoading.stateChanger.reset();
      }
    }, 350),
    showImage(e) {
      return api_base_url + e;
    },
    selectConversation(item) {
      this.selected = item
    },
    /*filter buyer seller conversation*/
    filterConversation(){
      let chatIds = this.chat_conversation_types.map(item=>item.messageable2_id);
      /*filter for buyer or supplier switcher*/
      return  this.conversations_main.map(item=>{
        let lng = item.conversation.participants.filter(item2=>{
          if(item2.messageable_id != this.user.id && chatIds.includes(item2.messageable_id)) return item2;
        });
        if (lng.length>0) return item;
      }).filter(Boolean);
    },
    infiniteHandler($state) {
      ApiService.get('user/message/conversation', '', {
        params: {
          page: this.page,
        },
      }).then(({data}) => {
        if (data.data.data.length) {
          this.page += 1;
          this.conversations_main.push(...data.data.data);
          $state.loaded();
        } else {
          $state.complete();
        }
      });
    },
    companyFind(participants){
      let user_id = participants.find(item => item.messageable_id != this.user?.id).messageable_id;
      return this.chat_conversation_types.find(item=>item.messageable2_id ==user_id).company_basic;
    },
    receiver(participants) {
      return participants.find(item => item.messageable_id != this.user?.id).messageable;
    }
  },
  computed: {
    ...mapGetters(["m_product","chat_conversation_types","user_type"]),
    user() {
      return this.$store.getters.currentUser;
    }
  },
  watch:{
    user_type(){
      this.conversations=this.filterConversation();
    },
    conversations_main(){
      this.conversations=this.filterConversation();
    }
  }
}
</script>

<style lang="scss" scoped>
@import 'src/assets/scss/message';
</style>
