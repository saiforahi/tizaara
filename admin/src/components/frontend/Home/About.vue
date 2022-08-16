<template>
  <b-form @submit.prevent="submit()" @keydown="form.onKeydown($event)">
    <ckeditor :editor="editor" v-model="form.about_us" :config="editorConfig"></ckeditor>
    <CRow class="justify-content-end">
      <CCol col="4" sm="4" md="3" class="my-3">
        <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
          <span v-if="form.busy" class="sr-only">{{$t("message.about_us.loading")}}</span>
          {{$t("message.about_us.update")}}
        </CButton>
      </CCol>
    </CRow>
  </b-form>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {ABOUT_US} from "@/core/services/store/module/page_manage";
import {mapGetters} from "vuex";

export default {
  name: "About",
  data() {
    return {
      form: new Form({
        about_us: '',
      }),
      editor: ClassicEditor,
      editorConfig: {},
    }
  },
  methods: {
    submit() {
      this.form.post('about_us')
          .then(({data}) => {
            toast.fire({
              icon: 'success',
              title: this.$t("message.about_us.about_us_update_successfully")
            });
          })
    }
  },
  created() {
    this.$store.dispatch(ABOUT_US)
  },
  computed: {
    ...mapGetters(["getAboutUs"]),
  },
  watch: {
    getAboutUs(data) {
      this.form.about_us = data;
    }
  }
}
</script>

<style scoped>

</style>