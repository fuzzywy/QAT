<style>
    #moTree .el-card__body{
        padding-top: 0px;
    }
</style>
<template>
    <el-container class="el-container_kget">
        <el-aside width="300px">
            <el-card id="moTree" class="box-card_kget">
                <input style="display: none;" :dataSourceType="computedDatasourceType">
                <el-input v-model="filterText" placeholder="Filter keyword">
                    <template slot="append"><el-button :disabled="!filterText" :options="getDataByFilter" @click.stop="clearFilter">Clear</el-button></template>
                </el-input>
                <el-tree
                  v-loading="loading.kgetMoLoading"
                  class="filter-tree"
                  :data="treeData"
                  :props="defaultProps"
                  node-key="id"
                  default-expand-all
                  highlight-current
                  :expand-on-click-node="false"
                  ref="tree"
                  :options="getData"
                  @node-click="handleNodeClick">
                </el-tree>
            </el-card>
        </el-aside>
        <el-main>
            <el-card class="box-card_kget">
                <el-row>
                    <el-col :span="8">
                        <el-form label-width="80px">
                            <el-form-item horizontal :label="this.$t('messages.common.date')">
                                <el-select
                                    class="full-width"
                                    v-model="kgetTaskValue"
                                    filterable
                                    remote
                                    reserve-keyword
                                    :placeholder="this.$t('messages.common.date')"
                                    :remote-method="remoteMethod"
                                    :loading="loading.qatKgetTaskStatus"
                                    :showData="getKgetTask">
                                    <el-option
                                      v-for="item in kgetTaskOptions"
                                      :key="item.value"
                                      :label="item.label"
                                      :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                    </el-col>
                    <el-col :span="8">
                        <el-form label-width="80px">
                            <el-form-item horizontal :label="this.$t('messages.common.city')">
                                <el-select
                                    class="full-width"
                                    v-loading="loading.qatCityStatus"
                                    v-model="city"
                                    :showData="getCityData"
                                    @change="change_city"
                                    @visible-change="visible_change_city"
                                    @remove-tag="remove_tag"
                                    multiple
                                    collapse-tags
                                    :placeholder="this.$t('messages.common.city')">
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
                    <el-col :span="8">
                        <el-form label-width="80px">
                            <el-form-item horizontal :label="this.$t('messages.common.subNet')">
                                <el-select
                                    :disabled="bool.subNet"
                                    class="full-width"
                                    v-loading="loading.qatSubNetStatus"
                                    v-model="subNet"
                                    @change="change_subNet"
                                    :showData="getSubNetData"
                                    multiple
                                    collapse-tags
                                    :placeholder="this.$t('messages.common.subNet')">
                                    <el-option
                                      v-for="item in subNetGroup"
                                      :key="item.value"
                                      :label="item.label"
                                      :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8">
                        <el-form label-width="80px">
                            <el-form-item horizontal :label="this.$t('messages.common.baseStation')">
                                <el-input
                                    class="full-width"
                                    :disabled="bool.baseStation"
                                    :placeholder="this.$t('messages.common.baseStationPlaceholder')"
                                    v-model="baseStation"
                                    clearable>
                                </el-input>
                            </el-form-item>
                        </el-form>
                    </el-col>
                    <el-col :span="8">
                        <el-form label-width="80px">
                            <el-form-item horizontal :label="this.$t('messages.common.parameter')">
                                <el-select
                                    class="full-width"
                                    v-model="kgetParamValue"
                                    multiple
                                    filterable
                                    remote
                                    reserve-keyword
                                    collapse-tags
                                    :placeholder="this.$t('messages.common.parameter')"
                                    :remote-method="remoteParamMethod"
                                    :loading="loading.qatKgetParamStatus"
                                    :showData="getKgetParam"
                                    @change="change_param">
                                    <el-option
                                      v-for="item in kgetParamOptions"
                                      :key="item.value"
                                      :label="item.label"
                                      :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                    </el-col>
                    <el-col :span="2" :offset="3">
                        <el-button 
                          style="width: -webkit-fill-available"
                          @click.stop="toggleStartIcon($event)"
                          :loading="loading.qatStart"
                          type="primary">
                            <span :class="toogle.startIcon">{{$t("messages.common.query")}}</span>
                        </el-button>
                    </el-col>
                    <el-col :span="2">
                        <el-button 
                          style="width: -webkit-fill-available"
                          @click.stop="toggleExportIcon($event)"
                          :loading="loading.qatExportStatus"
                          type="primary"
                          v-show="isShow">
                            <span>{{$t("messages.common.export")}}</span>
                        </el-button>
                    </el-col>
                </el-row>
                <el-table
                    v-loading="loading.qatKgetDataStatus"
                    :data="data"
                    border
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
            </el-card>
        </el-main>
    </el-container>
</template>

<script>
    import {Common} from '../../../../mixin/common/Common.js'; 
    var moTreeData = [];
    export default {
        mixins: [
            Common
        ],
        props: {
            dataSource: {
                type: String,
                default: "TDD"
            },
            dataType: {
                type: String,
                default: "KGET"
            }
        },
        data () {
             return {
                loading: {
                    qatKgetTaskStatus: false,
                    qatCityStatus: false,
                    qatSubNetStatus: false,
                    qatKgetParamStatus: false,
                    qatStart: false,
                    qatExportStatus: false,
                    qatKgetCrontabTaskDataStatus: false,
                    qatKgetMoListStatus: false,
                    kgetMoLoading: false,
                    qatKgetDataStatus: false
                },
                
                defaultProps: {
                  children: 'children',
                  label: 'label'
                },
                toogle: {
                    startIcon: true
                },
                filterText: '',
                treeData: [],
                bool: {
                    subNet: false,
                    baseStation: false
                },
                kgetTaskValue: '',
                kgetTaskOptions: [],
                kgetTaskList: [],

                city:[],
                cityGroup: [],

                subNet: [],
                subNetGroup: [],

                baseStation: '',

                kgetParamValue: [],
                kgetParamOptions: [],
                kgetParamList: [],

                moName : '',
                postData: [],

                isShow: false,
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面
                data: []

            }
        },
        watch: {
            filterText(val) {
                if(val =='' ) {
                    this.treeData = moTreeData;
                } else {
                    this.filterText = val;
                    this.$store.dispatch( 'loadMoByParamFilterData', {filterText:this.filterText});
                }
            }
        },
        methods: {
            clearFilter () {
                this.filterText = '';
            },

            handleNodeClick (data) {
                this.moName = data.label;
                this.$store.dispatch( 'loadKgetParamData', {dbName:this.kgetTaskValue, moName:data.label});
            },

            remoteMethod(query) {
                if (query !== '') {
                    this.loading.qatKgetTaskStatus = true;
                    setTimeout(() => {
                        this.loading.qatKgetTaskStatus = false;
                        this.kgetTaskOptions = this.kgetTaskList.filter(item => {
                            return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
                        });
                    }, 200);  
                } else {
                    this.kgetTaskOptions = this.kgetTaskList;
                }
            },

            moRemoteMethod(query) {
                if (query !== '') {
                    this.loading.qatKgetMoListStatus = true;
                    setTimeout(() => {
                        this.loading.qatKgetMoListStatus = false;
                        this.kgetMoListOptions = this.kgetMoList.filter(item => {
                            return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
                        });
                    }, 200);  
                } else {
                    this.kgetMoListOptions = this.kgetMoList;
                }
            },

            change_city: function(citys) {
                this.subNet = [];
                this.subNetGroup = [];
                this.city = this.processMutiplySelect(citys, this.cityGroup, this.city);
            },

            visible_change_city: function(value) {
                if (!value) {
                    if(!this.isDisableSN()) return;
                    this.loading.qatSubnetStatus = true;
                    this.city.length == 0 ? 
                    this.loading.qatSubnetStatus = this.$message.warning(this.$t('messages.common.loadNullCity'))
                    : this.processLoadSubnet(this.city, this.dataSource, this.dataType);
                }    
            },

            remove_tag: function(value) {
                if(!this.isDisableSN()) return;
                this.loading.qatSubNetStatus = true;
                this.city.length == 0 ? this.loading.qatSubNetStatus = this.$message.warning(this.$t('messages.common.loadNullCity'))
                : this.processLoadSubnet(this.city, this.dataSource, this.dataType);
            },

            change_subNet: function(subNets) {
                this.subNet = this.processMutiplySelect(subNets, this.subNetGroup, this.subNet);
            },

            isDisableSN() {
                return !((this.dataSource == 'ENIQ' &&  this.dataType == 'GSM') || (this.dataSource == "NBM") || (this.dataSource == "ALARM"));
            },

            remoteParamMethod(query) {
                if (query !== '') {
                    this.loading.qatKgetParamStatus = true;
                    setTimeout(() => {
                        this.loading.qatKgetParamStatus = false;
                        this.kgetParamOptions = this.kgetParamList.filter(item => {
                            return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
                        });
                    }, 200);
                } else {
                    this.kgetParamOptions = this.kgetParamList;
                }
            },

            change_param: function(params) {
                this.kgetParamValue = this.processMutiplySelect(params, this.kgetParamOptions, this.kgetParamValue);
            },
            //查询
            toggleStartIcon(event) {
                if ( !this.moName ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.moTip'),
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                }
                if ( !this.kgetTaskValue ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.dateTip'),
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                }
                if ( this.city.length == 0 ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.cityTip'),
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                }
                
                this.loading.qatStart = true;
                this.postData = {kgetTaskValue:this.kgetTaskValue,moName:this.moName,city:this.city,subNet:this.subNet,baseStation: this.baseStation,kgetParamValue:this.kgetParamValue,dataSource:this.dataSource,dataType:this.dataType};
                var _this = this;
                this.$store.dispatch( 'loadQatKgetData', this.postData).then(function(){
                        _this.getKgetData();
                    });
            },

            getKgetData() {
                switch( this.$store.getters.qatKgetDataStatus ) {
                    case 1:
                        this.loading.qatKgetDataStatus = true;
                    break;
                    case 2:
                        this.toogle.startIcon = true;
                        this.loading.qatStart = false;
                        this.loading.qatKgetDataStatus = false;
                        this.data = this.$store.getters.qatKgetData['data'];
                        this.total = this.$store.getters.qatKgetData['total'];
                        if(this.total > 0) this.isShow = true;
                        else this.isShow = false;

                    break;
                    case 3:
                    default:
                        this.toogle.startIcon = true;
                        this.loading.qatStart = false;
                    break;
                }
            },
            toggleExportIcon(event) {
                var _this = this;
                this.loading.qatExportStatus = true;
                this.postData = {kgetTaskValue:this.kgetTaskValue,moName:this.moName,city:this.city,subNet:this.subNet,baseStation: this.baseStation,kgetParamValue:this.kgetParamValue,dataSource:this.dataSource,dataType:this.dataType};
                this.$store.dispatch( 'qatKgetFileData', this.postData).then(function(){
                        _this.exportKgetData();
                    });
            },

            exportKgetData() {
                switch( this.$store.getters.qatKgetFileDataStatus ) {
                    case 1:
                        this.loading.qatExportStatus = true;
                        break;
                    case 2:
                        var downloadFile = this.$store.getters.qatKgetFileData;
                        var uerAgent = navigator.userAgent.toLowerCase();
                        var format = /(msie|firefox|chrome|opera|version).*?([\d.]+)/;
                        var matches = uerAgent.match(format);
                        var browerInfo = matches[1].replace(/version/, "'safari");
                        if (browerInfo == "chrome") {
                            this.download_chrome(downloadFile);
                            this.$message({
                                type: 'info',
                                message: this.$t('messages.common.exportSucc')
                            }); 
                            this.loading.qatExportStatus = false;
                        } else if (browerInfo == "firefox") {
                            this.download_firefox(downloadFile);
                            this.$message({
                                type: 'info',
                                message: this.$t('messages.common.exportSucc')
                            });
                            this.loading.qatExportStatus = false;
                        }
                        break;
                    case 3:
                        this.loading.qatExportStatus = false;
                        break;
                    default:
                        this.loading.qatExportStatus = false;
                        break;
                }
            },

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
        mounted () {
            this.$store.dispatch( 'loadQatKgetMoData' );
            this.$store.dispatch( 'loadQatKgetTaskData' );
        },
        computed:{
            computedDatasourceType() {
                this.loading.qatCityStatus = true;
                this.filterText = '';
                this.moName = '';
                this.kgetTaskValue = '';
                this.city = [];
                this.processLoadCitys(this.dataSource, this.dataType);
                this.bool.subNet = !this.isDisableSN();
                this.subNet = [];
                this.subNetGroup = [];
                this.baseStation = '';
                this.kgetParamValue = [];
                this.$store.dispatch( 'loadKgetParamData', {dbName:'', moName:''});
                this.bus.$emit('loadQatKgetData',{postData:{reload:'reload'}});
            },

            getData () {
                switch( this.$store.getters.qatKgetMoDataStatus ) {
                    case 1:
                        this.loading.kgetMoLoading = true;
                        break;
                    case 2:
                        this.loading.kgetMoLoading = false;
                        moTreeData = this.$store.getters.qatKgetMoData;
                        this.treeData = this.$store.getters.qatKgetMoData;
                        break;
                    case 3:
                        this.loading.kgetMoLoading = false;
                        break;
                    default:
                        this.loading.kgetMoLoading = false;
                        break;
                }
            },

            getDataByFilter () {
                switch( this.$store.getters.qatKgetMoByParamFilterDataStatus ) {
                    case 1:
                        this.loading.kgetMoLoading = true;
                        break;
                    case 2:
                        this.loading.kgetMoLoading = false;
                        this.treeData = this.$store.getters.qatKgetMoByParamFilterData;
                        break;
                    case 3:
                        this.loading.kgetMoLoading = false;
                        break;
                    default:
                        this.loading.kgetMoLoading = false;
                        break;
                }
            },

            getKgetTask () {
                switch( this.$store.getters.qatKgetTaskDataStatus ) {
                    case 1:
                        this.loading.qatKgetTaskStatus = true;
                        break;
                    case 2:
                        this.loading.qatKgetTaskStatus = false;
                        this.kgetTaskList = this.$store.getters.qatKgetTaskData;
                        this.kgetTaskOptions = this.kgetTaskList;
                        break;
                    case 3:
                        this.loading.qatKgetTaskStatus = false;
                        break;
                    default:
                        this.loading.qatKgetTaskStatus = false;
                        break;
                }
            },
              //获取城市数据
            getCityData() {
                switch ( this.$store.getters.qatCityStatus ) {
                    case 2 :
                        this.loading.qatCityStatus = false;
                        this.cityGroup = this.$store.getters.qatCity;
                        break;
                    case 3 :
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.qatCity,
                            type: 'error'
                          });
                        break;
                }
            },
            //获取子网数据
            getSubNetData() {
                switch ( this.$store.getters.qatSubnetStatus ) {
                    case 2 :
                        this.loading.qatSubNetStatus = false;
                        this.subNetGroup = this.$store.getters.qatSubnet;
                        break;
                    case 3 :
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.qatSubnet,
                            type: 'error'
                          });
                        break;
                }
            },
            //获取选中mo对应的参数列表
            getKgetParam() {
                switch( this.$store.getters.qatKgetParamDataStatus ) {
                    case 1:
                        this.loading.qatKgetParamStatus = true;
                        break;
                    case 2:
                        this.loading.qatKgetParamStatus = false;
                        this.kgetParamList = this.$store.getters.qatKgetParamData;
                        this.kgetParamOptions = this.kgetParamList;
                        break;
                    case 3:
                        this.loading.qatKgetParamStatus = false;
                        break;
                    default:
                        this.loading.qatKgetParamStatus = false;
                        break;
                }
            }
        }
    }
</script>