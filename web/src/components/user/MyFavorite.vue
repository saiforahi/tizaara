<template>
  <div class="product-list-wrapper" style="min-height: 80vh">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-8 offset-2">
          <div>
            <div class="item-container">
              <a class="product-item" title="Fila Exolize 2" v-for="item in products" :key="item.product.id"
                 href="#">
                <div class="item-box">
                  <div class="img-box">
                    <img class="equalHeightWidth" :src="showImage(item.product.thumbnail_img)" alt="Fila Exolize 2">
                  </div>
                  <div class="caption">
                    <div class="product-title">
                      <h6 title="Fila Exolize 2">{{ item.product.name }}</h6>
                    </div>
                    <div class="pdp-block">
                      <h5 class="price">{{ $t("message.product_new.৳") }}
                        {{
                          item.product.priceType == 1 ? totalPrice(item.product.product_stock) : item.product.unit_price
                        }}
                      </h5>
                      <div class="pdp-block module">
                        <div class="pdp-mod-wishlist" @click.stop="onFavorite(item.product)">
                          <i class="far fa-star"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {debounce} from "lodash";
import {api_base_url} from "@/core/config/app";
import ApiService from "@/core/services/api.service";

export default {
  name: 'MyFavorite',
  data() {
    return {
      products: []
    }
  },
  created() {
    this.loadProduct();
  },
  methods: {
    showImage(e) {
      return api_base_url + e;
    },
    loadProduct() {
      ApiService.get('user-product-favorites')
          .then(({data}) => {
            this.products = data;
          })
    },
    onFavorite(data) {
      ApiService.post('user-product-favorite/' + data.id)
          .then(() => {
            this.debounceName();
            toast.fire({
              icon: 'success',
              title: this.$t("message.common.success"),
            });
          }).catch(() => {
        swal.fire("Failed!", 'Error', 'warning')
      })
    },
    totalPrice: function (e) {
      if (e.length == 0) return;
      let max = e.reduce((a, b) => Number(a.price) > Number(b.price) ? a : b)
      let min = e.reduce((a, b) => Number(a.price) < Number(b.price) && 0 !== Number(a.price) ? a : b)
      return max === min ? max.price : min.price + ' - ' + max.price;
    }
  }
}
</script>

<style scoped>

</style>