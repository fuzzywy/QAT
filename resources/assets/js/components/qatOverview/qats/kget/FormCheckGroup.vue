<style>
   
</style>
<template>
    <el-container id="check" class="el-container_kget">
	  	<el-aside width="20%">
	  		<el-card class="box-card_kget">
		  		<div slot="header" class="clearfix">
		    		<span>{{$t('messages.kget.item')}}</span>
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
        <el-main>
            <el-card class="box-card_kget">
                    <el-form label-width="80px">
                        <el-row>
                            <el-col :span="8">
                                <el-form-item horizontal :label="this.$t('messages.common.province')">
                                    <el-select
                                        class="full-width"
                                        v-model="province"
                                        filterable
                                        remote
                                        reserve-keyword
                                        :placeholder="this.$t('messages.common.province')"
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
                            <el-col :span="8">
                                <el-form-item horizontal :label="this.$t('messages.common.operator')">
                                    <el-select
                                        class="full-width"
                                        v-model="operator"
                                        filterable
                                        remote
                                        reserve-keyword
                                        :placeholder="this.$t('messages.common.operator')"
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
                            <el-col :span="8">
                                <el-form-item horizontal :label="this.$t('messages.common.city')">
                                    <el-select
                                        class="full-width"
                                        v-model="cities"
                                        filterable
                                        remote
                                        multiple
                                        collapse-tags
                                        reserve-keyword
                                        :placeholder="this.$t('messages.common.city')"
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
                        </el-row>
                        <el-row >
                            <el-col :span="8">
                                <el-form-item horizontal :label="this.$t('messages.common.date')">
                                    <el-date-picker
                                      v-model="date"
                                      type="date"
                                      :placeholder="this.$t('messages.common.date')"
                                      format="yyyy-MM-dd"
                                      value-format="yyyy-MM-dd">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8">
                                <el-form-item horizontal :label="this.$t('messages.common.baseStation')">
                                    <el-input
                                        :placeholder="this.$t('messages.common.baseStationPlaceholder')"
                                        v-model="baseStation"
                                        clearable>
                                    </el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="2" :offset="4">
                                <el-button 
                                  style="width: -webkit-fill-available"
                                  @click.stop="toggleStartIcon($event)"
                                  :loading="loading.qatStartStatus"
                                  type="primary">
                                    <span :class="toogle.startIcon">{{this.$t('messages.common.query')}}</span>
                                </el-button>
                            </el-col>
                            <el-col :span="2">
                                <el-button 
                                  v-show="qatExpIsShow"
                                  style="width: -webkit-fill-available"
                                  @click.stop="toggleExportIcon($event)"
                                  :loading="loading.qatExportStatus"
                                  :option="exportParamData"
                                  type="primary">
                                    <span :class="toogle.exportIcon">{{this.$t('messages.common.export')}}</span>
                                </el-button>
                            </el-col>
                        </el-row>
                    </el-form>
                <div class="text item">
                    <highChartCheck-group></highChartCheck-group>
                    <tableCheck-group v-show="isShow"></tableCheck-group>
                </div>
            </el-card>
        </el-main>
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
				baseStation: '',
                postData: [],

                isShow: false,
                qatExpIsShow: false
			}
		},
		methods : {
			handleNodeClick (data) {
                this.qatExpIsShow = false;
                this.isShow = false;
                this.item = data.label;
                if(this.postData.province){
                    this.toggleStartIcon();
                }
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
                	this.province.length == 0 ? this.processLoadNull(this.$message, 'Province') : this.processLoadOperator({'province':this.province});
                    this.loading.qatCityStatus = true;
                    this.province.length == 0 ? 
                    this.loading.qatCityStatus = this.processLoadNull(this.$message, 'Province')
                    : this.processLoadCity({'province':this.province});
                }    
            },
            //查询
            toggleStartIcon(event) {
            	if ( !this.item ) {
                    this.processLoadNull(this.$message, 'Item');
                    return;
                }
                if ( !this.operator ) {
                    this.processLoadNull(this.$message, 'Operator');
                    return;
                }
                if ( !this.province ) {
                    this.processLoadNull(this.$message, 'Province');
                    return;
                }
                if ( this.cities.length == 0 ) {
                    this.processLoadNull(this.$message, 'City');
                    return;
                }
                if ( !this.date ) {
                    this.processLoadNull(this.$message, 'Date');
                    return;
                }
                this.toogle.startIcon = false;
                this.loading.qatStartStatus = true;
                this.postData = {item:this.item,province:this.province,operator:this.operator,cities:this.cities,date:this.date,baseStation: this.baseStation};
                this.$store.dispatch( 'loadQatParamData', this.postData);
                this.bus.$emit('loadQatParamData', {postData:this.postData});
            },
            //导出
            toggleExportIcon(event) {
            	if ( !this.item ) {
                    this.processLoadNull(this.$message, 'Item');
                    return;
                }
                if ( !this.operator ) {
                    this.processLoadNull(this.$message, 'Operator');
                    return;
                }
                if ( !this.province ) {
                    this.processLoadNull(this.$message, 'Province');
                    return;
                }
                if ( this.cities.length == 0 ) {
                    this.processLoadNull(this.$message, 'City');
                    return;
                }
                if ( !this.date ) {
                    this.processLoadNull(this.$message, 'Date');
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
                this.qatExpIsShow = data.qatExpIsShow;
            });
            this.bus.$on('loadQatParamDetailData', data=> {
                this.isShow = data.isShow;
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
                        this.loading.qatItemStatus = false;
                        break;
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
                        this.loading.qatOperatorStatus = false;
                        break;
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
                        this.loading.qatProvinceStatus = false;
                        break;
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
                        this.loading.qatCityStatus = false;
                        break;
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
                        break;
                    default:
                        break;
                }
            }
        }
	}
</script>