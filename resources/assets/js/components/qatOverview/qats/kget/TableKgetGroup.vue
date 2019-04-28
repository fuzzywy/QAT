 <template>
    <div>
        <el-table
            v-loading="loading.qatKgetDataStatus"
            :data="data"
            max-height="320"
            border
            :options="getData"
            style="margin: auto;">
            <el-table-column v-for="(value, key) in data[0]" min-width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip>
            </el-table-column >
        </el-table>
        <el-pagination
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
                    qatKgetDataStatus: false
                },
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面
                data: [],
                postData: [],//查询kget参数
                initFlag: true
            }
        },
        methods: {
            current_change: function(currentPage) {
                this.currentPage = currentPage;
                this.postData.currentPage = currentPage;
                this.postData.pageSize = this.pageSize;
                this.$store.dispatch( 'loadQatKgetData', this.postData);
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
                this.postData.currentPage = this.currentPage;
                this.postData.pageSize = sizeChange;
                this.$store.dispatch( 'loadQatKgetData', this.postData);
            }
        },
        computed: {
            getData() {
                if ( this.$store.getters.qatKgetDataStatus == 1 ) {
                    this.loading.qatKgetDataStatus = true;
                }
                if ( !this.initFlag && this.$store.getters.qatKgetDataStatus == 2 ) {
                    this.loading.qatKgetDataStatus = false;
                    this.data = this.$store.getters.qatKgetData['data'];
                    this.total = this.$store.getters.qatKgetData['total'];
                }
            }
        },
        mounted() {
            this.bus.$on('loadQatKgetData', data=> {
                this.postData = data.postData;
                this.initFlag = false;
            });
            this.bus.$on('qatKgetDataStatus', data=> {
                this.loading.qatKgetDataStatus = data.qatKgetDataStatus;
            });
        }
    }
</script>