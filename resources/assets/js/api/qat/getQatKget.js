export default {
    
    getQatKgetMoData: function() {
        return axios.post( 'kget/getQatKgetMoData')
        .catch(function(error){
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
    },
    getMoByParamFilterData: function(data) {
        return axios.post( 'kget/getMoByParamFilterData', data)
        .catch(function(error){
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
    },
    getKgetTaskData: function() {
        return axios.post( 'kget/getKgetTaskData')
        .catch(function(error){
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
    },
    getKgetParamData: function( data ) {
        return axios.post( 'kget/getKgetParamData', data)
        .catch(function(error){
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
    },
    getKgetData: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'kget/getKgetData', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
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
    },
    exportKgetFile: function( data ) {
        return axios.post( 'kget/exportKgetFile', data)
        .catch(function(error){
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
    },
    insertKgetCrontabTask: function( data ) {
        return axios.post( 'kget/insertKgetCrontabTask', data)
        .catch(function(error){
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
    },
    getKgetCrontabTask: function( data ) {
        return axios.post( 'kget/getKgetCrontabTask', data)
        .catch(function(error){
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
    },
    checkKgetTaskName: function( data ) {
        return axios.post( 'kget/checkKgetTaskName', data)
        .catch(function(error){
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
    },
    deleteKgetCrontabTask: function( data ) {
        return axios.post( 'kget/deleteKgetCrontabTask', data)
        .catch(function(error){
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
    },
    getQatKgetMoListData: function() {
        return axios.post( 'kget/getQatKgetMoListData')
        .catch(function(error){
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