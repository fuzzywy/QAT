import QatParamAPI from '../../api/qat/getQatParam.js';
export const qatParam = {
    state: {
        
        qatParamItemData: {},
        qatParamItemDataStatus: 0,

        qatParamOperatorData: {},
        qatParamOperatorDataStatus: 0,

        qatParamProvinceData: {},
        qatParamProvinceDataStatus: 0,

        qatParamCityData: {},
        qatParamCityDataStatus: 0,

        cancelToken: {},

        qatParamData: {},
        qatParamDataStatus: 0,

        exportParamData: {},
        exportParamDataStatus: 0,

        qatParamDetailData: {},
        qatParamDetailDataStatus: 0,

        exportParamDetailData: {},
        exportParamDetailDataStatus: 0,

        exportParamWhiteList: {},
        exportParamWhiteListStatus: 0
    },
    actions: {
        loadQatParamItemData( {commit} ) {
            commit( 'qatParamItemDataStatus', 1 );
            QatParamAPI.getQatParamItemData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatParamItemData', response.data );
                    commit( 'qatParamItemDataStatus', 2 );
                }else {
                    commit( 'qatParamItemData', [ response ] );
                    commit( 'qatParamItemDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatParamItemData', [ 'Connection failed' ] );
                commit( 'qatParamItemDataStatus', 3 );
            });
        },
        loadQatParamOperatorData( {commit}, data) {
            commit( 'qatParamOperatorDataStatus', 1 );
            QatParamAPI.getQatParamOperatorData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatParamOperatorData', response.data );
                    commit( 'qatParamOperatorDataStatus', 2 );
                }else {
                    commit( 'qatParamOperatorData', [ response ] );
                    commit( 'qatParamOperatorDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatParamOperatorData', [ 'Connection failed' ] );
                commit( 'qatParamOperatorDataStatus', 3 );
            });
        },
        loadQatParamProvinceData( {commit} ) {
            commit( 'qatParamProvinceDataStatus', 1 );
            QatParamAPI.getQatParamProvinceData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatParamProvinceData', response.data );
                    commit( 'qatParamProvinceDataStatus', 2 );
                }else {
                    commit( 'qatParamProvinceData', [ response ] );
                    commit( 'qatParamProvinceDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatParamProvinceData', [ 'Connection failed' ] );
                commit( 'qatParamProvinceDataStatus', 3 );
            });
        },
        loadQatParamCityData( {commit} , data) {
            commit( 'qatParamCityDataStatus', 1 );
            QatParamAPI.getQatParamCityData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatParamCityData', response.data );
                    commit( 'qatParamCityDataStatus', 2 );
                }else {
                    commit( 'qatParamCityData', [ response ] );
                    commit( 'qatParamCityDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatParamCityData', [ 'Connection failed' ] );
                commit( 'qatParamCityDataStatus', 3 );
            });
        },
        loadQatParamData( {commit}, data) {
            this.cancelToken = axios.CancelToken.source();
            data['cancelToken'] = this.cancelToken;
            commit( 'qatParamDataStatus', 1 );
            QatParamAPI.getQatParamData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatParamData', response.data );
                    commit( 'qatParamDataStatus', 2 );
                }else {
                    commit( 'qatParamData', [ response ] );
                    commit( 'qatParamDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatParamData', [ 'Connection failed' ] );
                commit( 'qatParamDataStatus', 3 );
            });
        },
        cancel() {
            this.cancelToken.cancel('取消查询');
        },
        exportQatParamData( {commit}, data) {
            this.cancelToken = axios.CancelToken.source();
            data['cancelToken'] = this.cancelToken;
            commit( 'exportParamDataStatus', 1 );
            QatParamAPI.exportQatParamData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'exportParamData', response.data );
                    commit( 'exportParamDataStatus', 2 );
                }else {
                    commit( 'exportParamData', [ response ] );
                    commit( 'exportParamDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'exportParamData', [ 'Connection failed' ] );
                commit( 'exportParamDataStatus', 3 );
            });
        },
        loadQatParamDetailData( {commit}, data) {
            this.cancelToken = axios.CancelToken.source();
            data['cancelToken'] = this.cancelToken;
            commit( 'qatParamDetailDataStatus', 1 );
            QatParamAPI.getQatParamDetailData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatParamDetailData', response.data );
                    commit( 'qatParamDetailDataStatus', 2 );
                }else {
                    commit( 'qatParamDetailData', [ response ] );
                    commit( 'qatParamDetailDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatParamDetailData', [ 'Connection failed' ] );
                commit( 'qatParamDetailDataStatus', 3 );
            });
        },
        exportQatParamDetailData( {commit}, data) {
            this.cancelToken = axios.CancelToken.source();
            data['cancelToken'] = this.cancelToken;
            commit( 'exportParamDetailDataStatus', 1 );
            QatParamAPI.exportQatParamDetailData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'exportParamDetailData', response.data );
                    commit( 'exportParamDetailDataStatus', 2 );
                }else {
                    commit( 'exportParamDetailData', [ response ] );
                    commit( 'exportParamDetailDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'exportParamDetailData', [ 'Connection failed' ] );
                commit( 'exportParamDetailDataStatus', 3 );
            });
        },
        exportParamWhiteList( {commit}, data) {
            this.cancelToken = axios.CancelToken.source();
            data['cancelToken'] = this.cancelToken;
            commit( 'exportParamWhiteListStatus', 1 );
            QatParamAPI.exportParamWhiteList(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'exportParamWhiteList', response.data );
                    commit( 'exportParamWhiteListStatus', 2 );
                }else {
                    commit( 'exportParamWhiteList', [ response ] );
                    commit( 'exportParamWhiteListStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'exportParamWhiteList', [ 'Connection failed' ] );
                commit( 'exportParamWhiteListStatus', 3 );
            });
        }
    },
    mutations: {
        qatParamItemDataStatus( state, status ){
            state.qatParamItemDataStatus = status;
        },
        qatParamItemData( state, qatParamItemData ){
            state.qatParamItemData = qatParamItemData;
        },
        qatParamOperatorDataStatus( state, status ){
            state.qatParamOperatorDataStatus = status;
        },
        qatParamOperatorData( state, qatParamOperatorData ){
            state.qatParamOperatorData = qatParamOperatorData;
        },
        qatParamProvinceDataStatus( state, status ){
            state.qatParamProvinceDataStatus = status;
        },
        qatParamProvinceData( state, qatParamProvinceData ){
            state.qatParamProvinceData = qatParamProvinceData;
        },
        qatParamCityDataStatus( state, status ){
            state.qatParamCityDataStatus = status;
        },
        qatParamCityData( state, qatParamCityData ){
            state.qatParamCityData = qatParamCityData;
        },
        qatParamDataStatus( state, status ){
            state.qatParamDataStatus = status;
        },
        qatParamData( state, qatParamData ){
            state.qatParamData = qatParamData;
        },
        exportParamDataStatus( state, status ){
            state.exportParamDataStatus = status;
        },
        exportParamData( state, exportParamData ){
            state.exportParamData = exportParamData;
        },
        qatParamDetailDataStatus( state, status ){
            state.qatParamDetailDataStatus = status;
        },
        qatParamDetailData( state, qatParamDetailData ){
            state.qatParamDetailData = qatParamDetailData;
        },
        exportParamDetailDataStatus( state, status ){
            state.exportParamDetailDataStatus = status;
        },
        exportParamDetailData( state, exportParamDetailData ){
            state.exportParamDetailData = exportParamDetailData;
        },
        exportParamWhiteListStatus( state, status ){
            state.exportParamWhiteListStatus = status;
        },
        exportParamWhiteList( state, exportParamWhiteList ){
            state.exportParamWhiteList = exportParamWhiteList;
        }
    },
    getters: {
        qatParamItemDataStatus( state ){
            return state.qatParamItemDataStatus;
        },
        qatParamItemData( state ){
            return state.qatParamItemData;
        },
        qatParamOperatorDataStatus( state ){
            return state.qatParamOperatorDataStatus;
        },
        qatParamOperatorData( state ){
            return state.qatParamOperatorData;
        },
        qatParamProvinceDataStatus( state ){
            return state.qatParamProvinceDataStatus;
        },
        qatParamProvinceData( state ){
            return state.qatParamProvinceData;
        },
        qatParamCityDataStatus( state ){
            return state.qatParamCityDataStatus;
        },
        qatParamCityData( state ){
            return state.qatParamCityData;
        },
        qatParamDataStatus( state ){
            return state.qatParamDataStatus;
        },
        qatParamData( state ){
            return state.qatParamData;
        },
        exportParamDataStatus( state ){
            return state.exportParamDataStatus;
        },
        exportParamData( state ){
            return state.exportParamData;
        },
        qatParamDetailDataStatus( state ){
            return state.qatParamDetailDataStatus;
        },
        qatParamDetailData( state ){
            return state.qatParamDetailData;
        },
        exportParamDetailDataStatus( state ){
            return state.exportParamDetailDataStatus;
        },
        exportParamDetailData( state ){
            return state.exportParamDetailData;
        },
        exportParamWhiteListStatus( state ){
            return state.exportParamWhiteListStatus;
        },
        exportParamWhiteList( state ){
            return state.exportParamWhiteList;
        }
    }
}