import Common from '../common.js';
export default {
    getQatParamItemData: function() {
        return axios.get( 'paramCheck/getQatParamItemData')
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatParamOperatorData: function( data ) {
        return axios.post( 'paramCheck/getQatParamOperatorData', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatParamProvinceData: function() {
        return axios.get( 'paramCheck/getQatParamProvinceData')
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatParamCityData: function( data ) {
        return axios.post( 'paramCheck/getQatParamCityData', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatParamData: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'paramCheck/getQatParamData', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
            Common.catchError(error);
        });
    },
    exportQatParamData: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'paramCheck/exportQatParamData', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatParamDetailData: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'paramCheck/getQatParamDetailData', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
            Common.catchError(error);
        });
    },
    exportQatParamDetailData: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'paramCheck/exportQatParamDetailData', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
            Common.catchError(error);
        });
    },
    exportParamWhiteList: function( data ) {
        var cancelToken = data.cancelToken;
        delete data.cancelToken;
        return axios.post( 'paramCheck/exportWhiteList', data, {
            cancelToken: cancelToken.token
        })
        .catch(function(error){
            Common.catchError(error);
        });
    }
}