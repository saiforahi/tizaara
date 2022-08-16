<template>
  <div class="table-responsive">
    <table class="transaction-history dataTable no-footer dtr-inline" id="DataTables_Table_0" role="grid"
           aria-describedby="DataTables_Table_0_info" style="width: 1008px;">
      <thead>
      <tr role="row">
        <th aria-controls="DataTables_Table_0" v-for="column in columns" :key="column.name"
            @click="$emit('sort', column.name)"
            :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
            :style="'cursor:pointer;'">
          {{ column.label }}
          <font-awesome-icon class="ml-2 pt-1" icon="sort"/>
        </th>
      </tr>
      </thead>
      <slot></slot>
    </table>
  </div>
</template>

<script>
export default {
  name: "DataTable",
  props: ['columns', 'sortKey', 'sortOrders']
}
</script>

<style scoped>

</style>
