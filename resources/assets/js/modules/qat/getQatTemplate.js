import QatTemplateAPI from '../../api/qat/getQatTemplate.js';
export const qatTemplate = {
    state: {
        qatTemplate: {},
        qatTemplateStatus: 0,

        qatLoginUser: {},
        qatLoginUserStatus: 0,

        qatTemplateData: {},
        qatTemplateDataStatus: 0,

        insertTemplateName: {},
        insertTemplateNameStatus: 0,

        removeTemplateName: {},
        removeTemplateNameStatus: 0,

        loadQatElementData: {},
        loadQatElementDataStatus: 0
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
        },
        loadQatLoginUser( {commit} ) {
            commit( 'qatLoginUserStatus', 1 );
            QatTemplateAPI.getQatLoginUser()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatLoginUser', response.data );
                    commit( 'qatLoginUserStatus', 2 );
                }else {
                    commit( 'qatLoginUser', [ response ] );
                    commit( 'qatLoginUserStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatLoginUser', [ 'Connection failed' ] );
                commit( 'qatLoginUserStatus', 3 );
            });
        },
        loadQatTemplateData( {commit}, data ) {
            commit( 'qatTemplateDataStatus', 1 );
            QatTemplateAPI.getQatTemplateData( data.dataSource, data.dataType )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTemplateData', response.data );
                    commit( 'qatTemplateDataStatus', 2 );
                }else {
                    commit( 'qatTemplateData', [ response ] );
                    commit( 'qatTemplateDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTemplateData', [ 'Connection failed' ] );
                commit( 'qatTemplateDataStatus', 3 );
            });
        },
        insertTemplateName( {commit}, data ) {
            commit( 'insertTemplateNameStatus', 1 );
            QatTemplateAPI.insertQatTemplateName( data.auth, data.templateName )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'insertTemplateName', response.data );
                    commit( 'insertTemplateNameStatus', 2 );
                }else {
                    commit( 'insertTemplateName', [ response ] );
                    commit( 'insertTemplateNameStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'insertTemplateName', [ 'Connection failed' ] );
                commit( 'insertTemplateNameStatus', 3 );
            });
        },
        removeTemplateName( {commit}, data ) {
            commit( 'removeTemplateNameStatus', 1 );
            QatTemplateAPI.removeQatTemplateName( data.auth, data.templateName )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'removeTemplateName', response.data );
                    commit( 'removeTemplateNameStatus', 2 );
                }else {
                    commit( 'removeTemplateName', [ response ] );
                    commit( 'removeTemplateNameStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'removeTemplateName', [ 'Connection failed' ] );
                commit( 'removeTemplateNameStatus', 3 );
            });
        },
        loadQatElementData( {commit}, data ) {
            commit( 'loadQatElementDataStatus', 1 );
            QatTemplateAPI.loadQatElementData( data.templateName, data.parent, data.grandparent, data.auth )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'loadQatElementData', response.data );
                    commit( 'loadQatElementDataStatus', 2 );
                }else {
                    commit( 'loadQatElementData', [ response ] );
                    commit( 'loadQatElementDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'loadQatElementData', [ 'Connection failed' ] );
                commit( 'loadQatElementDataStatus', 3 );
            });
        }
    },
    mutations: {
        qatTemplateStatus( state, status ){
            state.qatTemplateStatus = status;
        },
        qatTemplate( state, qatTemplate ){
            state.qatTemplate = qatTemplate;
        },
        qatLoginUserStatus( state, status ){
            state.qatLoginUserStatus = status;
        },
        qatLoginUser( state, qatLoginUser ){
            state.qatLoginUser = qatLoginUser;
        },
        qatTemplateDataStatus( state, status ){
            state.qatTemplateDataStatus = status;
        },
        qatTemplateData( state, qatTemplateData ){
            state.qatTemplateData = qatTemplateData;
        },
        insertTemplateNameStatus( state, status ){
            state.insertTemplateNameStatus = status;
        },
        insertTemplateName( state, insertTemplateName ){
            state.insertTemplateName = insertTemplateName;
        },
        removeTemplateNameStatus( state, status ){
            state.removeTemplateNameStatus = status;
        },
        removeTemplateName( state, removeTemplateName ){
            state.removeTemplateName = removeTemplateName;
        },
        loadQatElementDataStatus( state, status ){
            state.loadQatElementDataStatus = status;
        },
        loadQatElementData( state, loadQatElementData ){
            state.loadQatElementData = loadQatElementData;
        }
    },
    getters: {
        qatTemplateStatus( state ){
            return state.qatTemplateStatus;
        },
        qatTemplate( state ){
            return state.qatTemplate;
        },
        qatLoginUserStatus( state ){
            return state.qatLoginUserStatus;
        },
        qatLoginUser( state ){
            return state.qatLoginUser;
        },
        qatTemplateDataStatus( state ){
            return state.qatTemplateDataStatus;
        },
        qatTemplateData( state ){
            return state.qatTemplateData;
        },
        insertTemplateNameStatus( state ){
            return state.insertTemplateNameStatus;
        },
        insertTemplateName( state ){
            return state.insertTemplateName;
        },
        removeTemplateNameStatus( state ){
            return state.removeTemplateNameStatus;
        },
        removeTemplateName( state ){
            return state.removeTemplateName;
        },
        loadQatElementDataStatus( state ){
            return state.loadQatElementDataStatus;
        },
        loadQatElementData( state ){
            return state.loadQatElementData;
        }
    }
}