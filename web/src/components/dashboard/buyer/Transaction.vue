<template>
  <div class="content">
    <!-- Orders -->
    <div class="orders">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <h4 class="box-title">{{ $t("message.sidebar.transaction")}} </h4>
            </div>
            <div class="card-body--">
              <div class="table-stats ov-h">
                <table class="table">
                  <thead>
                  <tr>
                    <th class="serial">#</th>
                    <th>Invoice number</th>
                    <th>Supplier Name</th>
                    <th>{{ $t("message.dashboard.quantity")}}</th>
                    <th>Date</th>
                    <th>{{ $t("message.dashboard.status")}}</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td class="serial">1.</td>
                    <td> #5469 </td>
                    <td>  <span class="name">{{ $t("message.dashboard.louis_stanley")}}</span> </td>
                    <td><span class="count">231</span></td>
                    <td><span class="count">2 january, 2021</span></td>
                    <td>
                      <b-badge>{{ $t("message.dashboard.complete")}}</b-badge>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div> <!-- /.table-stats -->
            </div>
          </div> <!-- /.card -->
        </div>  <!-- /.col-lg-8 -->
      </div>
    </div>
    <!-- /.orders -->
  </div>
</template>
<script>
export default {
  name:'transaction',
  data() {
    return {

    }
  }
}
</script>
<style lang="scss" scoped>
@import 'src/assets/scss/dashboard/index';
</style>