import QatLocationAPI from '../../api/qat/getQatLocation.js';
export const qatLocation = {
    state: {
        qatLocation: {},
        qatLocationStatus: 0
    },
    actions: {
        loadQatLocationStatus( {commit}, data ) {
            commit( 'qatLocationStatus', 1 );
            QatLocationAPI.getQatLocation( data.dataSource, data.dataType )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatLocation', response.data );
                    commit( 'qatLocationStatus', 2 );
                }else {
                    commit( 'qatLocation', [ response ] );
                    commit( 'qatLocationStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatLocation', [ 'Connection failed' ] );
                commit( 'qatLocationStatus', 3 );
            });
        }
    },
    mutations: {
        qatLocationStatus( state, status ){
            state.qatLocationStatus = status;
        },
        qatLocation( state, qatLocation ){
            state.qatLocation = qatLocation;
        }
    },
    getters: {
        qatLocationStatus( state ){
            return state.qatLocationStatus;
        },
        qatLocation( state ){
            return state.qatLocation;
        }
    }
}