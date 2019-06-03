 <template>
    <div class="x-bar">
        <div :id="id"  :option="getParamData"></div>
    </div>
</template>
<script>
    import HighCharts from 'highcharts/highmaps';
    var chart = '';
    export default {
        data() {
            return {
                postData: null,
                id: 'container',
                option: {
                   chart: {
                            type: 'heatmap',
                            marginTop: 40,
                            marginBottom: 80,
                            plotBorderWidth: 1,
                            height:350, 
                            width:930
                    },
                    loading: {
                        hideDuration: 100,
                        labelStyle: {"fontWeight": "bold", "position": "relative", "top": "45%"},
                        showDuration: 100,
                        style: {"position": "absolute", "backgroundColor": "#ffffff", "opacity": 0.5, "textAlign": "center"}
                    },
                    title: {
                            text: ''
                    },
                    xAxis: {
                            categories: [],
                            labels: {
                                formatter: function () {
                                    var length = this.value.length;
                                    //获取到刻度值
                                    var labelVal = this.value;
                                    //实际返回的刻度值
                                    var reallyVal = labelVal;
                                    var standard = 7;
                                    //判断刻度值的长度
                                    if (length >= 6 && labelVal.length > standard) {
                                        var counts = parseInt(labelVal.length / standard) + 1;
                                        //alert("Oceania".substring(6,7));
                                        reallyVal = "";
                                        for (var i = 0; i < counts; i = i + 1) {
                                            if (i == 0) {
                                                //截取刻度值
                                                reallyVal = labelVal.substr(0, standard) + "<br/>";
                                            } else if (i == counts - 1) {
                                                reallyVal = reallyVal + labelVal.substring(standard * i, labelVal.length);
                                            } else {
                                                reallyVal = reallyVal + labelVal.substr(standard * i, standard) + "<br/>";
                                            }

                                        }

                                    }
                                    return reallyVal;
                                }
                            }
                    },
                    yAxis: {
                            categories: [],
                            title: null
                    },
                    colorAxis: {
                            min: 0,
                            minColor: '#FFFFFF',
                            maxColor: '#FF0000'
                    },
                    plotOptions: {},
                    legend: {
                            align: 'right',
                            layout: 'vertical',
                            margin: 0,
                            verticalAlign: 'top',
                            y: 25,
                            symbolHeight: 280
                    },
                    credits: {
                        enabled: false,
                    },
                    tooltip: {
                            formatter: function () {
                                    return '<b>' + this.series.xAxis.categories[this.point.x] + '</b> sold <br><b>' +
                                            this.point.value + '</b> items on <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
                            }
                    },
                    series: [{
                            name: 'Sales per employee',
                            borderWidth: 1,
                            data: [],
                            dataLabels: {
                                    enabled: true,
                                    color: '#000000'
                            }
                    }]
                }
            }
        },
        created() {
            var _this = this;
            _this.option.title.text = this.$t('messages.kget.checkTitle');
            _this.option.plotOptions = {
                heatmap: {
                    events: {
                        click: function (e) {
                            var subItem = this.xAxis.categories[e.point.x];
                            var city = this.yAxis.categories[e.point.y];
                            _this.postData.subItem = subItem;
                            _this.postData.city = city;
                            _this.$store.dispatch( 'loadQatParamDetailData', _this.postData);
                            _this.bus.$emit('loadQatParamDetailData', {isShow:true,postData: _this.postData});
                        }
                    }
                }
            }
            
        },
        mounted() {
            chart = HighCharts.chart(this.id,this.option);
            this.bus.$on('loadQatParamData', data=> {
                this.postData = data.postData;
            });
        },
        computed: {
            getParamData() {
                switch( this.$store.getters.qatParamDataStatus ) {
                    case 1:
                        chart.showLoading();
                        chart.series[0].setData([]);
                        chart.xAxis.categories = [];
                        chart.yAxis.categories = [];
                        break;
                    case 2:
                        chart.xAxis[0].categories = this.$store.getters.qatParamData.xAxis;
                        chart.yAxis[0].categories = this.$store.getters.qatParamData.yAxis;
                        chart.series[0].setData(this.$store.getters.qatParamData.series);
                        chart.hideLoading();
                        var qatExpIsShow = false;
                        if(this.$store.getters.qatParamData.totalCount > 0){
                            qatExpIsShow = true;
                        }
                        this.bus.$emit('qatParamStatus', {qatParamStatus: true ,qatExpIsShow: qatExpIsShow});
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