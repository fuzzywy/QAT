<style>
    .form {
        position: relative;
        top: 10px;
        text-align: center;
        z-index: 999;
        padding-left: 15px;
        padding-right: 15px;
    }
    .full-width {
      width: 100%;
    }
    .el-date-editor--daterange.el-input, .el-date-editor--daterange.el-input__inner, .el-date-editor--timerange.el-input, .el-date-editor--timerange.el-input__inner {
      width: 100%;
    }
    .line {
        /*border: 0.5px solid #dcdfe6;*/
        margin: 5.6px 5px 5px 5px;
    }
</style>
<template>
    <div class="form">
      <input style="display: none;" :dataSourceType="computedDatasourceType" :options="getdata">
      <el-row :gutter="20">
        <el-col :span="8">
          <el-select 
            :disabled="bool.kpitemplate"
            class="full-width"
            filterable
            remote
            v-loading="loading.qatTemplateStatus"
            v-model="template"
            :showData="getTemplateData"
            collapse-tags
            placeholder="模板">
            <el-option
              v-for="item in templateGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="8">
          <el-select
            class="full-width"
            v-loading="loading.qatTimeStatus"
            v-model="time"
            :showData="showTimeData"
            @visible-change="visible_change_time"
            collapse-tags
            placeholder="时间维度">
            <el-option
              v-for="item in timeGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="8">
          <el-select
            class="full-width"
            v-loading="loading.qatLocationStatus"
            v-model="location"
            :showData="showLocationData"
            @visible-change="visible_change_location"
            collapse-tags
            placeholder="地域维度">
            <el-option
              v-for="item in locationGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
      </el-row>
      <div class="line"></div>
      <el-row :gutter="20">
        <el-col :span="6">
          <el-select
            class="full-width"
            v-loading="loading.qatCityStatus"
            v-model="city"
            :showData="getCityData"
            @change="change_city"
            @visible-change="visible_change_city"
            @remove-tag="remove_tag"
            multiple
            placeholder="城市">
            <el-option
              v-for="item in cityGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="6">
          <el-select
            :disabled="bool.subnet"
            class="full-width"
            v-loading="loading.qatSubnetStatus"
            v-model="subnet"
            @change="change_subnet"
            :showData="getSubnetData"
            multiple
            collapse-tags
            placeholder="子网">
            <el-option
              v-for="item in subnetGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="6">
          <el-input
            class="full-width"
            :disabled="bool.baseStation"
            placeholder="请输入基站,英文逗号隔开"
            v-model="baseStation"
            clearable>
          </el-input>
        </el-col>
        <el-col :span="6">
          <el-input
            class="full-width"
            :disabled="bool.cell"
            placeholder="请输入小区,英文逗号隔开"
            v-model="cell"
            clearable>
          </el-input>
        </el-col>
      </el-row>
      <div class="line"></div>
      <el-row :gutter="20">
        <el-col :span="12">
          <el-date-picker
            :disabled="bool.chooseTime"
            value-format="yyyy-MM-dd"
            v-model="date"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期">
          </el-date-picker>
        </el-col>
        <el-col :span="6">
          <el-select
            :disabled="bool.hour"
            class="full-width"
            v-model="hour"
            @change="change_hour"
            multiple
            collapse-tags
            placeholder="请选择小时">
            <el-option
              v-for="item in hourGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
        <el-col :span="6">
          <el-select
            :disabled="bool.minute"
            class="full-width"
            v-model="minute"
            @change="change_minute"
            multiple
            placeholder="请选择15分钟">
            <el-option
              v-for="item in minuteGroup"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-col>
      </el-row>
      <div class="line"></div>
        <!-- <el-row :gutter="20">
          <el-col :span="6">
            <el-select
              class="full-width"
              v-model="crontab"
              multiple
              collapse-tags
              placeholder="定时任务">
              <el-option
                v-for="item in crontabGroup"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-select
              class="full-width"
              v-model="notice"
              multiple
              collapse-tags
              placeholder="通知">
              <el-option
                v-for="item in noticeGroup"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </el-col> -->
        <el-row :gutter="20">
          <el-col :span="2" :offset="18">
            <el-button 
              style="width: -webkit-fill-available"
              @click.stop="toggleStartIcon($event)"
              :loading="loading.qatStart"
              type="primary">
                <!-- style="background-color: white; border: 0px; width: auto; padding: 0; " -->  
                <span :class="toogle.startIcon">查询</span>
                <!-- <i style="font-size: xx-large; color: #6c757d;" 
                    :class="toogle.startIcon? 'icon-ali-kaishi': 'icon-ali-222'">
                </i> -->
            </el-button>
          </el-col>
          <el-col :span="2">
            <el-button 
              style="width: -webkit-fill-available"
              @click.stop="cancellation()"   
              type="primary">
                <span>停止</span>
               <!--  <i style="font-size: xx-large; color: #6c757d;" 
                    class="icon-ali-stopit">
                </i> -->
            </el-button>
          </el-col>
          <el-col :span="2">
            <el-button 
              style="width: -webkit-fill-available"
              @click.stop="toggleExportIcon($event)"
              :loading="loading.qatExport"
              type="primary">
                <!-- style="background-color: white; border: 0px; width: auto; padding: 0; " -->  
                <span>导出</span>
                <!-- <i style="font-size: xx-large; color: #6c757d;" 
                    :class="toogle.startIcon? 'icon-ali-kaishi': 'icon-ali-222'">
                </i> -->
            </el-button>
          </el-col>
        </el-row>
    </div>
</template>
<script>
    import {Common} from '../../../mixin/common/Common.js'; 
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
                default: "ENIQ"
            }
        },
        data () {
            return {
              downloadfiles: '',
              toogle: {
                startIcon: true
              },
              bool: {
                subnet: false,
                hour: false,
                minute: false,
                baseStation: false,
                cell: false,
                kpitemplate: false,
                chooseTime: false
              },
              loading: {
                qatSubnetStatus: false,
                qatTemplateStatus: false,

                qatCityStatus: false,
                qatTimeStatus: false,
                qatLocationStatus: false,

                qatStart:false
              },
              template: [],
              time: [],
              location: [],
              city: [],
              subnet: [],
              baseStation: '',
              cell: '',
              date: '',
              hour: [],
              minute: [],
              crontab: [],
              notice: [],
              hourGroup: [
                { value: 'allSelect', label: '全选' },
                { value: '0', label: '0' }, 
                { value: '1', label: '1' }, 
                { value: '2', label: '2' }, 
                { value: '3', label: '3' }, 
                { value: '4', label: '4' }, 
                { value: '5', label: '5' }, 
                { value: '6', label: '6' },
                { value: '7', label: '7' }, 
                { value: '8', label: '8' }, 
                { value: '9', label: '9' }, 
                { value: '10', label: '10' }, 
                { value: '11', label: '11' }, 
                { value: '12', label: '12' }, 
                { value: '13', label: '13' }, 
                { value: '14', label: '14' }, 
                { value: '15', label: '15' }, 
                { value: '16', label: '16' }, 
                { value: '17', label: '17' }, 
                { value: '18', label: '18' }, 
                { value: '19', label: '19' }, 
                { value: '20', label: '20' }, 
                { value: '21', label: '21' }, 
                { value: '22', label: '22' }, 
                { value: '23', label: '23' }
              ],
              minuteGroup: [
                { value: 'allSelect', label: '全选' },
                { value: '00', label: '00' },
                { value: '15', label: '15' },
                { value: '30', label: '30' },
                { value: '45', label: '45' }
              ],
              crontabGroup: [
                { value: '111', label: '111' }
              ],
              noticeGroup: [
                { value: 'lijian', label: 'lijian' }
              ],
              templateGroup: [
                { value: 'test1', label: 'test1111111111111111111111111111sadddddddddddddddddddaaaaaaaaaaaa' },
                { value: 'test2', label: 'test2' },
                { value: 'test3', label: 'test3' },
                { value: 'test4', label: 'test4' }
              ],
              timeGroup: [
                { value: 'day', label: '天' },
                { value: 'dayGroup', label: '天组' },
                { value: 'hour', label: '小时' },
                { value: 'hourGroup', label: '小时组' },
                { value: 'quarter', label: '15分钟' },
              ],
              locationGroup: [
                { value: 'city', label: '城市' },
                { value: 'subnet', label: '子网' },
                { value: 'subnetGroup', label: '子网组' },
                { value: 'baseStation', label: '基站' },
                { value: 'baseStationGroup', label: '基站组' },
                { value: 'cell', label: '小区' },
                { value: 'cellGroup', label: '小区组' },
              ],
              cityGroup: [
                { value: 'allSelect', label: '全选/全不选' },
                { value: 'changzhou', label: '常州' },
                { value: 'suzhou', label: '苏州' },
                { value: 'nantong', label: '南通' },
                { value: 'wuxi', label: '无锡' },
                { value: 'zhenjiang', label: '镇江' },
              ],
              subnetGroup: [
                { value: 'allSelect', label: '全选' },
              ],
              show: true
            }
        },
        methods: {
            isDisableSN() {
              return !((this.dataSource == 'ENIQ' &&  this.dataType == 'GSM') || (this.dataSource == "NBM") || (this.dataSource == "ALARM"));
            },
            isDisableTime() {
              return !(this.$store.state.qatData.alarmTime == "Current" && this.dataSource == 'ALARM' );
            },
            isDisableTemplate() {
              return !(this.dataSource == "ALARM");
            },
            change_city: function(citys) {
                this.city = this.processMutiplySelect(citys, this.cityGroup, this.city);
            },
            change_subnet: function(subnets) {
                this.subnet = this.processMutiplySelect(subnets, this.subnetGroup, this.subnet);
            },
            change_hour: function(hours) {
                this.hour = this.processMutiplySelect(hours, this.hourGroup, this.hour);
            },
            change_minute: function(minutes) {
                this.minute = this.processMutiplySelect(minutes, this.minuteGroup, this.minute);
            },
            visible_change_city: function(value) {
                if (!value) {
                  if(!this.isDisableSN()) return;
                  this.loading.qatSubnetStatus = true;
                  this.city.length == 0? 
                    this.loading.qatSubnetStatus = this.processLoadNullCity(this.$message, '城市')
                   : this.processLoadSubnet(this.city, this.dataSource, this.dataType);
                  this.city.length == 0?this.subnetGroup = []:console.log('不可能走到这里(当选择模式程序清除tag的时候还是会走到这里哦！)');
                }    
            },
            remove_tag: function(value) {
              if(!this.isDisableSN()) return;
              this.loading.qatSubnetStatus = true;
              this.city.length == 0? this.loading.qatSubnetStatus = this.processLoadNullCity(this.$message, '城市')
               : this.processLoadSubnet(this.city, this.dataSource, this.dataType);

              this.city.length == 0?this.subnetGroup = []:console.log('不可能走到这里');
            },
            visible_change_time: function(value) {
              if (!value) {
                if (this.time.length != 0) {
                  switch( this.time ) {
                    case 'day':
                    case 'dayGroup':
                      this.hour = [];
                      this.minute = [];
                      this.bool.hour = true;
                      this.bool.minute = true;
                    break;
                    case 'hour':
                    case 'hourGroup':
                      this.minute = [];
                      this.bool.hour = false;
                      this.bool.minute = true;
                    break;
                    case 'quarter':
                      this.bool.hour = false;
                      this.bool.minute = false;
                    break;
                    default:
                      this.bool.hour = false;
                    break;
                  }
                }
              }
            },
            visible_change_location: function(value) {
              if (!value) {
                if ( this.location.length != 0 ) {
                  switch( this.location ) {
                    case 'city':
                    case 'subnet':
                    case 'subnetGroup':
                      this.baseStation = '';
                      this.cell = '';
                      this.bool.baseStation = true;
                      this.bool.cell = true;
                    break;
                    case 'baseStation':
                    case 'baseStationGroup':
                      this.cell = '';
                      this.bool.baseStation = false;
                      this.bool.cell = true;
                    break;
                    case 'cell':
                      this.baseStation = '';
                      this.bool.baseStation = false;
                      this.bool.cell = false;
                      break;
                    case 'cellGroup':
                      this.baseStation = '';
                      this.bool.baseStation = true;
                      this.bool.cell = false;
                    break;
                    default:
                      this.bool.baseStation = false;
                      this.bool.cell = false;
                    break;
                  }
                }
              }
            },
            //查询主函数
            toggleStartIcon(event) {
              if ( !(this.template || this.dataSource == "ALARM")) {
                this.$message({
                  showClose: true,
                  message: '请选择模板',
                  type: 'warning'
                });
                this.toogle.startIcon = true;
                this.loading.qatStart = false;
                return;
              }
              if ( !this.time ) {
                this.$message({
                  showClose: true,
                  message: '请选择时间维度',
                  type: 'warning'
                });
                this.toogle.startIcon = true;
                this.loading.qatStart = false;
                return;
              }
              if ( !this.location ) {
                this.$message({
                  showClose: true,
                  message: '请选择地域维度',
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
              if ( this.date.length == 0 && !(this.$store.state.qatData.alarmTime == "Current" && this.dataSource == 'ALARM') ) {
                this.$message({
                  showClose: true,
                  message: '请选择日期',
                  type: 'warning'
                });
                this.toogle.startIcon = true;
                this.loading.qatStart = false;
                return;
              }
              if ( this.dataSource == 'ENIQ' && (this.dataType == 'TDD' || this.dataType == 'FDD' || this.dataType == 'NBIOT' )) 
              {
                if ( this.subnet.length == 0 ) {
                  this.$message({
                    showClose: true,
                    message: '请选择子网',
                    type: 'warning'
                  });
                  this.toogle.startIcon = true;
                  this.loading.qatStart = false;
                  return;
                }
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
                this.processLoadData(
                  this.dataSource,
                  this.dataType,
                  this.template, 
                  this.time, 
                  this.location, 
                  this.city, 
                  this.subnet, 
                  this.baseStation, 
                  this.cell, 
                  this.date, 
                  this.hour, 
                  this.minute, 
                  this.crontab, 
                  this.notice,
                  this.$store.state.qatData.alarmTime);
                return;
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
                this.bus.$emit('loadingQatDataStatus', {loadingQatDataStatus: false});
              }).catch(() => {
                this.$message({
                  type: 'info',
                  message: '已取消停止'
                });          
              });
            },
            //export
            toggleExportIcon(event) {
              if (this.downloadfiles == '') {
                this.$message({
                  type: 'info',
                  message: '请查询后导出！'
                });
                return;
              }
              this.loading.qatExport = true;
              var uerAgent = navigator.userAgent.toLowerCase();
              var format = /(msie|firefox|chrome|opera|version).*?([\d.]+)/;
              var matches = uerAgent.match(format);
              var browerInfo = matches[1].replace(/version/, "'safari");
              if (browerInfo == "chrome") {
                this.download_chrome(this.downloadfiles);
                this.$notify({
                  title: '导出成功！',
                  message: '查询模板:'+this.template,
                  type: 'success'
                });
                this.loading.qatExport = false;
              } else if (browerInfo == "firefox") {
                this.download_firefox(this.downloadfiles);
                this.$notify({
                  title: '导出成功！',
                  message: '查询模板:'+this.template,
                  type: 'success'
                });
                this.loading.qatExport = false;
              }
              this.downloadfiles = '';
            }
        },
        computed: {
          //监控数据源/类型
          computedDatasourceType() {
            this.loading.qatCityStatus = true;
            this.loading.qatTimeStatus = true;
            this.loading.qatLocationStatus = true;
            this.bool.subnet = !this.isDisableSN();
            this.bool.kpitemplate = !this.isDisableTemplate();
            this.bool.chooseTime = !this.isDisableTime();
            if (!this.bool.kpitemplate) {
              this.processLoadTemplate(this.dataSource, this.dataType);
              this.loading.qatTemplateStatus = true;
            }
            this.processLoadCitys(this.dataSource, this.dataType);
            this.processLoadTime(this.dataSource, this.dataType);
            this.processLoadLocation(this.dataSource, this.dataType);
            //同样是select，为啥一个要是数组一个要是字符串。奇怪的elementui
            //清除城市tag模板
            this.city = [];
            this.template = "";
            this.subnet = [];
            this.time = '';
            this.location = '';
            this.date = [];
            this.toogle.startIcon = true;
            this.loading.qatStart = false;
          },
          getSubnetData() {
              if( this.$store.getters.qatSubnetStatus == 2 ) {
                  this.loading.qatSubnetStatus = false;
                  //显示subnet
                  this.subnetGroup = this.$store.getters.qatSubnet;
              } else if( this.$store.getters.qatSubnetStatus == 3 ) {
                  this.$message({
                    showClose: true,
                    message: this.$store.getters.qatSubnet,
                    type: 'error'
                  });
              }
          },
          getTemplateData() {
            if( this.$store.getters.qatTemplateStatus == 2 ) {
              this.loading.qatTemplateStatus = false;
              //显示模板
              this.templateGroup = this.$store.getters.qatTemplate;
            }else if( this.$store.getters.qatTemplateStatus == 3 ) {
              this.$message({
                showClose: true,
                message: this.$store.getters.qatTemplate,
                type: 'error'
              });
            }
          },
          //获取城市数据
          getCityData() {
            if( this.$store.getters.qatCityStatus == 2 ) {
              this.loading.qatCityStatus = false;
              //显示模板
              this.cityGroup = this.$store.getters.qatCity;
            }else if( this.$store.getters.qatCityStatus == 3 ) {
              this.$message({
                showClose: true,
                message: this.$store.getters.qatCity,
                type: 'error'
              });
            }
          },
          //获取时间维度
          showTimeData() {
            if( this.$store.getters.qatTimeStatus == 2 ) {
              this.loading.qatTimeStatus = false;
              //显示时间维度
              this.timeGroup = this.$store.getters.qatTime;
            }else if( this.$store.getters.qatTimeStatus == 3 ) {
              this.$message({
                showClose: true,
                message: this.$store.getters.qatTime,
                type: 'error'
              });
            }
          },
          //获取地域维度
          showLocationData() {
            if( this.$store.getters.qatLocationStatus == 2 ) {
              this.loading.qatLocationStatus = false;
              //显示地域维度
              this.locationGroup = this.$store.getters.qatLocation;
            }else if( this.$store.getters.qatLocationStatus == 3 ) {
              this.$message({
                showClose: true,
                message: this.$store.getters.qatLocation,
                type: 'error'
              });
            }
          },
          getdata() {
            switch( this.$store.getters.qatDataStatus ) {
              case 1:
                break;
              case 2:
                this.toogle.startIcon = true;
                this.loading.qatStart = false;
                this.downloadfiles = this.$store.getters.qatData['file'];
                break;
              case 3:
                break;
              default:
                this.toogle.startIcon = true;
                this.loading.qatStart = false;
                break;
            }
          }
        }
    }
</script>