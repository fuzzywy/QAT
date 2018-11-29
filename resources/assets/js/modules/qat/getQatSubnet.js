import QatSubnetAPI from '../../api/qat/getQatSubnet.js';

export const qatSubnet = {
	/**
     * Defines the state being monitored for the module.
     */
    state: {
        qatSubnet: {},
        qatSubnetStatus: 0
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {

        loadQatSubnetStatus( { commit }, data ){
            commit( 'qatSubnetStatus', 1 );

            QatSubnetAPI.getQatSubnet( data.citys, data.dataSource, data.dataType )
                .then( function( response ){
                    if ( response.data != undefined ) {
                        commit( 'qatSubnet', response.data );
                        commit( 'qatSubnetStatus', 2 );
                    }else {
                        commit( 'qatSubnet', [ response ] );
                        commit( 'qatSubnetStatus', 3 ); 
                    }
                    
                })
                .catch( function(){
                    commit( 'qatSubnet', [ 'Connection failed' ] );
                    commit( 'qatSubnetStatus', 3 );
                });

        }
    },
    /**
     * Defines the mutations used
     */
    mutations: {

        qatSubnetStatus( state, status ){
            state.qatSubnetStatus = status;
        },

        qatSubnet( state, qatSubnet ){
            state.qatSubnet = qatSubnet;
        }
    },
    /**
     * Defines the getters used by the module
     */
    getters: {
        qatSubnetStatus( state ){
            return state.qatSubnetStatus;
        },

        qatSubnet( state ){
            return state.qatSubnet;
        }
    }
};