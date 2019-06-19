import QatKgetAPI from '../../api/qat/getQatKget.js';
export const qatKget = {
    state: {
        
        qatKgetMoData: {},
        qatKgetMoDataStatus: 0,

        qatKgetMoByParamFilterData: {},
        qatKgetMoByParamFilterDataStatus: 0,

        qatKgetTaskData: {},
        qatKgetTaskDataStatus: 0,

        qatKgetParamData: {},
        qatKgetParamDataStatus: 0,

        qatKgetData: {},
        qatKgetDataStatus: 0,
        
        cancelToken: {},

        qatKgetFileData: {},
        qatKgetFileDataStatus: 0,

        qatCreateKgetCrontabTaskData: {},
        qatCreateKgetCrontabTaskDataStatus: 0,

        qatKgetCrontabTaskData: {},
        qatKgetCrontabTaskDataStatus: 0,

        qatCheckKgetTaskName: {},
        qatCheckKgetTaskNameStatus: 0,

        qatDeleteKgetCrontabTask: {},
        qatDeleteKgetCrontabTaskStatus: 0,

        qatKgetMoListData: {},
        qatKgetMoListDataStatus: 0
    },
    actions: {
        loadQatKgetMoData( {commit} ) {
            commit( 'qatKgetMoDataStatus', 1 );
            QatKgetAPI.getQatKgetMoData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetMoData', response.data );
                    commit( 'qatKgetMoDataStatus', 2 );
                }else {
                    commit( 'qatKgetMoData', [ response ] );
                    commit( 'qatKgetMoDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetMoData', [ 'Connection failed' ] );
                commit( 'qatKgetMoDataStatus', 3 );
            });
        },
        loadMoByParamFilterData( {commit}, data) {
            commit( 'qatKgetMoByParamFilterDataStatus', 1 );
            QatKgetAPI.getMoByParamFilterData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetMoByParamFilterData', response.data );
                    commit( 'qatKgetMoByParamFilterDataStatus', 2 );
                }else {
                    commit( 'qatKgetMoByParamFilterData', [ response ] );
                    commit( 'qatKgetMoByParamFilterDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetMoByParamFilterData', [ 'Connection failed' ] );
                commit( 'qatKgetMoByParamFilterDataStatus', 3 );
            });
        },
        loadQatKgetTaskData ( {commit} ) {
            commit( 'qatKgetTaskDataStatus', 1 );
            QatKgetAPI.getKgetTaskData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetTaskData', response.data );
                    commit( 'qatKgetTaskDataStatus', 2 );
                }else {
                    commit( 'qatKgetTaskData', [ response ] );
                    commit( 'qatKgetTaskDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetTaskData', [ 'Connection failed' ] );
                commit( 'qatKgetTaskDataStatus', 3 );
            });
        },
        loadMoByParamFilterData( {commit}, data) {
            commit( 'qatKgetMoByParamFilterDataStatus', 1 );
            QatKgetAPI.getMoByParamFilterData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetMoByParamFilterData', response.data );
                    commit( 'qatKgetMoByParamFilterDataStatus', 2 );
                }else {
                    commit( 'qatKgetMoByParamFilterData', [ response ] );
                    commit( 'qatKgetMoByParamFilterDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetMoByParamFilterData', [ 'Connection failed' ] );
                commit( 'qatKgetMoByParamFilterDataStatus', 3 );
            });
        },
        loadKgetParamData( {commit}, data) {
            commit( 'qatKgetParamDataStatus', 1 );
            QatKgetAPI.getKgetParamData(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetParamData', response.data );
                    commit( 'qatKgetParamDataStatus', 2 );
                }else {
                    commit( 'qatKgetParamData', [ response ] );
                    commit( 'qatKgetParamDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetParamData', [ 'Connection failed' ] );
                commit( 'qatKgetParamDataStatus', 3 );
            });
        },
        loadQatKgetData( {commit}, data) {
            this.cancelToken = axios.CancelToken.source();
            data['cancelToken'] = this.cancelToken;
            commit( 'qatKgetDataStatus', 1 );
            return new Promise(function(resolve, reject){
                QatKgetAPI.getKgetData(data)
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'qatKgetData', response.data );
                            commit( 'qatKgetDataStatus', 2 );
                        } else {
                            commit( 'qatKgetData', [ response ] );
                            commit( 'qatKgetDataStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'qatKgetData', [ 'Connection failed' ] );
                    commit( 'qatKgetDataStatus', 3 );
                });
            });
        },
        cancel() {
            this.cancelToken.cancel('取消查询');
        },
        qatKgetFileData( {commit}, data) {
            commit( 'qatKgetFileDataStatus', 1 );
            return new Promise(function(resolve, reject){
                QatKgetAPI.exportKgetFile(data)
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'qatKgetFileData', response.data );
                            commit( 'qatKgetFileDataStatus', 2 );
                        } else {
                            commit( 'qatKgetFileData', [ response ] );
                            commit( 'qatKgetFileDataStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'qatKgetFileData', [ 'Connection failed' ] );
                    commit( 'qatKgetFileDataStatus', 3 );
                });
            });
        },
        insertKgetCrontabTask( {commit}, data) {
            commit( 'qatCreateKgetCrontabTaskDataStatus', 1 );
            QatKgetAPI.insertKgetCrontabTask(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatCreateKgetCrontabTaskData', response.data );
                    commit( 'qatCreateKgetCrontabTaskDataStatus', 2 );
                }else {
                    commit( 'qatCreateKgetCrontabTaskData', [ response ] );
                    commit( 'qatCreateKgetCrontabTaskDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatCreateKgetCrontabTaskData', [ 'Connection failed' ] );
                commit( 'qatCreateKgetCrontabTaskDataStatus', 3 );
            });
        },
        getKgetCrontabTask( {commit}, data ) {
            commit( 'qatKgetCrontabTaskDataStatus', 1 );
            QatKgetAPI.getKgetCrontabTask(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetCrontabTaskData', response.data );
                    commit( 'qatKgetCrontabTaskDataStatus', 2 );
                }else {
                    commit( 'qatKgetCrontabTaskData', [ response ] );
                    commit( 'qatKgetCrontabTaskDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetCrontabTaskData', [ 'Connection failed' ] );
                commit( 'qatKgetCrontabTaskDataStatus', 3 );
            });
        },
        checkKgetTaskName( {commit}, data ) {
            commit( 'qatCheckKgetTaskNameStatus', 1 );
            QatKgetAPI.checkKgetTaskName(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatCheckKgetTaskName', response.data );
                    commit( 'qatCheckKgetTaskNameStatus', 2 );
                }else {
                    commit( 'qatCheckKgetTaskName', [ response ] );
                    commit( 'qatCheckKgetTaskNameStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatCheckKgetTaskName', [ 'Connection failed' ] );
                commit( 'qatCheckKgetTaskNameStatus', 3 );
            });
        },
        deleteKgetCrontabTask( {commit}, data ) {
            commit( 'qatDeleteKgetCrontabTaskStatus', 1 );
            QatKgetAPI.deleteKgetCrontabTask(data)
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatDeleteKgetCrontabTask', response.data );
                    commit( 'qatDeleteKgetCrontabTaskStatus', 2 );
                }else {
                    commit( 'qatDeleteKgetCrontabTask', [ response ] );
                    commit( 'qatDeleteKgetCrontabTaskStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatDeleteKgetCrontabTask', [ 'Connection failed' ] );
                commit( 'qatDeleteKgetCrontabTaskStatus', 3 );
            });
        },
        loadQatKgetMoList( {commit} ) {
            commit( 'qatKgetMoListDataStatus', 1 );
            QatKgetAPI.getQatKgetMoListData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatKgetMoListData', response.data );
                    commit( 'qatKgetMoListDataStatus', 2 );
                }else {
                    commit( 'qatKgetMoListData', [ response ] );
                    commit( 'qatKgetMoListDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatKgetMoListData', [ 'Connection failed' ] );
                commit( 'qatKgetMoListDataStatus', 3 );
            });
        }
    },
    mutations: {
        qatKgetMoDataStatus( state, status ){
            state.qatKgetMoDataStatus = status;
        },
        qatKgetMoData( state, qatKgetMoData ){
            state.qatKgetMoData = qatKgetMoData;
        },
        qatKgetMoByParamFilterDataStatus( state, status ){
            state.qatKgetMoByParamFilterDataStatus = status;
        },
        qatKgetMoByParamFilterData( state, qatKgetMoByParamFilterData ){
            state.qatKgetMoByParamFilterData = qatKgetMoByParamFilterData;
        },
        qatKgetTaskDataStatus( state, status ){
            state.qatKgetTaskDataStatus = status;
        },
        qatKgetTaskData( state, qatKgetTaskData ){
            state.qatKgetTaskData = qatKgetTaskData;
        },
        qatKgetParamDataStatus( state, status ){
            state.qatKgetParamDataStatus = status;
        },
        qatKgetParamData( state, qatKgetParamData ){
            state.qatKgetParamData = qatKgetParamData;
        },
        qatKgetDataStatus( state, status ){
            state.qatKgetDataStatus = status;
        },
        qatKgetData( state, qatKgetData ){
            state.qatKgetData = qatKgetData;
        },
        qatKgetDataStatus( state, status ){
            state.qatKgetDataStatus = status;
        },
        qatKgetData( state, qatKgetData ){
            state.qatKgetData = qatKgetData;
        },
        qatKgetFileDataStatus( state, status ){
            state.qatKgetFileDataStatus = status;
        },
        qatKgetFileData( state, qatKgetFileData ){
            state.qatKgetFileData = qatKgetFileData;
        },
        qatCreateKgetCrontabTaskDataStatus( state, status ){
            state.qatCreateKgetCrontabTaskDataStatus = status;
        },
        qatCreateKgetCrontabTaskData( state, qatCreateKgetCrontabTaskData ){
            state.qatCreateKgetCrontabTaskData = qatCreateKgetCrontabTaskData;
        },
        qatKgetCrontabTaskDataStatus( state, status ){
            state.qatKgetCrontabTaskDataStatus = status;
        },
        qatKgetCrontabTaskData( state, qatKgetCrontabTaskData ){
            state.qatKgetCrontabTaskData = qatKgetCrontabTaskData;
        },
        qatCheckKgetTaskNameStatus( state, status ){
            state.qatCheckKgetTaskNameStatus = status;
        },
        qatCheckKgetTaskName( state, qatCheckKgetTaskName ){
            state.qatCheckKgetTaskName = qatCheckKgetTaskName;
        },
        qatDeleteKgetCrontabTaskStatus( state, status ){
            state.qatDeleteKgetCrontabTaskStatus = status;
        },
        qatDeleteKgetCrontabTask( state, qatDeleteKgetCrontabTask ){
            state.qatDeleteKgetCrontabTask = qatDeleteKgetCrontabTask;
        },
        qatKgetMoListDataStatus( state, status ){
            state.qatKgetMoListDataStatus = status;
        },
        qatKgetMoListData( state, qatKgetMoListData ){
            state.qatKgetMoListData = qatKgetMoListData;
        }
    },
    getters: {
        qatKgetMoDataStatus( state ){
            return state.qatKgetMoDataStatus;
        },
        qatKgetMoData( state ){
            return state.qatKgetMoData;
        },
        qatKgetMoByParamFilterDataStatus( state ){
            return state.qatKgetMoByParamFilterDataStatus;
        },
        qatKgetMoByParamFilterData( state ){
            return state.qatKgetMoByParamFilterData;
        },
        qatKgetTaskDataStatus( state ){
            return state.qatKgetTaskDataStatus;
        },
        qatKgetTaskData( state ){
            return state.qatKgetTaskData;
        },
        qatKgetParamDataStatus( state ){
            return state.qatKgetParamDataStatus;
        },
        qatKgetParamData( state ){
            return state.qatKgetParamData;
        },
        qatKgetDataStatus( state ){
            return state.qatKgetDataStatus;
        },
        qatKgetData( state ){
            return state.qatKgetData;
        },
        qatKgetDataStatus( state ){
            return state.qatKgetDataStatus;
        },
        qatKgetData( state ){
            return state.qatKgetData;
        },
        qatKgetFileDataStatus( state ){
            return state.qatKgetFileDataStatus;
        },
        qatKgetFileData( state ){
            return state.qatKgetFileData;
        },
        qatCreateKgetCrontabTaskDataStatus( state ){
            return state.qatCreateKgetCrontabTaskDataStatus;
        },
        qatCreateKgetCrontabTaskData( state ){
            return state.qatCreateKgetCrontabTaskData;
        },
        qatKgetCrontabTaskDataStatus( state ){
            return state.qatKgetCrontabTaskDataStatus;
        },
        qatKgetCrontabTaskData( state ){
            return state.qatKgetCrontabTaskData;
        },
        qatCheckKgetTaskNameStatus( state ){
            return state.qatCheckKgetTaskNameStatus;
        },
        qatCheckKgetTaskName( state ){
            return state.qatCheckKgetTaskName;
        },
        qatDeleteKgetCrontabTaskStatus( state ){
            return state.qatDeleteKgetCrontabTaskStatus;
        },
        qatDeleteKgetCrontabTask( state ){
            return state.qatDeleteKgetCrontabTask;
        },
        qatKgetMoListDataStatus( state ){
            return state.qatKgetMoListDataStatus;
        },
        qatKgetMoListData( state ){
            return state.qatKgetMoListData;
        }
    }
}