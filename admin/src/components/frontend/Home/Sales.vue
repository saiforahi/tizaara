<template>
  <b-form @submit.prevent="submit()" @keydown="form.onKeydown($event)">
    <ckeditor :editor="editor" v-model="form.join_sales" :config="editorConfig"></ckeditor>
    <CRow class="justify-content-end">
      <CCol col="4" sm="4" md="3" class="my-3">
        <CButton block color="info" type="submit" :disabled="form.busy">
              <span v-if="form.busy" class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>
          <span v-if="form.busy" class="sr-only">{{ $t("message.privacy.loading")}}</span>
          {{ $t("message.privacy.update")}}
        </CButton>
      </CCol>
    </CRow>
  </b-form>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {JOIN_SALES} from "@/core/services/store/module/page_manage";
import {mapGetters} from "vuex";

export default {
  name: "Sales",
  data() {
    return {
      form: new Form({
        join_sales: '',
      }),
      editor: ClassicEditor,
      editorConfig: {},
    }
  },
  methods: {
    submit() {
      this.form.post('join_sales')
          .then(({data}) => {
            toast.fire({
              icon: 'success',
              title: this.$t("message.privacy.join_sales_update_successfully")
            });
          })
    }
  },
  created() {
    this.$store.dispatch(JOIN_SALES)
  },
  computed: {
    ...mapGetters(["getJoinSales"]),
  },
  watch: {
    getJoinSales(data) {
      this.form.join_sales = data;
    }
  }
}
</script>

<style scoped>

</style>