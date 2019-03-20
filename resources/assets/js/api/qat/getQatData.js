export default {
    getQatData: function( cancelToken, dataSource, dataType, template, timeDim, locationDim, cities, subnets, baseStation, cell, date, hour, minute, crontab, notice, alarmTime ) {
        return axios.post( "getQatData", {
            dataSource: dataSource,
            dataType: dataType,
            template: template,
            timeDim: timeDim,
            locationDim: locationDim,
            cities: cities,
            subnets: subnets,
            baseStation: baseStation,
            cell: cell,
            date: date,
            hour: hour,
            minute: minute,
            crontab: crontab,
            notice: notice,
            alarmTime: alarmTime
        }, {
            cancelToken: cancelToken.token
        })
        .catch(function(error) {
            if (axios.isCancel(error)) {
                console.log('Request canceled', error.message);
            } else {
                // handle error
            }
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    }
}