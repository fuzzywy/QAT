export const Common = {
  methods: {
    //多选逻辑
    processMutiplySelect(select, allselect, newdata) {
            var len = select.length,
              glen = allselect.length,
              seleAll = 0,
              arr = [];
            for (var i = 0; i < len; i++) { 
              if ( select[i] == 'allSelect' && len < 6) {
                seleAll = 1;
                break;
              } else {
                seleAll = 0;
              }
            }

            //全不选
            for (var k = 0,l=newdata.length; k < l; k++) {
              if (newdata[k] == "allSelect") {
                if ( allselect[0]["value"] == "allSelect" && len > 1 ) {
                  newdata = [];
                  return newdata;
                } 
              }
                
            }

            //全选
            if ( seleAll == 1 ) {
              newdata = [];
              for (var j = 0; j < glen; j++) {
                if (allselect[j]['value'] != "allSelect") {
                  newdata.push(allselect[j]['value']);
                }
                
              }
            }

            if ( seleAll == 1 && len == glen-1 ) {
                for (var i = 0; i < newdata.length; i++) {
                    if( newdata[i] != 'allSelect' ) {
                        arr.push(newdata[i]);
                    }
                }
                newdata = arr;
            }
            return newdata;
    },
    //加载子网
    processLoadSubnet(citys, dataSource, dataType) {
      this.$store.dispatch( 'loadQatSubnetStatus', {
                citys: citys,
                dataSource: dataSource,
                dataType: dataType
            });
    },
    //城市为空
    processLoadNullCity(msg, type, clear) {
        msg({
          showClose: true,
          message: '未选择'+type,
          type: 'warning'
        });
        return false;
    },
    //根据数据源类型加载模板
    processLoadTemplate(dataSource, dataType) {
        this.$store.dispatch( 'loadQatTemplateStatus', {
            dataSource: dataSource,
            dataType: dataType
        });
    },
    //加载城市(根据数据源类型显示)
    processLoadCitys(dataSource, dataType) {
        this.$store.dispatch( 'loadQatCityStatus', {
            dataSource: dataSource,
            dataType: dataType
        });
    },
    //加载时间维度(根据数据源类型显示)
    processLoadTime(dataSource, dataType) {
        this.$store.dispatch( 'loadQatTimeStatus', {
            dataSource: dataSource,
            dataType: dataType
        });
    },
    //加载地域维度(根据数据源类型显示)
    processLoadLocation(dataSource, dataType) {
        this.$store.dispatch( 'loadQatLocationStatus', {
            dataSource: dataSource,
            dataType: dataType
        });
    },
    //查询数据
    processLoadData( 
              dataSource,
              dataType,
              template, 
              time, 
              location, 
              city, 
              subnet, 
              baseStation, 
              cell, 
              date, 
              hour, 
              minute, 
              crontab, 
              notice,
              alarmTime ) {
        this.$store.dispatch( 'loadQatDataStatus', {
            dataSource: dataSource,
            dataType: dataType,
            template: template,
            timeDim: time,
            locationDim: location,
            cities: city,
            subnets: subnet,
            baseStation: baseStation,
            cell: cell,
            date: date,
            hour: hour,
            minute: minute,
            crontab: crontab,
            notice: notice,
            alarmTime: alarmTime
        });
    },
    download_chrome(url) {
      var aLink = document.createElement("a");
      aLink.href = url;
      aLink.download = url;
      document.body.appendChild(aLink);
      aLink.click();
    },
    download_firefox(url) {
      window.open(url);
    }
  }
}