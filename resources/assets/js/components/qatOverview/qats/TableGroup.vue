<style>
    
</style>

<template>
    <div>
        <el-table
            v-loading="loading.qatDataStatus"
            :data="data.slice((currentPage-1)*pagesize,currentPage*pagesize)"
            height="287"
            border
            :options="getdata"
            style="width: 80%; margin: auto;">
            <el-table-column v-for="(value, key) in data[0]" width="180" :key="key" :prop="key" :label="key">
            </el-table-column >
            <!-- <el-table-column
                prop="date"
                label="日期"
                width="180">
            </el-table-column>
            <el-table-column
                prop="name"
                label="姓名"
                width="180">
            </el-table-column>
            <el-table-column
                prop="address"
                label="地址">
            </el-table-column> -->
        </el-table>
        <!-- <el-pagination
            layout="prev, pager, next"
            :page-size="3"
            :total="total"
            @current-change="current_change">
        </el-pagination> -->
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
                data: [/*{
                  date: '2016-05-03',
                  name: '王小虎',
                  address: '1'
                }, {
                  date: '2016-05-02',
                  name: '王小虎',
                  address: '2'
                }, {
                  date: '2016-05-04',
                  name: '王小虎',
                  address: '3'
                }, {
                  date: '2016-05-01',
                  name: '王小虎',
                  address: '4'
                }*/]
            }
        },
        methods: {
            current_change: function(currentPage) {
                this.currentPage = currentPage;
            },
            size_change: function(sizeChange) {
                // console.log(`每页 ${sizeChange} 条`);
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
              this.data = this.$store.getters.qatData;
              this.total = this.$store.getters.qatData.length;
              // this.pagesize = 5;
              this.currentPage = 1;
            }
          }
        }
    }
</script>