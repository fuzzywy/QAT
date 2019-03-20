import QatDataAPI from '../../api/qat/getQatData.js';
export const qatData = {
    state: {
        qatData: {},
        qatDataStatus: 0,
        cancelToken: {},

        qatDataSource: "ENIQ",

        qatDataStyle: "TDD",
    },
    actions: {
        loadQatDataStatus( {commit}, data ) {
            this.cancelToken = axios.CancelToken.source();
            commit( 'qatDataStatus', 1 );
            QatDataAPI.getQatData( this.cancelToken, data.dataSource, data.dataType, data.template, data.timeDim, data.locationDim, data.cities, data.subnets, data.baseStation, data.cell, data.date, data.hour, data.minute, data.crontab, data.notice, data.alarmTime )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatData', response.data );
                    commit( 'qatDataStatus', 2 );
                }else {
                    commit( 'qatData', [ response ] );
                    commit( 'qatDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatData', [ 'Connection failed' ] );
                commit( 'qatDataStatus', 3 );
            });
        },
        cancel() {
            this.cancelToken.cancel('取消查询');
        },
        getQatDataSource( {commit}, data) {
            commit("qatDataSource", data.dataSource);
        },
        getQatDataStyle( {commit}, data) {
            commit("qatDataStyle", data.dataType);
        }
    },
    mutations: {
        qatDataStatus( state, status ){
            state.qatDataStatus = status;
        },
        qatData( state, qatData ){
            state.qatData = qatData;
        },
        qatDataSource(state, qatDataSource) {
            state.qatDataSource = qatDataSource;
        },
        qatDataStyle(state, qatDataStyle) {
            state.qatDataStyle = qatDataStyle
        }
    },
    getters: {
        qatDataStatus( state ){
            return state.qatDataStatus;
        },
        qatData( state ){
            return state.qatData;
        }
    }
}