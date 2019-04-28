<style>
    .el-form-item{
        margin-bottom: 0px;
    }
    .el-main{
        padding: 0px;
    }
    .el-col-2{
        padding-right: 10px;
        padding-left: 10px;
    }
    .el-input--prefix .el-input__inner{
    	width: 93%;
    }
    .el-dialog__header {
	    padding: 10px 20px 10px;
	}
	.el-dialog__body {
	    padding-top: 0px;
	    padding-bottom: 0px;
	}
	.form {
		padding-bottom: 0px;
	}
</style>
<template>
    <el-container style="height: 180px; ">
        <el-aside width="300px" style="background-color: rgb(238, 241, 246)">
        	<input style="display: none;" :dataSourceType="computedDatasourceType" :kgetData="getKgetData" :exportKgetData="exportKgetData">
            <!--<el-input v-model="filterText" placeholder="Filter keyword">
                <el-button slot="append" icon="el-icon-search" :options="getDataByFilter" @click.stop="handleFilter"></el-button>
            </el-input>-->
            <el-input v-model="filterText" placeholder="Filter keyword">
                <template slot="append"><el-button :disabled="!filterText" :options="getDataByFilter" @click.stop="clearFilter">Clear</el-button></template>
            </el-input>
            <el-tree
              v-loading="kgetMoLoading"
              class="filter-tree"
              :data="treeData"
              :props="defaultProps"
              node-key="id"
              default-expand-all
              highlight-current
              :expand-on-click-node="true"
              ref="tree"
              :options="getData"
              @node-click="handleNodeClick">
            </el-tree>
        </el-aside>
        <el-main>
            <div class="form">
                <el-row>
                    <el-col :span="8">
                    	<el-form label-width="80px">
	                    	<el-form-item horizontal label="日期：">
		                        <el-select
		                        	class="full-width"
		                            v-model="kgetTaskValue"
		                            filterable
		                            remote
		                            reserve-keyword
		                            placeholder="日期"
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
	                    	<el-form-item horizontal label="城市：">
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
						            placeholder="城市">
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
	                    	<el-form-item horizontal label="子网：">
		                        <el-select
						            :disabled="bool.subNet"
						            class="full-width"
						            v-loading="loading.qatSubNetStatus"
						            v-model="subNet"
						            @change="change_subNet"
						            :showData="getSubNetData"
						            multiple
						            collapse-tags
						            placeholder="子网">
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
	                    	<el-form-item horizontal label="基站：">
		                        <el-input
						            class="full-width"
						            :disabled="bool.baseStation"
						            placeholder="请输入基站,英文逗号隔开"
						            v-model="baseStation"
						            clearable>
						        </el-input>
		                    </el-form-item>
	            		</el-form>
            		</el-col>
            		<el-col :span="8">
	                	<el-form label-width="80px">
	                    	<el-form-item horizontal label="参数：">
		                        <el-select
		                        	class="full-width"
		                            v-model="kgetParamValue"
		                            multiple
		                            filterable
		                            remote
		                            reserve-keyword
		                            collapse-tags
		                            placeholder="参数"
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
                </el-row>
                <el-row>
                    <el-col :span="2" :offset="16">
                        <el-button 
                          style="width: -webkit-fill-available"
                          @click.stop="toggleStartIcon($event)"
                          :loading="loading.qatStart"
                          type="primary">
                            <span :class="toogle.startIcon">查询</span>
                        </el-button>
                    </el-col>
                    <el-col :span="2">
                        <el-button 
                          style="width: -webkit-fill-available"
                          @click.stop="cancellation()"   
                          type="primary">
                            <span>停止</span>
                        </el-button>
                    </el-col>
                    <el-col :span="2">
                        <el-button 
                          style="width: -webkit-fill-available"
                          @click.stop="toggleExportIcon($event)"
                          :loading="loading.qatExportStatus"
                          type="primary">
                            <span>导出</span>
                        </el-button>
                    </el-col>
                    <el-col :span="2">
                        <el-button 
                          style="width: -webkit-fill-available"
                          @click.stop="editCrontabTask()"
                          type="primary">
                            定时
                        </el-button>
                    </el-col>
                </el-row>
            </div>
            <el-dialog title="定时"
                :visible.sync="crontabVisible"
                :close-on-click-modal="false"
                :before-close="handleClose"
                :modal-append-to-body="false"
                :append-to-body="true"
                width="80%"
                >

               	<div class="form">
               		<el-row>
               			<el-col :span="9">
                    		<el-form label-width="80px">
		                    	<el-form-item horizontal label="名称：">
			                        <el-input
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
               		</el-row>
                	<el-row>
                    	<el-col :span="9">
                    		<el-form label-width="80px">
		                    	<el-form-item horizontal label="MO：">
                                    <el-select
                                        class="full-width"
                                        v-model="moName"
                                        filterable
                                        remote
                                        reserve-keyword
                                        placeholder="MO"
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
                    	<el-col :span="7">
                    		<el-form label-width="90px">
		                    	<el-form-item horizontal label="日期：">
			                        <!--<el-input
							            class="full-width"
							            disabled
							            v-model="kgetTaskValue"
							            >
							        </el-input>-->
							        <el-select
			                        	class="full-width"
			                            v-model="kgetTaskValue"
			                            filterable
			                            remote
			                            reserve-keyword
			                            placeholder="日期"
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
		                    	<el-form-item horizontal label="城市：">
			                        <el-select
							            class="full-width"
							            v-model="city"
							            @change="change_city"
							            @visible-change="visible_change_city"
							            multiple
							            collapse-tags
							            placeholder="城市">
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
                    	<el-col :span="9">
                    		<el-form label-width="80px">
		                    	<el-form-item horizontal label="子网：">
			                        <el-select
							            class="full-width"
							            v-model="subNet"
							            multiple
							            collapse-tags
							            placeholder="子网"
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
                    	<el-col :span="7">
                    		<el-form label-width="90px">
		                    	<el-form-item horizontal label="基站：">
			                        <el-input
							            class="full-width"
							            v-model="baseStation"
							            clearable>
							        </el-input>
			                    </el-form-item>
		                    </el-form>
                    	</el-col>
                    	<el-col :span="8">
                    		<el-form label-width="80px">
		                    	<el-form-item horizontal label="参数：">
			                        <el-select
			                        	class="full-width"
			                            v-model="kgetParamValue"
			                            multiple
			                            filterable
			                            remote
			                            reserve-keyword
			                            collapse-tags
			                            placeholder="参数"
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
                	</el-row>
                	<el-row>
                    	<el-col :span="9">
                    		<el-form label-width="80px">
		                    	<el-form-item horizontal label="Email：">
			                        <el-input
							            class="full-width"
							            v-model="email"
							            @change="checkEmail"
							            >
							        </el-input>
			                    </el-form-item>
		                    </el-form>
                    	</el-col>
                    	<el-col :span="7">
                    		<el-form label-width="90px">
		                    	<el-form-item horizontal label="推送时间：">
			                        <el-date-picker
			                          class="full-width"
								      v-model="pushTime"
								      type="datetime"
								      placeholder="选择推送时间"
								      default-time="12:00:00"
								      value-format="yyyy-MM-dd HH:mm:ss">
								    </el-date-picker>
			                    </el-form-item>
		                    </el-form>
                    	</el-col>
                    	<el-col :span="8">
                    		<el-form label-width="80px">
		                    	<el-form-item horizontal label="状态：">
			                        <el-switch
									  	v-model="status"
									  	active-color="#13ce66"
									  	inactive-color="#ff4949"
									    active-text="开"
									    inactive-text="关">
									</el-switch>
			                    </el-form-item>
		                    </el-form>
                    	</el-col>
                	</el-row>
               	</div>
               	<div slot="footer" class="dialog-footer">
               		<div >
                        <el-button type="primary" @click.stop="addCrontab()" >新增</el-button>
		                <el-button type="primary" @click.stop="handleCrontab()" >确定</el-button>
		            </div>
               		<el-table
               		v-loading="loading.qatKgetCrontabTaskDataStatus"
		            :data="kgetCrontabTaskdata"
		            max-height="320"
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
			            :page-sizes="[4, 10, 50, 100]"
			            :page-size="4"
			            layout="total, sizes, prev, pager, next, jumper"
			            :total="total">
			        </el-pagination>
               	</div>
               	
            </el-dialog>
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
        data () {
            return {
                filterText: '',
                kgetMoLoading: false,
                treeData: [],
                defaultProps: {
                  children: 'children',
                  label: 'label'
                },
                toogle: {
                    startIcon: true
                },
                loading: {
                    qatKgetTaskStatus: false,
                    qatCityStatus: false,
                    qatSubNetStatus: false,
                    qatKgetParamStatus: false,
                    qatStart: false,
                    qatExportStatus: false,
                    qatKgetCrontabTaskDataStatus: false,
                    qatKgetMoListStatus: false
                },
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
                historyData:[],

                crontabVisible: false,

                kgetMoListValue: '',
                kgetMoListOptions: [],
                kgetMoList: [],

                kgetCrontabTaskdata: [],
                total:0,//默认数据总数
                pageSize:4,//每页的数据条数
                currentPage:1,//默认开始页面

                taskId: '',//任务Id
                taskName: '',//任务名称
                taskNameDisabled: false,
                taskNameCount: 0,//库中对应的taskName数量
                email: '',
                pushTime: '',//推送时间
                status: false,
                modify: [],
                delete: [],

                checkEmailFlag: true

            }
        },
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
                    this.loading.qatSubnetStatus = this.processLoadNullCity(this.$message, '城市')
                    : this.processLoadSubnet(this.city, this.dataSource, this.dataType);
                    /*this.city.length == 0 ? this.subNetGroup = []:console.log('不可能走到这里(当选择模式程序清除tag的时候还是会走到这里哦！)');*/
                }    
            },

            remove_tag: function(value) {
                if(!this.isDisableSN()) return;
                this.loading.qatSubNetStatus = true;
                this.city.length == 0 ? this.loading.qatSubNetStatus = this.processLoadNullCity(this.$message, '城市')
                : this.processLoadSubnet(this.city, this.dataSource, this.dataType);

                /*this.city.length == 0 ? this.subNetGroup = []:console.log('不可能走到这里');*/
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
                      message: '请选择MO',
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                }
                if ( !this.kgetTaskValue ) {
                    this.$message({
                      showClose: true,
                      message: '请选择日期',
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                }
                if ( this.city.length == 0 ) {
                    this.$message({
                      showClose: true,
                      message: '请选择城市',
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                }
                if ( this.toogle.startIcon == false ) {
                    this.$message({
                      showClose: true,
                      message: '重复查询，请结束当前查询',
                      type: 'warning'
                    });
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    return;
                } else {
                    this.toogle.startIcon = false;
                    this.loading.qatStart = true;
                    this.postData = {kgetTaskValue:this.kgetTaskValue,moName:this.moName,city:this.city,subNet:this.subNet,baseStation: this.baseStation,kgetParamValue:this.kgetParamValue,dataSource:this.dataSource,dataType:this.dataType};
                    this.$store.dispatch( 'loadQatKgetData', this.postData);
                    this.bus.$emit('loadQatKgetData', {postData: this.postData});
                }
            },

            cancellation() {
                if(this.toogle.startIcon) {
                    this.$message({
                      type: 'info',
                      message: '没有正在执行的进程！'
                    });
                    return; 
                }
                this.$confirm('此操作将停止当前查询, 是否继续?', '提示', {
                    confirmButtonText: '是',
                    cancelButtonText: '否',
                    type: 'warning'
                    }).then(() => {
                        this.$message({
                            type: 'success',
                            message: '已经停止!'
                        });
                        this.$store.dispatch( 'cancel' );
                        this.toogle.startIcon = true;
                        this.loading.qatStart = false;
                        this.bus.$emit('qatKgetDataStatus', {qatKgetDataStatus: false});
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消停止'
                        });          
                    });
            },

            toggleExportIcon(event) {
                this.loading.qatExportStatus = true;
                this.postData = {kgetTaskValue:this.kgetTaskValue,moName:this.moName,city:this.city,subNet:this.subNet,baseStation: this.baseStation,kgetParamValue:this.kgetParamValue,dataSource:this.dataSource,dataType:this.dataType};
                this.$store.dispatch( 'qatKgetFileData', this.postData);
            },
            //点击关闭dialog
            handleClose(done) {
                this.crontabVisible = false;
                console.log(this.historyData);
                this.moName = this.historyData.moName;
                this.kgetTaskValue = this.historyData.kgetTaskValue;
                this.city = this.historyData.city;
                this.subNet = this.historyData.subNet;
                this.baseStation = this.historyData.baseStation;
                this.kgetParamValue = this.historyData.kgetParamValue;
                this.init();
            },

            change_moName(moName) {
                console.log("change_moName");
                console.log(moName);
                this.$store.dispatch( 'loadKgetParamData', {dbName:this.kgetTaskValue, moName:moName});
            },

            change_taskName(taskName) {
            	console.log(taskName);
            	this.$store.dispatch( 'checkKgetTaskName', {taskName:taskName});
            	console.log(this.taskNameCount);
            },

            checkEmail(email) {
            	var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); //正则表达式
				if(email === ""){ //输入不能为空
                    this.checkEmailFlag = false;
					this.$message({
                        type: 'warning',
                        message: '输入不能为空!'
                    });
				　　　return;
				}else if(!reg.test(email)){ //正则验证不通过，格式不对
                    this.checkEmailFlag = false;
					this.$message({
                        type: 'warning',
                        message: '格式不对!'
                    });
			　　　　   return;
				}
            },
            //设置定时任务
            editCrontabTask() {
                this.historyData = {moName:this.moName,kgetTaskValue:this.kgetTaskValue,city:this.city,subNet:this.subNet,baseStation:this.baseStation,kgetParamValue:this.kgetParamValue};
            	this.crontabVisible = true;
            	this.loading.qatKgetCrontabTaskDataStatus = true;
                this.$store.dispatch( 'loadQatKgetMoList' );
            	this.$store.dispatch( 'getKgetCrontabTask',{currentPage:this.currentPage, pageSize:this.pageSize});
            },

            current_change: function(currentPage) {
                this.currentPage = currentPage;
                this.$store.dispatch( 'getKgetCrontabTask', {currentPage:this.currentPage, pageSize:this.pageSize});
            },

            size_change: function(pageSize) {
                this.pageSize = pageSize;
                this.$store.dispatch( 'getKgetCrontabTask', {currentPage:this.currentPage, pageSize:this.pageSize});
            },
            //点击编辑
            handleEdit(index, row) {
                this.modify = Object.assign({}, row);
                console.log(this.modify);
                this.taskId = this.modify.id;
                this.taskName = this.modify.taskName;
                this.taskNameDisabled = true;
                this.moName = this.modify.moName;
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
                this.$confirm("<pre>"+JSON.stringify(this.delete, null, 2)+"</pre>", '确认删除', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    dangerouslyUseHTMLString: true,
                    type:'warning'
                }).then(() => {
                    this.$store.dispatch('deleteKgetCrontabTask', this.delete);
                    this.$message({
                        type: 'success',
                        message: '删除成功!'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });          
                });

            },

            init() {
                this.taskNameDisabled = false;
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
            },

            addCrontab () {
                this.init();
            },

            handleCrontab() {
            	if ( !this.taskName ) {
                    this.$message({
                      showClose: true,
                      message: '请填写任务名称',
                      type: 'warning'
                    });
                    return;
                }
                if ( this.taskNameCount > 0 ) {
            		this.$message({
		                    showClose: true,
		                    message: '任务名称已存在',
		                    type: 'warning'
		            });
		            return;
		        }
		        if ( !this.moName ) {
                    this.$message({
                      showClose: true,
                      message: '请选择MO',
                      type: 'warning'
                    });
                    return;
                }
		        if ( !this.kgetTaskValue ) {
                    this.$message({
                      showClose: true,
                      message: '请选择日期',
                      type: 'warning'
                    });
                    return;
                }
		        if ( !this.email ) {
                    this.$message({
                      showClose: true,
                      message: '请填写邮箱地址',
                      type: 'warning'
                    });
                    return;
                }
            	
                if ( !this.checkEmailFlag ) {
                    this.$message({
                      showClose: true,
                      message: '邮箱格式有误',
                      type: 'warning'
                    });
                    return;
                }
                if ( !this.pushTime ) {
                    this.$message({
                      showClose: true,
                      message: '请选择推送时间',
                      type: 'warning'
                    });
                    return;
                }
                var postData = {taskId:this.taskId,taskName:this.taskName,moName:this.moName,kgetTaskValue:this.kgetTaskValue,city:this.city,subNet:this.subNet,baseStation: this.baseStation,kgetParamValue:this.kgetParamValue,email:this.email,pushTime:this.pushTime,status:this.status,dataSource:this.dataSource,dataType:this.dataType};
                this.$store.dispatch( 'insertKgetCrontabTask' , postData);
                this.$message({
                    showClose: true,
                    message: '修改成功',
                    type: 'success'
                });
            }
        },

        mounted () {
            this.$store.dispatch( 'loadQatKgetMoData' );
            this.$store.dispatch( 'loadQatKgetTaskData' );
        },

        computed: {

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
                        this.kgetMoLoading = true;
                        break;
                    case 2:
                        this.kgetMoLoading = false;
                        moTreeData = this.$store.getters.qatKgetMoData;
                        this.treeData = this.$store.getters.qatKgetMoData;
                        break;
                    case 3:
                        this.kgetMoLoading = false;
                        break;
                    default:
                        this.kgetMoLoading = false;
                        break;
                }
            },

            getDataByFilter () {
                switch( this.$store.getters.qatKgetMoByParamFilterDataStatus ) {
                    case 1:
                        this.kgetMoLoading = true;
                        break;
                    case 2:
                        this.kgetMoLoading = false;
                        this.treeData = this.$store.getters.qatKgetMoByParamFilterData;
                        break;
                    case 3:
                        this.kgetMoLoading = false;
                        break;
                    default:
                        this.kgetMoLoading = false;
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
	        },

            getKgetData() {
                switch( this.$store.getters.qatKgetDataStatus ) {
                  case 1:
                    break;
                  case 2:
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    break;
                  case 3:
                    break;
                  default:
                    this.toogle.startIcon = true;
                    this.loading.qatStart = false;
                    break;
                }
            },

            exportKgetData() {
                if (this.kgetTaskValue == '') return;
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
                                message: '导出成功'
                            }); 
                            this.loading.qatExportStatus = false;
                        } else if (browerInfo == "firefox") {
                            this.download_firefox(downloadFile);
                            this.$message({
                                type: 'info',
                                message: '导出成功'
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
            checkKgetTaskName() {
            	switch( this.$store.getters.qatCheckKgetTaskNameStatus ) {
                  	case 2:
                  		this.taskNameCount = this.$store.getters.qatCheckKgetTaskName;
	                  	if ( this.taskNameCount > 0 ) {
		            		this.$message({
				                    showClose: true,
				                    message: '任务名称已存在',
				                    type: 'warning'
				            });
				            return;
				        }
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
            },

            getKgetMoListData() {
                console.log('getKgetMoListData');
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
            }
            
        }
    }
</script>