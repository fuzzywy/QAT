import QatTemplateAPI from '../../api/qat/getQatTemplate.js';
export const qatTemplate = {
    state: {
        qatTemplate: {},
        qatTemplateStatus: 0
    },
    actions: {
        loadQatTemplateStatus( {commit}, data ) {
            commit( 'qatTemplateStatus', 1 );
            QatTemplateAPI.getQatTemplate( data.dataSource, data.dataType )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTemplate', response.data );
                    commit( 'qatTemplateStatus', 2 );
                }else {
                    commit( 'qatTemplate', [ response ] );
                    commit( 'qatTemplateStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTemplate', [ 'Connection failed' ] );
                commit( 'qatTemplateStatus', 3 );
            });
        }
    },
    mutations: {
        qatTemplateStatus( state, status ){
            state.qatTemplateStatus = status;
        },
        qatTemplate( state, qatTemplate ){
            state.qatTemplate = qatTemplate;
        }
    },
    getters: {
        qatTemplateStatus( state ){
            return state.qatTemplateStatus;
        },
        qatTemplate( state ){
            return state.qatTemplate;
        }
    }
}