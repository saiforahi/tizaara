<template>
  <CFooter :fixed="false">
    <div>
      <span class="ml-1">{{$store.getters.generalList.footer_text}}</span>
    </div>
    <div class="mfs-auto">
      <span class="mr-1">{{$t("message.footer.developed_by")}}</span>
      <a href="http://uniteditbd.com/" target="_blank">{{$t("message.footer.united_it")}}</a>
    </div>
  </CFooter>
</template>

<script>
export default {
  name: 'Footer'
}
</script>
