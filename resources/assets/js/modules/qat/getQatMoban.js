import QatMobanAPI from '../../api/qat/getQatMoban.js';

export const qatMoban = {
	state: {
		qatMoban: {},
		qatMobanStatus: 0
	},

	actions: {
		loadQatMobanStatus( {commit}, data ) {
			commit( 'qatMobanStatus', 1 );
			QatMobanAPI.getQatMoban( data.dataSource, data.dataType )
				.then( function( response ){
					if ( response.data != undefined ) {
                        commit( 'qatMoban', response.data );
                        commit( 'qatMobanStatus', 2 );
                    }else {
                        commit( 'qatMoban', [ response ] );
                        commit( 'qatMobanStatus' , 3 ); 
                    }
				})
				.catch( function(){
                    commit( 'qatMoban', [ 'Connection failed' ] );
                    commit( 'qatMobanStatus', 3 );
                });
		}
	},

	mutations: {
        qatMobanStatus( state, status ){
            state.qatMobanStatus = status;
        },
        qatMoban( state, qatMoban ){
            state.qatMoban = qatMoban;
        }
    },

    getters: {
        qatMobanStatus( state ){
            return state.qatMobanStatus;
        },
        qatMoban( state ){
            return state.qatMoban;
        }
    }
}