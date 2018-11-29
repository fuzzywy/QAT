import QatCityAPI from '../../api/qat/getQatCity.js';

export const qatCity = {
	state: {
		qatCity: {},
		qatCityStatus: 0
	},

	actions: {
		loadQatCityStatus( {commit}, data ) {
			commit( 'qatCityStatus', 1 );
			QatCityAPI.getQatCity( data.dataSource, data.dataType )
				.then( function( response ){
					if ( response.data != undefined ) {
                        commit( 'qatCity', response.data );
                        commit( 'qatCityStatus', 2 );
                    }else {
                        commit( 'qatCity', [ response ] );
                        commit( 'qatCityStatus' , 3 ); 
                    }
				})
				.catch( function(){
                    commit( 'qatCity', [ 'Connection failed' ] );
                    commit( 'qatCityStatus', 3 );
                });
		}
	},

	mutations: {
        qatCityStatus( state, status ){
            state.qatCityStatus = status;
        },
        qatCity( state, qatCity ){
            state.qatCity = qatCity;
        }
    },

    getters: {
        qatCityStatus( state ){
            return state.qatCityStatus;
        },
        qatCity( state ){
            return state.qatCity;
        }
    }
}