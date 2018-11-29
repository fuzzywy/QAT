export default {
	getQatData: function(
				  dataSource,
                  dataType,
                  template, 
                  timeDim, 
                  locationDim, 
                  cities, 
                  subnets, 
                  baseStation, 
                  cell, 
                  date, 
                  hour, 
                  minute, 
                  crontab, 
                  notice) {
		return axios.get( 'getQatData', {
			params: {
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
				notice: notice
			}
		})
		.catch(function(error) {
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