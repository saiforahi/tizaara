<template>
  <div>
    <span>{{ item.name }}</span>
  </div>
</template>

<script>
export default {
name: "itemTemplate",
  props: {
    item: { required: true },
  }
}
</script>

<style scoped>

</style>