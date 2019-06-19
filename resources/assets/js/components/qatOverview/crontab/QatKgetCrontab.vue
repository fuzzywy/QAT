<style>
    #moTree .el-card__body{
        padding-top: 0px;
    }
</style>
<template>
    <div>
        <el-row>
            <el-col :span="6">
                <el-form label-width="90px">
                    <el-form-item horizontal :label="this.$t('messages.task.taskName')">
                        <el-input
                            style="width:201px"
                            class="full-width"
                            v-model="taskName"
                            @change="change_taskName"
                            :options="checkKgetTaskName"
                            :disabled="taskNameDisabled"
                            >
                        </el-input>
                        <el-input
                            style="display: none;"
                            class="full-width"
                            v-model="taskId"
                            >
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal label="MO">
                        <el-select
                            style="width:201px"
                            class="full-width"
                            v-model="moName"
                            filterable
                            remote
                            reserve-keyword
                            placeholder="MO"
                            :disabled="moNameDisabled"
                            :remote-method="moRemoteMethod"
                            :loading="loading.qatKgetMoListStatus"
                            :showData="getKgetMoListData"
                            @change="change_moName">
                            <el-option
                              v-for="item in kgetMoListOptions"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal :label="this.$t('messages.common.date')">
                        <el-select
                            style="width:201px"
                            class="full-width"
                            v-model="kgetTaskValue"
                            filterable
                            remote
                            reserve-keyword
                            :placeholder="this.$t('messages.common.date')"
                            :remote-method="remoteMethod"
                            @change="change_task"
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
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal :label="this.$t('messages.common.city')">
                        <el-select
                            style="width:201px"
                            class="full-width"
                            v-model="city"
                            :showData="getCityData"
                            @change="change_city"
                            @visible-change="visible_change_city"
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
        </el-row>
        <el-row>
            <el-col :span="6">
                <el-form label-width="90px">
                    <el-form-item horizontal :label="this.$t('messages.common.subNet')">
                        <el-select
                            style="width:201px"
                            class="full-width"
                            v-model="subNet"
                            multiple
                            collapse-tags
                            :placeholder="this.$t('messages.common.subNet')"
                            :showData="getSubNetData"
                            @change="change_subNet">
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
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal :label="this.$t('messages.common.baseStation')">
                        <el-input
                            style="width:201px"
                            class="full-width"
                            v-model="baseStation"
                            clearable>
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal :label="this.$t('messages.common.parameter')">
                        <el-select
                            style="width:201px"
                            class="full-width"
                            v-model="kgetParamValue"
                            multiple
                            filterable
                            remote
                            reserve-keyword
                            collapse-tags
                            :placeholder="this.$t('messages.common.parameter')"
                            :showData="getKgetParam"
                            :remote-method="remoteParamMethod"
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
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal label="Email">
                        <el-input
                            style="width:201px"
                            class="full-width"
                            v-model="email"
                            @change="checkEmail"
                            >
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="6">
                <el-form label-width="90px">
                    <el-form-item horizontal :label="this.$t('messages.kget.pushTime')">
                        <el-date-picker
                          style="width:201px"
                          class="full-width"
                          v-model="pushTime"
                          type="datetime"
                          :placeholder="this.$t('messages.kget.pushTime')"
                          default-time="12:00:00"
                          value-format="yyyy-MM-dd HH:mm:ss">
                        </el-date-picker>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="6">
                <el-form label-width="70px">
                    <el-form-item horizontal :label="this.$t('messages.common.status')">
                        <el-switch
                            v-model="status"
                            active-color="#13ce66"
                            inactive-color="#ff4949"
                            active-text="ON"
                            inactive-text="OFF">
                        </el-switch>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="4" :offset="8">
                <el-button type="primary" @click.stop="addCrontab()" >{{$t('messages.common.init')}}</el-button>
                <el-button type="primary" @click.stop="handleCrontab()" >{{$t('messages.common.ok')}}</el-button>
            </el-col>
        </el-row>
        
        <el-table
            v-loading="loading.qatKgetCrontabTaskDataStatus"
            :data="kgetCrontabTaskdata"
            border
            :options="getKgetCrontabTask"
            style="margin: auto;">
            <el-table-column v-for="(value, key) in kgetCrontabTaskdata[0]" min-width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip>
            </el-table-column >
            <el-table-column align="center" width="180" v-if="total > 0">
                <template slot="header" slot-scope="scope" >
                    Actions
                </template>
                <template slot-scope="scope">
                    <el-button size="mini"
                        @click="handleEdit(scope.$index, scope.row)" :options="insertKgetCrontabTask">modify</el-button>
                    <el-button size="mini" type="danger"
                        @click="handleDelete(scope.$index, scope.row)" :options="deleteKgetCrontabTask">delete</el-button>
                </template>
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
<script>
    import {Common} from '../../../mixin/common/Common.js';
    var moTreeData = [];
    export default {
        name: "ScheduleManage",
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
        data() {
            return {
                defaultProps: {
                  children: 'children',
                  label: 'label'
                },
                loading: {
                    kgetMoLoading: false,
                    qatKgetCrontabTaskDataStatus: false,
                    qatKgetMoListStatus: false
                },
                filterText: '',
                moName : '',
                treeData: [],
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

                kgetMoListValue: '',
                kgetMoListOptions: [],
                kgetMoList: [],

                kgetCrontabTaskdata: [],
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面

                taskId: '',//任务Id
                taskName: '',//任务名称
                taskNameDisabled: false,
                moNameDisabled: false,
                taskNameCount: 0,//库中对应的taskName数量
                email: '',
                pushTime: '',//推送时间
                status: false,
                modify: [],
                delete: [],

                checkEmailFlag: true
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
            change_taskName(taskName) {
                this.$store.dispatch( 'checkKgetTaskName', {taskName:taskName});
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

            change_city: function(citys) {
                this.subNet = [];
                this.subNetGroup = [];
                this.city = this.processMutiplySelect(citys, this.cityGroup, this.city);
            },
            change_task: function(kgetTask){
                this.$store.dispatch( 'loadKgetParamData', {dbName:kgetTask, moName:this.moName});
            },
            visible_change_city: function(value) {
                if (!value) {
                    if(!this.isDisableSN()) return;
                    this.loading.qatSubnetStatus = true;
                    this.city.length == 0 ? 
                    this.loading.qatSubnetStatus = this.processLoadNullCity(this.$message, 'City')
                    : this.processLoadSubnet(this.city, this.dataSource, this.dataType);
                }    
            },

            isDisableSN() {
                return !((this.dataSource == 'ENIQ' &&  this.dataType == 'GSM') || (this.dataSource == "NBM") || (this.dataSource == "ALARM"));
            },

            change_subNet: function(subNets) {
                this.subNet = this.processMutiplySelect(subNets, this.subNetGroup, this.subNet);
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

            checkEmail(email) {
                var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); 
                if(email === ""){
                    this.checkEmailFlag = false;
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.kget.emailNull')
                    });
                　　　return;
                }else if(!reg.test(email)){ 
                    this.checkEmailFlag = false;
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.kget.emailError')
                    });
            　　　　   return;
                }
            },
            init() {
                this.taskNameDisabled = false;
                this.moNameDisabled = false;
                this.taskName = '';
                this.moName = '';
                this.kgetTaskValue = '';
                this.city = [];
                this.subNet = [];
                this.subNetGroup = [];
                this.baseStation = '';
                this.kgetParamValue = [];
                this.email = '';
                this.pushTime = '';
                this.status = false;
                this.kgetMoListOptions = this.kgetMoList;
            },

            addCrontab () {
                this.init();
            },

            handleCrontab() {
                if ( !this.taskName ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.task.taskNameTip'),
                      type: 'warning'
                    });
                    return;
                }
                if ( this.taskNameCount > 0 ) {
                    this.$message({
                            showClose: true,
                            message: this.$t('messages.task.taskNameExists'),
                            type: 'warning'
                    });
                    return;
                }
                if ( !this.moName ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.moTip'),
                      type: 'warning'
                    });
                    return;
                }
                if ( !this.kgetTaskValue ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.dateTip'),
                      type: 'warning'
                    });
                    return;
                }
                if ( !this.email ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.emailNull'),
                      type: 'warning'
                    });
                    return;
                }
                
                if ( !this.checkEmailFlag ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.emailError'),
                      type: 'warning'
                    });
                    return;
                }
                if ( !this.pushTime ) {
                    this.$message({
                      showClose: true,
                      message: this.$t('messages.kget.pushTimeTip'),
                      type: 'warning'
                    });
                    return;
                }
                var postData = {taskId:this.taskId,taskName:this.taskName,moName:this.moName,kgetTaskValue:this.kgetTaskValue,city:this.city,subNet:this.subNet,baseStation: this.baseStation,kgetParamValue:this.kgetParamValue,email:this.email,pushTime:this.pushTime,status:this.status,dataSource:this.dataSource,dataType:this.dataType};
                this.$store.dispatch( 'insertKgetCrontabTask' , postData);
                this.$message({
                    showClose: true,
                    message: this.$t('messages.common.success'),
                    type: 'success'
                });
            },
            //点击编辑
            handleEdit(index, row) {
                this.modify = Object.assign({}, row);
                this.taskId = this.modify.id;
                this.taskName = this.modify.taskName;
                this.taskNameDisabled = true;
                this.moName = this.modify.moName;
                this.moNameDisabled = true;
                this.kgetTaskValue = this.modify.kget;
                this.city = this.modify.city == '' ? [] : this.modify.city.split(',');
                this.subNet = this.modify.subNetwork == '' ? [] : this.modify.subNetwork.split(',');
                this.baseStation = this.modify.meContext;

                this.city.length == 0 ? 
                    this.loading.qatSubnetStatus = false
                    : this.processLoadSubnet(this.city, this.dataSource, this.dataType);
                this.$store.dispatch( 'loadKgetParamData', {dbName:this.kgetTaskValue, moName:this.moName});

                this.kgetParamValue = this.modify.params == '' ? [] : this.modify.params.split(',');
                this.email = this.modify.Email;
                this.pushTime = this.modify.pushTime;
                this.status = this.modify.status == 'ON' ? true : false;
            },
            //点击删除
            handleDelete(index, row) {
                this.delete = Object.assign({}, row);
                this.$confirm("<pre>"+JSON.stringify(this.delete, null, 2)+"</pre>", this.$t('messages.task.deleteConfirm'), {
                    confirmButtonText: this.$t('messages.common.ok'),
                    cancelButtonText: this.$t('messages.common.cancel'),
                    dangerouslyUseHTMLString: true,
                    type:'warning'
                }).then(() => {
                    this.$store.dispatch('deleteKgetCrontabTask', this.delete);
                    this.$message({
                        type: 'success',
                        message: this.$t('messages.common.deleteSuccess')
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.$t('messages.common.Cancelled')
                    });          
                });

            },

            current_change: function(currentPage) {
                this.currentPage = currentPage;
                this.$store.dispatch( 'getKgetCrontabTask', {currentPage:this.currentPage, pageSize:this.pageSize});
            },

            size_change: function(pageSize) {
                this.pageSize = pageSize;
                this.$store.dispatch( 'getKgetCrontabTask', {currentPage:this.currentPage, pageSize:this.pageSize});
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

            change_moName(moName) {
                this.$store.dispatch( 'loadKgetParamData', {dbName:this.kgetTaskValue, moName:moName});
            }
        },
        mounted () {
            this.loading.qatCityStatus = true;
            this.filterText = '';
            this.moName = '';
            this.kgetTaskValue = '';
            this.city = [];
            this.processLoadCitys(this.dataSource, this.dataType);
            this.subNet = [];
            this.subNetGroup = [];
            this.baseStation = '';
            this.kgetParamValue = [];
            this.$store.dispatch( 'loadKgetParamData', {dbName:'', moName:''});
            //this.$store.dispatch( 'loadQatKgetMoData' );
            this.$store.dispatch( 'loadQatKgetTaskData' );
            this.$store.dispatch( 'getKgetCrontabTask',{currentPage:this.currentPage, pageSize:this.pageSize});
            this.$store.dispatch( 'loadQatKgetMoList' );

        },
        computed: {
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
            checkKgetTaskName() {
                switch( this.$store.getters.qatCheckKgetTaskNameStatus ) {
                    case 2:
                        this.taskNameCount = this.$store.getters.qatCheckKgetTaskName;
                        if ( this.taskNameCount > 0 ) {
                            this.$message({
                                    showClose: true,
                                    message: this.$t('messages.task.taskNameExists'),
                                    type: 'warning'
                            });
                            return;
                        }
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
            getKgetCrontabTask() {
                switch( this.$store.getters.qatKgetCrontabTaskDataStatus ) {
                    case 2:
                        this.loading.qatKgetCrontabTaskDataStatus = false;
                        this.kgetCrontabTaskdata = this.$store.getters.qatKgetCrontabTaskData['data'];
                        this.total = this.$store.getters.qatKgetCrontabTaskData['total'];
                        break;
                    case 3:
                        this.loading.qatKgetCrontabTaskDataStatus = false;
                        break;
                    default:
                        this.loading.qatKgetCrontabTaskDataStatus = false;
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
            },

            getKgetMoListData() {
                switch( this.$store.getters.qatKgetMoListDataStatus ) {
                    case 1:
                        this.loading.qatKgetMoListStatus = true;
                        break;
                    case 2:
                        this.loading.qatKgetMoListStatus = false;
                        this.kgetMoList = this.$store.getters.qatKgetMoListData;
                        this.kgetMoListOptions = this.kgetMoList;
                        break;
                    case 3:
                        this.loading.qatKgetMoListStatus = false;
                        break;
                    default:
                        this.loading.qatKgetMoListStatus = false;
                        break;
                }
            },

            insertKgetCrontabTask() {
                switch( this.$store.getters.qatCreateKgetCrontabTaskDataStatus ) {
                    case 2:
                        this.$store.dispatch( 'getKgetCrontabTask',{currentPage:this.currentPage, pageSize:this.pageSize});
                        break;
                }
            },

            deleteKgetCrontabTask() {
                switch( this.$store.getters.qatDeleteKgetCrontabTaskStatus ) {
                    case 2:
                        this.$store.dispatch( 'getKgetCrontabTask',{currentPage:this.currentPage, pageSize:this.pageSize});
                        break;
                }
            }
        }
    }
</script>