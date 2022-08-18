<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container" style="font-family: 'Noto Sans JP', sans-serif;">
      <div class="container">
        <div class="row text-center justify-content-center">
          <div class="col-sm-6 col-sm-offset-3">
            <div style="border-radius:150px; height:150px; width:150px; background: #F8FAF5; margin:0 auto;">
              <i class="checkmark fa-6x" style="color: red">X</i>
            </div>
            <br><br> <h2 style="color: #dc3545">Error</h2>
            <h3>Dear, Customer</h3>
            <p style="font-size:20px;color:#5C5C5C;">
              We are sorry. Your order is not completed successfully. You can try few time later. Thank you for with us
            </p>
            <a href="/" class="btn btn-success">     Home      </a>
            <br><br>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CheckoutError",
}
</script>

<style scoped>

</style>