import Common from '../common.js';
export default {
    getQatKgetMoData: function() {
        return axios.get( 'kget/getQatKgetMoData')
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getMoByParamFilterData: function(data) {
        return axios.post( 'kget/getMoByParamFilterData', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getKgetTaskData: function() {
        return axios.get( 'kget/getKgetTaskData')
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getKgetParamData: function( data ) {
        return axios.post( 'kget/getKgetParamData', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getKgetData: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'kget/getKgetData', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
            Common.catchError(error);
        });
    },
    exportKgetFile: function( data ) {
        return axios.post( 'kget/exportKgetFile', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    insertKgetCrontabTask: function( data ) {
        return axios.post( 'kget/insertKgetCrontabTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getKgetCrontabTask: function( data ) {
        return axios.post( 'kget/getKgetCrontabTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    checkKgetTaskName: function( data ) {
        return axios.post( 'kget/checkKgetTaskName', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    deleteKgetCrontabTask: function( data ) {
        return axios.post( 'kget/deleteKgetCrontabTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatKgetMoListData: function() {
        return axios.get( 'kget/getQatKgetMoListData')
        .catch(function(error){
            Common.catchError(error);
        });
    }
}