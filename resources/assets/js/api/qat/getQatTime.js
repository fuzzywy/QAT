export default {
    getQatTime: function(dataSource, dataType) {
        return axios.get( 'getQatTime', {
            params: {
                dataSource: dataSource,
                dataType: dataType
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