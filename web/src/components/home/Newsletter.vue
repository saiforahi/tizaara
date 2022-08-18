<template>
  <section class="weekly-market-wrapper our-brand-wrapper newsletter">
    <div class="container">
      <div class="grid-bg-pad">
        <div class="row">
          <div class="col-sm-12">
            <div class="content">
              <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
                <h3>{{ $t("message.new_letter.subscribe") }}</h3>
                <div class="input-group">
                  <input type="email" name="email" class="form-control" v-model="form.email"
                         :placeholder="$t('message.new_letter.enter_your_email')" required
                         :class="{ 'is-invalid': form.errors.has('email') }">
                  <span class="input-group-btn">
                                    <button class="btn" type="submit">{{
                                        $t("message.new_letter.subscribe_now")
                                      }}</button>
                                </span>
                  <has-error :form="form" field="email"></has-error>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "Newsletter",
  data() {
    return {
      form: new Form({
        email: '',
      }),
    }
  },
  methods: {
    submit() {
      this.form.post('subscribe')
          .then(() => {
            this.form.reset();
            toast.fire({
              icon: 'success',
              title: this.$t("message.new_letter.subscribe_successfully")
            });
          })
    }
  }
}
</script>

<style scoped>
.newsletter {

}

.newsletter .content {
  max-width: 650px;
  margin: 0 auto;
  text-align: center;
  position: relative;
  z-index: 2;
}

.newsletter .content h2 {
  color: #243c4f;
  margin-bottom: 40px;
}

.newsletter .content .form-control {
  height: 50px;
  border-color: #243c4f;
  border-radius: 0;
}

.newsletter .content.form-control:focus {
  box-shadow: none;
  border: 2px solid #243c4f;
}

.newsletter .content .btn {
  min-height: 50px;
  border-radius: 0;
  background: #243c4f;
  color: #fff;
  font-weight: 600;
}
</style>