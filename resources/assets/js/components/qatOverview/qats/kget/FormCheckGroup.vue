<style>
   	.el-form-item{
        margin-bottom: 0px;
    }
    .el-col-2{
        padding-right: 10px;
        padding-left: 10px;
    }
    .form {
		padding-bottom: 0px;
	}
</style>
<template>
    <el-container>
	  	<el-aside width="200px">
	  		<el-card class="box-card">
		  		<div slot="header" class="clearfix">
		    		<span>检查项</span>
		  		</div>
			  	<div class="text item">
			    	<el-tree
	              		v-loading="loading.qatItemStatus"
	              		class="filter-tree"
	              		:data="treeData"
	              		:props="defaultProps"
	              		node-key="id"
	              		default-expand-all
	              		highlight-current
	              		:expand-on-click-node="true"
	              		ref="tree"
	              		:options="getItemData"
	              		@node-click="handleNodeClick">
	            	</el-tree>
			  	</div>
			</el-card>
	  	</el-aside>
	  	<el-container style="height: 520px;">
	    	<el-header style="height: 100px;">
	    		<div class="form">
	    			<el-form label-width="80px">
		    			<el-row>
		    				<el-col :span="6">
		                    	<el-form-item horizontal label="省：">
			                        <el-select
			                        	class="full-width"
			                            v-model="province"
			                            filterable
			                            remote
			                            reserve-keyword
			                            placeholder="省"
			                            :loading="loading.qatProvinceStatus"
			                            :showData="getProvinceData"
			                            @visible-change="visible_change_province">
			                            <el-option
			                              v-for="item in provinceOptions"
			                              :key="item.value"
			                              :label="item.label"
			                              :value="item.value">
			                            </el-option>
			                        </el-select>
			                    </el-form-item>
		                    </el-col>
		                    <el-col :span="6">
		                    	<el-form-item horizontal label="运营商：">
			                        <el-select
			                        	class="full-width"
			                            v-model="operator"
			                            filterable
			                            remote
			                            reserve-keyword
			                            placeholder="运营商"
			                            :loading="loading.qatOperatorStatus"
			                            :showData="getOperatorData"
			                            >
			                            <el-option
			                              v-for="item in operatorOptions"
			                              :key="item.value"
			                              :label="item.label"
			                              :value="item.value">
			                            </el-option>
			                        </el-select>
			                    </el-form-item>
		                    </el-col>
		                    <el-col :span="6">
		                    	<el-form-item horizontal label="市：">
			                        <el-select
			                        	class="full-width"
			                            v-model="cities"
			                            filterable
			                            remote
			                            multiple
			                            collapse-tags
			                            reserve-keyword
			                            placeholder="市"
			                            @change="change_city"
			                            :loading="loading.qatCityStatus"
			                            :showData="getCityData">
			                            <el-option
			                              v-for="item in cityOptions"
			                              :key="item.value"
			                              :label="item.label"
			                              :value="item.value">
			                            </el-option>
			                        </el-select>
			                    </el-form-item>
		                    </el-col>
		                    <el-col :span="6">
		                    	<el-form-item horizontal label="日期：">
			                        <el-date-picker
			                          class="full-width"
								      v-model="date"
								      type="date"
								      placeholder="日期"
								      format="yyyy-MM-dd"
								      value-format="yyyy-MM-dd">
								    </el-date-picker>
			                    </el-form-item>
	                    	</el-col>
	                    </el-row>
	                    <el-row >
		                    <el-col :span="6">
		                    	<el-form-item horizontal label="基站：">
			                        <el-input
							            class="full-width"
							            placeholder="基站,英文逗号隔开"
							            v-model="baseStation"
							            clearable>
							        </el-input>
			                    </el-form-item>
		            		</el-col>
		            		<el-col :span="2" :offset="12">
		                        <el-button 
		                          style="width: -webkit-fill-available"
		                          @click.stop="toggleStartIcon($event)"
		                          :loading="loading.qatStartStatus"
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
                                  :option="exportParamData"
		                          type="primary">
		                            <span :class="toogle.exportIcon">导出</span>
		                        </el-button>
		                    </el-col>
	                    </el-row>
                    </el-form>
	    		</div>
	    	</el-header>
	    	<el-main>
	    		<div>
	    			<highChartCheck-group></highChartCheck-group>
	    		</div>
	    		<div >
	            	<tableCheck-group></tableCheck-group>
	        	</div>
	    	</el-main>
	  	</el-container>
	</el-container>
</template>
<script>
	import {Common} from '../../../../mixin/common/Common.js';
	import {CommonKget} from '../../../../mixin/common/CommonKget.js';
	import TableCheckGroup from './TableCheckGroup.vue';
	import HighChartCheckGroup from './HighChartCheckGroup.vue';
	export default {
		mixins: [
			Common,
            CommonKget
        ],
		components : {
			TableCheckGroup,
			HighChartCheckGroup
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
		data () {
			return {
				defaultProps: {
		            children: 'children',
		            label: 'label'
		        },
				loading: {
					qatItemStatus : false,
					qatOperatorStatus : false,
					qatProvinceStatus : false,
					qatCityStatus: false,
					qatStartStatus: false,
					qatExportStatus: false

				},
				toogle: {
					startIcon: true,
					exportIcon: true
				},
				item: '',
				treeData: [],
				operator: '',
				operatorOptions: [],
				province: '',
				provinceOptions: [],
				cities: [],
				cityOptions: [],
				date: '',
				baseStation: ''
			}
		},
		methods : {
			handleNodeClick (data) {
                this.item = data.label;
            },
            change_city: function(citys) {
                this.cities = this.processMutiplySelect(citys, this.cityOptions, this.cities);
            },
            visible_change_province: function(value) {
                if (!value) {
                	this.operator = '';
                	this.operatorOptions = [];
                	this.cities = [];
                	this.cityOptions = [];
                	this.loading.qatOperatorStatus = true;
                	this.province.length == 0 ? this.processLoadNull(this.$message, '省份') : this.processLoadOperator({'province':this.province});
                    this.loading.qatCityStatus = true;
                    this.province.length == 0 ? 
                    this.loading.qatCityStatus = this.processLoadNull(this.$message, '省份')
                    : this.processLoadCity({'province':this.province});
                }    
            },
            //查询
            toggleStartIcon(event) {
            	if ( !this.item ) {
                    this.processLoadNull(this.$message, '检查项');
                    return;
                }
                if ( !this.operator ) {
                    this.processLoadNull(this.$message, '运营商');
                    return;
                }
                if ( !this.province ) {
                    this.processLoadNull(this.$message, '省份');
                    return;
                }
                if ( this.cities.length == 0 ) {
                    this.processLoadNull(this.$message, '城市');
                    return;
                }
                if ( !this.date ) {
                    this.processLoadNull(this.$message, '日期');
                    return;
                }
                this.toogle.startIcon = false;
                this.loading.qatStartStatus = true;
                var postData = {item:this.item,province:this.province,operator:this.operator,cities:this.cities,date:this.date,baseStation: this.baseStation};
                this.$store.dispatch( 'loadQatParamData', postData);
                this.bus.$emit('loadQatParamData', {postData:postData});
            },
            //停止
            cancellation() {
                if(this.toogle.startIcon && this.toogle.exportIcon) {
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
                        if(!this.toogle.startIcon) {
                            this.toogle.startIcon = true;
                            this.loading.qatStartStatus = false;
                        } else if(!this.toogle.exportIcon) {
                            this.toogle.exportIcon = true;
                            this.loading.qatExportStatus = false;
                        }
                        
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消停止'
                        });          
                    });
            },
            //导出
            toggleExportIcon(event) {
            	if ( !this.item ) {
                    this.processLoadNull(this.$message, '检查项');
                    return;
                }
                if ( !this.operator ) {
                    this.processLoadNull(this.$message, '运营商');
                    return;
                }
                if ( !this.province ) {
                    this.processLoadNull(this.$message, '省份');
                    return;
                }
                if ( this.cities.length == 0 ) {
                    this.processLoadNull(this.$message, '城市');
                    return;
                }
                if ( !this.date ) {
                    this.processLoadNull(this.$message, '日期');
                    return;
                }
                this.toogle.exportIcon = false;
                this.loading.qatExportStatus = true;
                var postData = {item:this.item,province:this.province,operator:this.operator,cities:this.cities,date:this.date,baseStation: this.baseStation,dataSource:this.dataSource,dataType:'ALL'};
                this.$store.dispatch( 'exportQatParamData', postData);
            }
		},
		mounted () {
            this.$store.dispatch( 'loadQatParamItemData' );
            this.$store.dispatch( 'loadQatParamProvinceData' );
            this.bus.$on('qatParamStatus', data=> {
                this.toogle.startIcon = data.qatParamStatus;
                this.loading.qatStartStatus = false;
            });
        },
        computed: {
        	getItemData () {
                switch( this.$store.getters.qatParamItemDataStatus ) {
                    case 1:
                        this.loading.qatItemStatus = true;
                        break;
                    case 2:
                        this.loading.qatItemStatus = false;
                        this.treeData = this.$store.getters.qatParamItemData;
                        break;
                    case 3:
                    default:
                        this.loading.qatItemStatus = false;
                        break;
                }
            },
            getOperatorData () {
                switch( this.$store.getters.qatParamOperatorDataStatus ) {
                    case 1:
                        this.loading.qatOperatorStatus = true;
                        break;
                    case 2:
                        this.loading.qatOperatorStatus = false;
                        this.operatorOptions = this.$store.getters.qatParamOperatorData;
                        break;
                    case 3:
                    default:
                        this.loading.qatOperatorStatus = false;
                        break;
                }
            },
            getProvinceData () {
                switch( this.$store.getters.qatParamProvinceDataStatus ) {
                    case 1:
                        this.loading.qatProvinceStatus = true;
                        break;
                    case 2:
                        this.loading.qatProvinceStatus = false;
                        this.provinceOptions = this.$store.getters.qatParamProvinceData;
                        break;
                    case 3:
                    default:
                        this.loading.qatProvinceStatus = false;
                        break;
                }
            },
            getCityData () {
                switch( this.$store.getters.qatParamCityDataStatus ) {
                    case 1:
                        this.loading.qatCityStatus = true;
                        break;
                    case 2:
                        this.loading.qatCityStatus = false;
                        this.cityOptions = this.$store.getters.qatParamCityData;
                        break;
                    case 3:
                    default:
                        this.loading.qatCityStatus = false;
                        break;
                }
            },
            exportParamData() {
                switch( this.$store.getters.exportParamDataStatus ) {
                    case 1:
                        break;
                    case 2:
                        this.toogle.exportIcon = true;
                        this.loading.qatExportStatus = false;
                        var downloadFile = this.$store.getters.exportParamData;
                        this.download(downloadFile);
                        break;
                    case 3:
                    default:
                        break;
                }
            }
        }
	}
</script>