import QatDataAPI from '../../api/qat/getQatData.js';
export const qatData = {
    state: {
        qatData: {},
        qatDataStatus: 0,
        cancelToken: {}
    },
    actions: {
        loadQatDataStatus( {commit}, data ) {
            this.cancelToken = axios.CancelToken.source();
            commit( 'qatDataStatus', 1 );
            QatDataAPI.getQatData( this.cancelToken, data.dataSource, data.dataType, data.template, data.timeDim, data.locationDim, data.cities, data.subnets, data.baseStation, data.cell, data.date, data.hour, data.minute, data.crontab, data.notice )
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
        }
    },
    mutations: {
        qatDataStatus( state, status ){
            state.qatDataStatus = status;
        },
        qatData( state, qatData ){
            state.qatData = qatData;
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