<template>
  <div class="input-group cus-input-group">
    <div class="input-group-prepend">
      <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ type }}
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="javascript:void(0);" @click="type = 'Products'">{{ $t('message.search.products') }}</a>
        <a class="dropdown-item" href="javascript:void(0);" @click="type = 'Suppliers'">{{ $t('message.search.suppliers') }}</a>
      </div>
    </div>
<!--    <vue-suggestion :items="products"-->
<!--                    v-model="selectKeyword"-->
<!--                    :setLabel="setLabel"-->
<!--                    :itemTemplate="itemTemplate"-->
<!--                    @changed="(text)=>{ this.keyword = text; this.products = []}"-->
<!--                    @enter="itemEnter" @selected="itemSelected">-->
<!--    </vue-suggestion>-->
    <vue-simple-suggest
            v-model="chosen"
            :list="simpleSuggestionList"
            value-attribute="id"
            display-attribute="name"
            class="suggest"
            :class="{'suppliers': type == 'Suppliers'}"
            @select="onSearchSelected"
            @hover="onSearchHover"
            @blur="onSearchBlur"
    >
      <template slot="suggestion-item" slot-scope="{suggestion}">
        {{ suggestion.name }}
      </template>
      <template v-if="type == 'Suppliers'" slot="expand">
        <div class="ui-searchbar-keyword-panel ui-searchbar-keyword-hide ui-searchbar-dynamic"
             style="z-index: 9999; height: auto; display: block;">
          <div v-show="isHover" class="ui-searchbar-dynamic-query-container">
            <div class="ui-searchbar-dynamic-query-content-wrap"
                 style="z-index: 99999; display: block;">
              <div class="ui-searchbar-dynamic-query-content" style="margin-top: 15px;">
                <h3 class="ui-searchbar-dynamic-attrs-title">{{ searchChosen.name }}</h3>
                <div class="ui-searchbar-dynamic-attrs-list">
                  <a v-for="(item, i) in searchChosen.products" :key="i" href="#" @click.prevent="onItemSelected(item)">
                    {{ item.name }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </vue-simple-suggest>
    <button @click.prevent="itemSelected" type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
  </div>
</template>

<script>
  import itemTemplate from '@/components/layout/inc/itemTemplate'
  import {debounce} from 'lodash'
  import {mapGetters} from 'vuex'
  import {SEARCH_PRODUCT} from '@/core/services/store/module/search'
  import ApiService from "@/core/services/api.service"

  import VueSimpleSuggest from '@/core/simple-suggest'
  import 'vue-simple-suggest/dist/styles.css' // Optional CSS

  export default {
    name: 'Search',
    components: {
      VueSimpleSuggest
    },
    data() {
      return {
        type: 'Products',
        selectKeyword: '',
        products: [],
        itemTemplate,
        isHover: false,
        searchChosen: {},
        chosen: ''
      }
    },
    methods: {
      onSearchHover(obj) {
        this.isHover = true
        this.searchChosen = obj
      },
      onSearchBlur() {
        this.$nextTick(() => {
           this.isHover = false
        })
      },
      onSearchSelected(item) {
        this.isHover = false
        if (item) {
          this.$router.push({name: 'category', query: {pro: item.name, type: this.type}}).catch()
        }
      },
      onItemSelected(item) {
        this.isHover = false
        if (item) {
          this.$router.replace({name: 'category', query: {pro: item.name, type: 'Products'}}).catch()
        }
      },
      search() {
        let data = this.getProductName(this.chosen)
        if (data.length > 5) {
          this.products = data.slice(0, 6)
        } else {
          this.products = data
          this.$store.dispatch(SEARCH_PRODUCT, {
            keyword: this.chosen,
            type: this.type,
          })
        }
      },
      simpleSuggestionList() {
        return ApiService.get('product-name', '', {
          params: {
            keyword: this.chosen,
            type: this.type
          }
        }).then(({data}) => {
          return data
        })
      },
      loadQuery() {
        this.chosen = this.$route.query.pro
      },
      searchAgain() {
        this.products = this.getProductName(this.chosen).slice(0, 6)
      }
    },
    created() {
      this.debounceName = debounce(this.search, 0)
    },
    watch: {
      chosen() {
        if (!this.chosen || this.chosen.length < 2) return
        this.debounceName()
      },
      searchProductLoad() {
        this.searchProductLoad ? this.searchAgain() : ''
      },
      '$route.query.pro'() {
        this.loadQuery()
      },
      type() {
        this.chosen = ''
        this.products = []
      }
    },
    computed: {
      ...mapGetters(['getProductName', 'searchProductLoad'])
    }
  }
</script>

<style lang="scss">
  .vue-suggestion {
    width: 70%;

    .vs__selected {
      border: 0;
      border-radius: 0;
      margin: 0;
      padding: 0;
    }

    .vs__input {
      width: 100%;
      height: calc(1.5em + .75rem + 5px) !important;
    }

    .vs__list {
      width: 100%;
      text-align: left;
      border: none;
      border-top: none;
      max-height: 400px;
      overflow-y: auto;
      border-bottom: 1px solid #023d7b;
      position: relative;

      .vs__list-item {
        background-color: #fff;
        padding: 10px;
        border-left: 1px solid #023d7b;
        border-right: 1px solid #023d7b;
      }

      .vs__list-item:last-child {
        border-bottom: none;
      }

      .vs__list-item:hover {
        background-color: #eee !important;
      }

      .vs__list-item {
        cursor: pointer;
      }
    }

    .vs__list,
    .vs__loading {
      position: absolute;
    }
  }

  .suggest {
    width: 70%;
  }

  .suggestions {
    min-height: 210px;
  }

  .vue-simple-suggest.designed .input-wrapper input {
    border: 1px solid #aaaaaa;
    border-radius: 0;
    height: calc(1.5em + .75rem + 5px);
  }

  .ui-searchbar-dynamic-query-content-wrap {
    background: #f5f5f5;
    position: absolute;
    right: 0;
    top: 0;
    width: 50%;
    height: 100%;
    display: none;
  }

  .ui-searchbar-keyword-panel {
    position: absolute;
    width: 100%;
    padding: 0 5px;
    top: 33px;
    left: 0;
    background-color: #fff;
    z-index: 2;
    _position: relative;
    _zoom: 1 !important;
    _margin-right: -14px;
    _margin-bottom: -100%;
    _top: 2px;
    _left: 0;
    border-radius: 3px;
    -moz-box-shadow: 1px 1px 4px -1px rgba(0, 0, 0, .1);
    -webkit-box-shadow: 1px 1px 4px -1px rgb(0 0 0 / 10%);
    box-shadow: 1px 1px 4px -1px rgb(0 0 0 / 10%);
  }

  .ui-searchbar-dynamic-attrs-title {
    font-size: 14px;
    font-weight: 700;
    margin: 0;
    margin-left: 10px;
    line-height: 25px;
    color: #666;
  }

  .ui-searchbar-dynamic-attrs-list {
    margin: 10px;
    overflow: hidden;
  }

  .ui-searchbar .ui-searchbar-keyword-panel a:link, .ui-searchbar .ui-searchbar-keyword-panel a:visited {
    color: #666;
  }

  .ui-searchbar-dynamic-attrs-list a {
    float: left;
    padding: 6px;
    border: 1px solid #ddd;
    background: #fff;
    margin-bottom: 8px;
    margin-right: 4px;
    border-radius: 2px;
    text-decoration: none;
  }

  .ui-searchbar-dynamic a, .ui-searchbar-dynamic-item {
    display: block;
    padding: 6px 5px 6px 10px;
    color: #666 !important;
    cursor: pointer;
    line-height: 18px;
  }

  .ui-searchbar-dynamic-attrs-list a:hover, .ui-searchbar-dynamic-attrs-list a:hover {
    background: #f05931;
    border-color: #f05931;
    color: #fff!important;
  }
</style>