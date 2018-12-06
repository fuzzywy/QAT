<template>
    <div>
        <el-table
            v-loading="loading.qatDataStatus"
            :data="data.slice((currentPage-1)*pagesize,currentPage*pagesize)"
            max-height="500"
            border
            :options="getdata"
            style="margin: auto;">
            <el-table-column v-for="(value, key) in data[0]" width="180" :key="key" :prop="key" :label="key">
            </el-table-column >
        </el-table>
        <el-pagination
          style="padding-left: 150px;"
          @size-change="size_change"
          @current-change="current_change"
          :current-page="currentPage"
          :page-sizes="[5, 10, 50, 100]"
          :page-size="5"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total">
        </el-pagination>

    </div>
</template>

<script>
    export default {
        data() {
            return {
              loading: {
                qatDataStatus: false
              },
              total:0,//默认数据总数
              pagesize:5,//每页的数据条数
              currentPage:1,//默认开始页面
              data: []
            }
        },
        methods: {
          current_change: function(currentPage) {
              this.currentPage = currentPage;
          },
          size_change: function(sizeChange) {
              this.pagesize = sizeChange;
          }
        },
        computed: {
          getdata() {
            if ( this.$store.getters.qatDataStatus == 1 ) {
              this.loading.qatDataStatus = true;
            }
            if ( this.$store.getters.qatDataStatus == 2 ) {
              this.loading.qatDataStatus = false;
              this.data = this.$store.getters.qatData['data'];
              // var downloadfiles = this.$store.getters.qatData['file'];
              // this.bus.$emit('downloadfiles', { downloadfiles: downloadfiles });
              this.total = this.$store.getters.qatData.length;
              this.currentPage = 1;
            }
          }
        },
        mounted() {
          this.bus.$on('loadingQatDataStatus', types=> {
              this.loading.qatDataStatus = types.loadingQatDataStatus;
          });
        }
    }
</script>