<template>
  <b-form @submit.prevent="submit()" @keydown="form.onKeydown($event)">
    <ckeditor :editor="editor" v-model="form.terms_condition" :config="editorConfig"></ckeditor>
    <CRow class="justify-content-end">
      <CCol col="4" sm="4" md="3" class="my-3">
        <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
          <span v-if="form.busy" class="sr-only">{{$t("message.terms.loading")}}</span>
          {{$t("message.terms.update")}}
        </CButton>
      </CCol>
    </CRow>
  </b-form>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {TERM_CONDITIONS} from "@/core/services/store/module/page_manage";
import {mapGetters} from "vuex";

export default {
  name: "Terms",
  data() {
    return {
      form: new Form({
        terms_condition: '',
      }),
      editor: ClassicEditor,
      editorConfig: {},
    }
  },
  methods: {
    submit() {
      this.form.post('term_condition')
          .then(({data}) => {
            toast.fire({
              icon: 'success',
              title: this.$t("message.terms.terms_and_conditions_update_successfully")
            });
          })
    }
  },
  created() {
    this.$store.dispatch(TERM_CONDITIONS)
  },
  computed: {
    ...mapGetters(["getTermCondition"]),
  },
  watch: {
    getTermCondition(data){
      this.form.terms_condition = data;
    }
  }
}
</script>

<style scoped>

</style>