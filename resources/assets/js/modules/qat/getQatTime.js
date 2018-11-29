import QatTimeAPI from '../../api/qat/getQatTime.js';
export const qatTime = {
    state: {
        qatTime: {},
        qatTimeStatus: 0
    },
    actions: {
        loadQatTimeStatus( {commit}, data ) {
            commit( 'qatTimeStatus', 1 );
            QatTimeAPI.getQatTime( data.dataSource, data.dataType )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTime', response.data );
                    commit( 'qatTimeStatus', 2 );
                }else {
                    commit( 'qatTime', [ response ] );
                    commit( 'qatTimeStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTime', [ 'Connection failed' ] );
                commit( 'qatTimeStatus', 3 );
            });
        }
    },
    mutations: {
        qatTimeStatus( state, status ){
            state.qatTimeStatus = status;
        },
        qatTime( state, qatTime ){
            state.qatTime = qatTime;
        }
    },
    getters: {
        qatTimeStatus( state ){
            return state.qatTimeStatus;
        },
        qatTime( state ){
            return state.qatTime;
        }
    }
}