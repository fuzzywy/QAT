 <template>
    <div>
        <el-button size="small" type="primary" style="float:right" @click="exportQatParamDetail" :exportParamDetailData="exportParamDetailData" :loading="loading.qatExportStatus">导出</el-button>
        <el-button v-show="isShow" size="small" type="primary" style="float:right;margin-right: 20px;" @click="exportParamWhiteList" :exportParamWhiteListData="exportParamWhiteListData" :loading="loading.qatExportWhiteListStatus">导出白名单</el-button>
        <el-upload 
            v-show="isShow"
            style="float:right"
            :action="uploadActionUrl()"
            accept=".csv"
            :data="myData"
            :before-upload="onBeforeUpload"
            :limit="1"
            :on-exceed="handleExceed"
            :on-success="handleSuccess"
            :headers="myHeader"
            :file-list="fileList">
            <el-button size="small" type="primary">导入白名单</el-button>
            <div slot="tip" class="el-upload__tip">请上传csv格式文件</div>
        </el-upload>

        <el-table
            v-loading="loading.qatParamDetailDataStatus"
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
    import {Common} from '../../../../mixin/common/Common.js'; 
    export default {
        mixins: [
            Common
        ],
        data() {
            return {
                loading: {
                    qatParamDetailDataStatus: false,
                    qatExportStatus: false,
                    qatExportWhiteListStatus: false
                },
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面
                data: [],
                postData: [],//查询条件,

                fileList: [],
                myData:{},
                isShow: false
            }
        },
        methods: {
            current_change: function(currentPage) {
                this.currentPage = currentPage;
                this.postData.currentPage = currentPage;
                this.postData.pageSize = this.pageSize;
                this.$store.dispatch( 'loadQatParamDetailData', this.postData);
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
                this.postData.currentPage = this.currentPage;
                this.postData.pageSize = sizeChange;
                this.$store.dispatch( 'loadQatParamDetailData', this.postData);
            },
            exportQatParamDetail() {
                if( this.postData.length == 0){
                    this.$message({
                        type: 'warning',
                        message: '请先选中具体检查项！'
                    }); 
                    return;
                }
                this.qatExportStatus = true;
                this.$store.dispatch( 'exportQatParamDetailData', this.postData);
            },
            onBeforeUpload(file){
                const isCsvOrTxt = file.name.indexOf('.csv') !== -1;
                //const isLt1M = file.size / 1024 / 1024 < 1;

                if (!isCsvOrTxt) {
                    this.$message.error('上传文件只能是csv格式!');
                }
                //if (!isLt1M) {
                    //this.$message.error('上传文件大小不能超过 1MB!');
                //}
                //return isCsvOrTxt && isLt1M;
                return isCsvOrTxt;
            },
            handleExceed(files, fileList) {
                this.$message.warning(`当前限制选择 1 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`);
            },
            handleSuccess(res,file,fileList) {
                if(res.code===20000){
                    this.$message({
                        message: '上传成功！',
                        type: 'success'
                    });
                }else {
                    this.$message({
                        message: res.msg,
                        type: 'error'
                    });
                }
            },
            uploadActionUrl() {
                return "paramCheck/uploadWhiteList";
            },
            exportParamWhiteList() {
                this.loading.qatExportWhiteListStatus = true;
                this.$store.dispatch( 'exportParamWhiteList', this.myData);
            }

        },
        computed: {
            getData() {
                if ( this.$store.getters.qatParamDetailDataStatus == 1 ) {
                    this.loading.qatParamDetailDataStatus = true;
                }
                if ( this.$store.getters.qatParamDetailDataStatus == 2 ) {
                    this.loading.qatParamDetailDataStatus = false;
                    this.data = this.$store.getters.qatParamDetailData['data'];
                    this.total = this.$store.getters.qatParamDetailData['total'];
                }
            },
            exportParamDetailData() {
                switch( this.$store.getters.exportParamDetailDataStatus ) {
                    case 1:
                        this.loading.qatExportStatus = true;
                        break;
                    case 2:
                        var downloadFile = this.$store.getters.exportParamDetailData;
                        this.download(downloadFile);
                        this.loading.qatExportStatus = false;
                        break;
                    case 3:
                        this.loading.qatExportStatus = false;
                        break;
                    default:
                        this.loading.qatExportStatus = false;
                        break;
                }
            },
            myHeader(){
                return {
                    "authToken":window.sessionStorage.getItem('authToken')
                }
            },
            exportParamWhiteListData() {
                switch( this.$store.getters.exportParamWhiteListStatus ) {
                    case 1:
                        this.loading.qatExportWhiteListStatus = true;
                        break;
                    case 2:
                        var downloadFile = this.$store.getters.exportParamWhiteList;
                        this.download(downloadFile);
                        this.loading.qatExportWhiteListStatus = false;
                        break;
                    case 3:
                        this.loading.qatExportWhiteListStatus = false;
                        break;
                    default:
                        this.loading.qatExportWhiteListStatus = false;
                        break;
                }
            }
        },
        mounted() {
            this.bus.$on('loadQatParamDetailData', data=> {
                this.postData = data.postData;
                this.myData = {'subItem':data.postData.subItem};
                this.isShow = false;
                if (data.postData.subItem === "单向邻区"){
                    this.isShow = true;
                }
            });
            this.bus.$on('loadQatParamData', data=>{
                this.data = [];
                this.total = 0;
            });
        }
    }
</script>