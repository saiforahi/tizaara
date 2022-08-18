<template>
  <div class="dx-container dx-top-container" style="display: block;">
    <div class="dx-dom">
      <div class="tpl-wrapper">
        <div class="dx-event-node"
             style="position: relative; display: flex; overflow: hidden; flex-direction: row; width: 100%; -webkit-box-orient: horizontal; -webkit-box-direction: normal;">
          <div
              style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; overflow: hidden; margin-right: 10px; margin-left: 10px; width: 40px; margin-top: 8px; height: 40px; -webkit-box-flex: 0;">
            <div
                 style="width: 100%; height: 100%; background-repeat: no-repeat; background-position: center center; background-size: 100% 100%;"
                 :style="{backgroundImage: `url(${showImage(companyFind.company_details?companyFind.company_details.company_logo:'')})`}">
              <img :src="showImage(companyFind.company_details?companyFind.company_details.company_logo:'')"
                   style="max-height: 100%; max-width: 100%; opacity: 0;"></div>
          </div>
          <div
              style="position: relative; display: flex; flex: 1 1 0%; overflow: hidden; margin-right: 10px; flex-direction: column; width: 100%; max-width: calc((100% - 0px) - 10px); margin-top: 8px; height: 100%; max-height: calc((100% - 8px) - 0px); -webkit-box-flex: 0; -webkit-box-orient: vertical; -webkit-box-direction: normal;">
            <div
                style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; overflow: hidden; margin-right: 30px; flex-direction: row; width: 100%; max-width: calc((100% - 0px) - 30px); height: auto; -webkit-box-flex: 0; -webkit-box-orient: horizontal; -webkit-box-direction: normal;">
              <div
                  style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; font-size: 14px; width: fit-content; color: rgb(102, 102, 102); height: auto; justify-content: flex-start; align-items: center; overflow: hidden; max-width: none; -webkit-box-flex: 0; -webkit-box-pack: start; -webkit-box-align: center;">
                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 17px;"></span>
              </div>
              <div
                  style="position: relative; cursor: pointer; display: flex; flex-shrink: 1; flex-grow: 0; font-size: 14px; margin-left: 8px; width: 100%; max-width: none; color: rgb(102, 102, 102); height: auto; justify-content: flex-start; align-items: center; overflow: hidden; -webkit-box-flex: 0; -webkit-box-pack: start; -webkit-box-align: center;">
                <span v-if="companyFind.company_basic"
                    style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 17px;">
                  {{ companyFind.company_basic.name }}
                  <i class="fa fa-map-marker" aria-hidden="true"></i> {{ companyFind.company_basic.office_space }}
                </span>
              </div>
            </div>
            <div v-if="companyFind.company_basic"
                style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; overflow: hidden; margin-right: 30px; flex-direction: row; width: 100%; max-width: calc((100% - 0px) - 30px); height: auto; -webkit-box-flex: 0; -webkit-box-orient: horizontal; -webkit-box-direction: normal;">
              <div
                  style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; font-size: 14px; width: fit-content; color: rgb(102, 102, 102); height: auto; justify-content: flex-start; align-items: center; overflow: hidden; max-width: none; -webkit-box-flex: 0; -webkit-box-pack: start; -webkit-box-align: center;">
                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 17px;"></span>
              </div>
              <div
                  style="position: relative; cursor: pointer; display: flex; flex-shrink: 1; flex-grow: 0; font-size: 14px; margin-left: 8px; width: 100%; max-width: none; color: rgb(102, 102, 102); height: auto; justify-content: flex-start; align-items: center; overflow: hidden; -webkit-box-flex: 0; -webkit-box-pack: start; -webkit-box-align: center;">
                <span
                    style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 17px;">
                  <i class="fa fa-globe" aria-hidden="true"></i>
                  {{ 'https://tizaara.com/'+companyFind.company_basic.display_name }}
                </span>
              </div>
            </div>
            <div
                style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; overflow: hidden; flex-direction: row; width: 100%; margin-top: 4px; height: auto; -webkit-box-flex: 0; -webkit-box-orient: horizontal; -webkit-box-direction: normal; max-width: 1341px;">
              <div
                  style="position: relative; display: flex; flex: 1 1 0%; overflow: hidden; height: 100%; flex-direction: row; -webkit-box-flex: 0; -webkit-box-orient: horizontal; -webkit-box-direction: normal;">
                <div v-if="companyFind.company_basic"
                    style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; font-size: 14px; width: fit-content; color: rgb(51, 51, 51); height: auto; justify-content: flex-start; align-items: center; overflow: hidden; max-width: none; -webkit-box-flex: 0; -webkit-box-pack: start; -webkit-box-align: center;">
                  <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 17px;">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    {{
                      companyFind.company_basic.phone
                    }}</span>
                </div>
                <div
                     style="position: relative; display: flex; flex-shrink: 0; flex-grow: 0; font-size: 12px; width: fit-content; color: rgb(102, 102, 102); height: auto; justify-content: flex-start; align-items: center; overflow: hidden; max-width: none; -webkit-box-flex: 0; -webkit-box-pack: start; -webkit-box-align: center;">
                  <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 14px;">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    {{ receiver().full_name }}<br>
                    <template v-if="!companyFind.company_basic">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    {{ receiver().email }}<br>
                      <i class="fa fa-phone" aria-hidden="true"></i>
                    {{ receiver().mobile }}<br>
                  </template>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="close topclose"></div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import {api_base_url} from "@/core/config/app";
import {PRODUCT} from "../../../core/services/store/module/product";

export default {
  name: "Product",
  props: {
    selected: {
      type: Object,
      required: true
    }
  },
  created() {
    this.data = JSON.parse(this.selected.data);
    this.$store.dispatch(PRODUCT, this.data.id);
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    /*
    * method for single product load in new tab
    * */
    loadSingleProduct() {
      window.open("/single/product/" + this.m_product.slug, "_blank");
    },
    receiver() {
      return this.selected.conversation.participants.find(item => item.messageable_id != this.user?.id).messageable;
    }
  },
  computed: {
    ...mapGetters(["m_product","chat_conversation_types"]),
    user() {
      return this.$store.getters.currentUser;
    },
    companyFind(){
      let user_id = this.selected.conversation.participants.find(item => item.messageable_id != this.user?.id).messageable_id;
      return this.chat_conversation_types.find(item=>item.messageable2_id ==user_id);
    },
  },
  watch: {
    selected(val) {
      this.data = JSON.parse(val.data);
      this.$store.dispatch(PRODUCT, this.data.id);
    }
  },
}
</script>

<style scoped>

</style>