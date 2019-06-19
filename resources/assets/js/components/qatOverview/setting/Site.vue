<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="6">
                <el-form label-width="80px">
                    <el-form-item horizontal :label="this.$t('messages.common.city')">
                        <el-select
                            class="full-width"
                            v-loading="loading.qatCityStatus"
                            v-model="city"
                            :showData="getCityData"
                            collapse-tags
                            >
                            <el-option
                              v-for="item in cityGroup"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="6">
                <el-form label-width="80px">
                    <el-form-item horizontal :label="this.$t('messages.site.importType')">
                        <el-select
                            class="full-width"
                            v-model="importType"
                            collapse-tags
                            >
                            <el-option
                              v-for="item in options"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="2" :offset="6">
                <el-button type="primary" @click="exportTemplateData" :loading="loading.qatExportTemplateStatus">{{$t('messages.common.template')}}</el-button>
            </el-col>
            <el-col :span="2">
                <el-upload 
                    style="float:right;"
                    :action="uploadActionUrl"
                    accept=".csv"
                    :data="myData"
                    :before-upload="onBeforeUpload"
                    :on-success="handleSuccess"
                    :headers="myHeader"
                    :file-list="fileList"
                    :show-file-list="false">
                    <el-button type="primary">{{$t('messages.common.upload')}}</el-button>
                    <div slot="tip" class="el-upload__tip">{{$t('messages.kget.importTip')}}</div>
                </el-upload>
             </el-col>
            <el-col :span="2">
                <el-button :disabled="disabled" type="primary" @click="exportSiteData" :loading="loading.qatExportStatus" style="float:right;">{{$t('messages.common.export')}}</el-button>
            </el-col>
        </el-row>
        <el-table
            v-loading="loading.showSiteDataStatus"
            :data="tableData"
            border
            style="margin: auto;"
            :options="getData">
            <el-table-column v-for="(value,key) in tableData[0]" min-width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip  v-if="key !== 'id'" >
            </el-table-column>
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
<style>
    
</style>
<script>
    import {Common} from '../../../mixin/common/Common.js'; 
    var allCity = '';
    export default {
        mixins: [
            Common
        ],
        data() {
            return {
                loading: {
                    qatCityStatus: false,
                    qatExportStatus: false,
                    qatExportTemplateStatus: false
                },
                cityGroup: [],
                city: '',
                uploadActionUrl: 'site/uploadSiteFile',
                myData: {},
                fileList: [],
                disabled: true,

                tableData: [],
                total:0,
                pageSize:5,
                currentPage:1,
                postData: [],

                options: [{
                      value: 'apend',
                      label: this.$t('messages.common.apend')
                    }, {
                      value: 'replace',
                      label: this.$t('messages.common.replace')
                    }],
                importType: 'replace'
            }
        },
        watch: {
            city(val){
                this.postData = {city:val};
                this.$store.dispatch( 'loadSiteData', this.postData);
            }
        },
        methods: {
            current_change: function(currentPage) {
                this.currentPage = currentPage;
                this.postData.currentPage = currentPage;
                this.postData.pageSize = this.pageSize;
                this.$store.dispatch( 'loadSiteData', this.postData);
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
                this.postData.currentPage = this.currentPage;
                this.postData.pageSize = sizeChange;
                this.$store.dispatch( 'loadSiteData', this.postData);
            },
            onBeforeUpload(file){
                this.myData.city = this.city;
                this.myData.importType = this.importType;
                const isCsvOrTxt = file.name.indexOf('.csv') !== -1;
                //const isLt1M = file.size / 1024 / 1024 < 1;

                if (!isCsvOrTxt) {
                    this.$message.error(this.$t('messages.kget.importTip'));
                }
                //if (!isLt1M) {
                    //this.$message.error('上传文件大小不能超过 1MB!');
                //}
                //return isCsvOrTxt && isLt1M;
                return isCsvOrTxt;
            },
            handleSuccess(res,file,fileList) {
                this.$store.dispatch( 'loadSiteData', this.postData);
                if(res){
                    this.$message({
                        message: res,
                        type: 'error'
                    });
                }else {
                    this.$message({
                        message: this.$t('messages.common.uploadSucc'),
                        type: 'success'
                    });
                }
            },
            exportSiteData() {
                var _this = this;
                this.loading.qatExportStatus = true;
                this.$store.dispatch( 'exportSiteData', this.postData).then(function(){
                        _this.exportSite();
                    });
            },
            exportSite() {
                switch( this.$store.getters.getExportSiteDataStatus ) {
                    case 1:
                        this.loading.qatExportStatus = true;
                        break;
                    case 2:
                        this.loading.qatExportStatus = false;
                        this.download(this.$store.getters.getExportSiteData);
                        break;
                    case 3:
                        this.loading.qatExportStatus = false;
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.getExportSiteData,
                            type: 'error'
                        });
                        break;
                    default:
                        this.loading.qatExportStatus = false;
                        break;
                }
            },
            exportTemplateData() {
                this.download('common/template/siteLte_Template.csv');
            }
        },
        computed: {
            myHeader () {
                return {
                    "authToken":window.sessionStorage.getItem('authToken')
                }
            },
            getCityData() {
                switch ( this.$store.getters.qatCityStatus ) {
                    case 2 :
                        this.loading.qatCityStatus = false;
                        var cityData = this.$store.getters.qatCity;
                        if (cityData[0]['value'] == 'allSelect') cityData.shift();
                        this.cityGroup = cityData;
                        this.city = cityData[0].value;
                        break;
                    case 3 :
                        this.loading.qatCityStatus = false;
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.qatCity,
                            type: 'error'
                          });
                        break;
                    default :
                        this.loading.qatCityStatus = false;
                        break;
                }
            },
            getData() {
                switch (this.$store.getters.getLoadSiteDataStatus) {
                    case 1 :
                        this.loading.loadSiteDataStatus = true;
                    break;
                    case 2 :
                        this.loading.loadSiteDataStatus = false;
                        this.tableData =  this.$store.getters.getLoadSiteData.data;
                        this.total = this.$store.getters.getLoadSiteData.total;
                        this.disabled = this.total > 0 ? false : true;
                        break;
                    case 3 :
                        this.loading.loadSiteDataStatus = false;
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.getLoadSiteData,
                            type: 'error'
                          });
                        break;
                    default:
                         this.loading.loadSiteDataStatus = false;
                    break;
                }
            }
        }
    }
</script>