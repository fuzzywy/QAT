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
        loadQatElementDataStatus: 0,

        orderQatElementData: {},
        orderQatElementDataStatus: 0,

        loadQatFormulaData: {},
        loadQatFormulaDataStatus: 0,

        selectKpiFormula: {},
        selectKpiFormulaStatus: 0,

        addQatFormula: {},
        addQatFormulaStatus: 0,

        deleteQatFormulaStatus: 0,

        modifyQatFormulaStatus: 0,
        modifyQatFormula: {},

        insertQatElementStatus: 0,
        insertQatElement: {},

        deleteQatElementStatus: 0,
        deleteQatElement: {},

        addQatTemplate: {},
        addQatTemplateStatus: 0

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
            QatTemplateAPI.insertQatTemplateName( data.auth, data.templateName, data.parent )
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
            QatTemplateAPI.removeQatTemplateName( data.auth, data.templateName, data.id )
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
        },
        orderQatElementData( {commit}, data ) {
            commit( 'orderQatElementDataStatus', 1 );
            QatTemplateAPI.orderQatElementData( data.element, data.templateName, data.parent, data.grandparent, data.auth )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'orderQatElementData', response.data );
                    commit( 'orderQatElementDataStatus', 2 );
                }else {
                    commit( 'orderQatElementData', [ response ] );
                    commit( 'orderQatElementDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'orderQatElementData', [ 'Connection failed' ] );
                commit( 'orderQatElementDataStatus', 3 );
            });
        },
        loadQatFormulaData(  {commit}, data ) {
            commit( 'loadQatFormulaDataStatus', 1 );
            QatTemplateAPI.loadQatFormulaData( /*data.id, data.label, data.templateName, data.parent, data.grandparent, *//*data.auth*/ )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'loadQatFormulaData', response.data );
                    commit( 'loadQatFormulaDataStatus', 2 );
                }else {
                    commit( 'loadQatFormulaData', [ response ] );
                    commit( 'loadQatFormulaDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'loadQatFormulaData', [ 'Connection failed' ] );
                commit( 'loadQatFormulaDataStatus', 3 );
            });
        },
        selectKpiFormula( {commit}, data ) {
            commit( 'selectKpiFormulaStatus', 1 );
            QatTemplateAPI.selectKpiFormula( data.clickElement, data.elements )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'selectKpiFormula', response.data );
                    commit( 'selectKpiFormulaStatus', 2 );
                }else {
                    commit( 'selectKpiFormula', [ response ] );
                    commit( 'selectKpiFormulaStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'selectKpiFormula', [ 'Connection failed' ] );
                commit( 'selectKpiFormulaStatus', 3 );
            });
        },
        addQatFormula( {commit}, data ) {
            commit( 'addQatFormulaStatus', 1 );
            QatTemplateAPI.addQatFormula( data.kpiName, data.kpiFormula, data.kpiPrecision, data.format )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'addQatFormula', response.data );
                    commit( 'addQatFormulaStatus', 2 );
                }else {
                    commit( 'addQatFormula', [ response ] );
                    commit( 'addQatFormulaStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'addQatFormula', [ 'Connection failed' ] );
                commit( 'addQatFormulaStatus', 3 );
            });
        },
        deleteQatFormula( {commit}, data ) {
            commit( 'deleteQatFormulaStatus', 1 );
            QatTemplateAPI.deleteQatFormula( data.id )
            .then( function( response ){
                if ( response.data != undefined ) {
                    // commit( 'addQatFormula', response.data );
                    commit( 'deleteQatFormulaStatus', 2 );
                }else {
                    // commit( 'addQatFormula', [ response ] );
                    commit( 'deleteQatFormulaStatus' , 3 ); 
                }
            })
            .catch( function(){
                // commit( 'addQatFormula', [ 'Connection failed' ] );
                commit( 'deleteQatFormulaStatus', 3 );
            });
        },
        modifyQatFormula( {commit}, data ) {
            commit( 'modifyQatFormulaStatus', 1 );
            QatTemplateAPI.modifyQatFormula( data.id, data.kpiName, data.kpiFormula, data.kpiPrecision )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'modifyQatFormula', response.data );
                    commit( 'modifyQatFormulaStatus', 2 );
                }else {
                    commit( 'modifyQatFormula', [ response ] );
                    commit( 'modifyQatFormulaStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'modifyQatFormula', [ 'Connection failed' ] );
                commit( 'modifyQatFormulaStatus', 3 );
            });
        },
        insertQatElement( {commit}, data ) {
            commit( 'insertQatElementStatus', 1 );
            QatTemplateAPI.insertQatElement( data.templateName, data.parent, data.grandparent, data.ids )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'insertQatElement', response.data );
                    commit( 'insertQatElementStatus', 2 );
                }else {
                    commit( 'insertQatElement', [ response ] );
                    commit( 'insertQatElementStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'insertQatElement', [ 'Connection failed' ] );
                commit( 'insertQatElementStatus', 3 );
            });
        },
        deleteQatElement( {commit}, data ) {
            commit( 'deleteQatElementStatus', 1 );
            QatTemplateAPI.deleteQatElement( data.id, data.templateName, data.parent, data.grandparent )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'deleteQatElement', response.data );
                    commit( 'deleteQatElementStatus', 2 );
                }else {
                    commit( 'deleteQatElement', [ response ] );
                    commit( 'deleteQatElementStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'deleteQatElement', [ 'Connection failed' ] );
                commit( 'deleteQatElementStatus', 3 );
            });
        },
        addQatTemplate( {commit}, data ) {
            commit( 'addQatTemplateStatus', 1 );
            QatTemplateAPI.addQatTemplate( data.templateName, data.format )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'addQatTemplate', response.data );
                    commit( 'addQatTemplateStatus', 2 );
                }else {
                    commit( 'addQatTemplate', [ response ] );
                    commit( 'addQatTemplateStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'addQatTemplate', [ 'Connection failed' ] );
                commit( 'addQatTemplateStatus', 3 );
            });
        },
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
        },
        orderQatElementDataStatus( state, status ){
            state.orderQatElementDataStatus = status;
        },
        orderQatElementData( state, orderQatElementData ){
            state.orderQatElementData = orderQatElementData;
        },
        loadQatFormulaDataStatus( state, status ){
            state.loadQatFormulaDataStatus = status;
        },
        loadQatFormulaData( state, loadQatFormulaData ){
            state.loadQatFormulaData = loadQatFormulaData;
        },
        selectKpiFormulaStatus( state, status ){
            state.selectKpiFormulaStatus = status;
        },
        selectKpiFormula( state, selectKpiFormula ){
            state.selectKpiFormula = selectKpiFormula;
        },
        addQatFormulaStatus( state, status ){
            state.addQatFormulaStatus = status;
        },
        addQatFormula( state, addQatFormula ){
            state.addQatFormula = addQatFormula;
        },
        deleteQatFormulaStatus( state, status ){
            state.deleteQatFormulaStatus = status;
        },
        modifyQatFormulaStatus( state, status ){
            state.modifyQatFormulaStatus = status;
        },
        modifyQatFormula( state, modifyQatFormula ){
            state.modifyQatFormula = modifyQatFormula;
        },
        insertQatElementStatus( state, status ){
            state.insertQatElementStatus = status;
        },
        insertQatElement( state, insertQatElement ){
            state.insertQatElement = insertQatElement;
        },
        deleteQatElementStatus( state, status ){
            state.deleteQatElementStatus = status;
        },
        deleteQatElement( state, deleteQatElement ){
            state.deleteQatElement = deleteQatElement;
        },
        addQatTemplateStatus( state, status ){
            state.addQatTemplateStatus = status;
        },
        addQatTemplate( state, addQatTemplate ){
            state.addQatTemplate = addQatTemplate;
        },
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
        },
        orderQatElementDataStatus( state ){
            return state.orderQatElementDataStatus;
        },
        orderQatElementData( state ){
            return state.orderQatElementData;
        },
        loadQatFormulaDataStatus( state ){
            return state.loadQatFormulaDataStatus;
        },
        loadQatFormulaData( state ){
            return state.loadQatFormulaData;
        },
        selectKpiFormulaStatus( state ){
            return state.selectKpiFormulaStatus;
        },
        selectKpiFormula( state ){
            return state.selectKpiFormula;
        },
        addQatFormulaStatus( state ){
            return state.addQatFormulaStatus;
        },
        addQatFormula( state ){
            return state.addQatFormula;
        },
        deleteQatFormulaStatus( state ){
            return state.deleteQatFormulaStatus;
        },
        modifyQatFormulaStatus( state ){
            return state.modifyQatFormulaStatus;
        },
        modifyQatFormula( state ){
            return state.modifyQatFormula;
        },
        insertQatElementStatus( state ){
            return state.insertQatElementStatus;
        },
        insertQatElement( state ){
            return state.insertQatElement;
        },
        deleteQatElementStatus( state ){
            return state.deleteQatElementStatus;
        },
        deleteQatElement( state ){
            return state.deleteQatElement;
        },
        addQatTemplateStatus( state ){
            return state.addQatTemplateStatus;
        },
        addQatTemplate( state ){
            return state.addQatTemplate;
        }
    }
}