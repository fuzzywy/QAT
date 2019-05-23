import QatTaskAPI from '../../api/qat/getQatTask.js';
export const qatTask = {
    state: {
        
        qatTaskDirData: {},
        qatTaskDirDataStatus: 0,

        qatTaskDirFileData: {},
        qatTaskDirFileDataStatus: 0,

        addTaskFileDir: {},
        addTaskFileDirStatus: 0,

        deleteTaskFileDir: {},
        deleteTaskFileDirStatus: 0,

        qatTaskStorageTypeData: {},
        qatTaskStorageTypeDataStatus: 0,

        qatTaskDirTreeData: {},
        qatTaskDirTreeDataStatus: 0,

        saveTaskData: {},
        saveTaskDataStatus: 0,

        qatTaskData: {},
        qatTaskDataStatus: 0,

        deleteTaskData: {},
        deleteTaskDataStatus: 0,

        runTaskData: {},
        runTaskDataStatus: 0,

        stopTaskData: {},
        stopTaskDataStatus: 0

    },
    actions: {
        loadQatTaskDirData( {commit} ) {
            commit( 'qatTaskDirDataStatus', 1 );
            QatTaskAPI.getQatTaskDirData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTaskDirData', response.data );
                    commit( 'qatTaskDirDataStatus', 2 );
                }else {
                    commit( 'qatTaskDirData', [ response ] );
                    commit( 'qatTaskDirDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTaskDirData', [ 'Connection failed' ] );
                commit( 'qatTaskDirDataStatus', 3 );
            });
        },
        loadQatTaskDirFileData( {commit}, data) {
            commit( 'qatTaskDirFileDataStatus', 1 );
            QatTaskAPI.getQatTaskDirFileData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTaskDirFileData', response.data );
                    commit( 'qatTaskDirFileDataStatus', 2 );
                }else {
                    commit( 'qatTaskDirFileData', [ response ] );
                    commit( 'qatTaskDirFileDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTaskDirFileData', [ 'Connection failed' ] );
                commit( 'qatTaskDirFileDataStatus', 3 );
            });
        },
        addTaskFileDir( {commit}, data) {
            commit( 'addTaskFileDirStatus', 1 );
            return new Promise(function(resolve, reject){
                QatTaskAPI.addTaskFileDir( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'addTaskFileDir', response.data );
                            commit( 'addTaskFileDirStatus', 2 );
                        } else {
                            commit( 'addTaskFileDir', [ response ] );
                            commit( 'addTaskFileDirStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'addTaskFileDir', [ 'Connection failed' ] );
                    commit( 'addTaskFileDirStatus', 3 );
                });
            });
        },
        deleteTaskFileDir( {commit}, data) {
            commit( 'deleteTaskFileDirStatus', 1 );
            return new Promise(function(resolve, reject){
                QatTaskAPI.deleteTaskFileDir( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'deleteTaskFileDir', response.data );
                            commit( 'deleteTaskFileDirStatus', 2 );
                        } else {
                            commit( 'deleteTaskFileDir', [ response ] );
                            commit( 'deleteTaskFileDirStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'deleteTaskFileDir', [ 'Connection failed' ] );
                    commit( 'deleteTaskFileDirStatus', 3 );
                });
            });

           
        },
        loadQatTaskStorageTypeData( {commit} ) {
            commit( 'qatTaskStorageTypeDataStatus', 1 );
            QatTaskAPI.getQatTaskStorageTypeData()
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTaskStorageTypeData', response.data );
                    commit( 'qatTaskStorageTypeDataStatus', 2 );
                }else {
                    commit( 'qatTaskStorageTypeData', [ response ] );
                    commit( 'qatTaskStorageTypeDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTaskStorageTypeData', [ 'Connection failed' ] );
                commit( 'qatTaskStorageTypeDataStatus', 3 );
            });
        },
        loadQatTaskDirTreeData( {commit}, data) {
            commit( 'qatTaskDirTreeDataStatus', 1 );
            QatTaskAPI.getQatTaskDirTreeData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTaskDirTreeData', response.data );
                    commit( 'qatTaskDirTreeDataStatus', 2 );
                }else {
                    commit( 'qatTaskDirTreeData', [ response ] );
                    commit( 'qatTaskDirTreeDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTaskDirTreeData', [ 'Connection failed' ] );
                commit( 'qatTaskDirTreeDataStatus', 3 );
            });
        },
        saveTaskData( {commit}, data) {
            commit( 'saveTaskDataStatus', 1 );
            return new Promise(function(resolve, reject){
                QatTaskAPI.saveTask( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'saveTaskData', response.data );
                            commit( 'saveTaskDataStatus', 2 );
                        } else {
                            commit( 'saveTaskData', [ response ] );
                            commit( 'saveTaskDataStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'saveTaskData', [ 'Connection failed' ] );
                    commit( 'saveTaskDataStatus', 3 );
                });
            });
        },
        loadQatTaskData( {commit}, data) {
            commit( 'qatTaskDataStatus', 1 );
            QatTaskAPI.getTaskData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'qatTaskData', response.data );
                    commit( 'qatTaskDataStatus', 2 );
                }else {
                    commit( 'qatTaskData', [ response ] );
                    commit( 'qatTaskDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'qatTaskData', [ 'Connection failed' ] );
                commit( 'qatTaskDataStatus', 3 );
            });
        },
        deleteTaskData( {commit}, data) {
            commit( 'deleteTaskDataStatus', 1 );
            return new Promise(function(resolve, reject){
                QatTaskAPI.deleteTaskData( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'deleteTaskData', response.data );
                            commit( 'deleteTaskDataStatus', 2 );
                        } else {
                            commit( 'deleteTaskData', [ response ] );
                            commit( 'deleteTaskDataStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'deleteTaskData', [ 'Connection failed' ] );
                    commit( 'deleteTaskDataStatus', 3 );
                });
            });
        },
        runTaskData( {commit}, data) {
            commit( 'runTaskDataStatus', 1 );
            QatTaskAPI.runTaskData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'runTaskData', response.data );
                    commit( 'runTaskDataStatus', 2 );
                }else {
                    commit( 'runTaskData', [ response ] );
                    commit( 'runTaskDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'runTaskData', [ 'Connection failed' ] );
                commit( 'runTaskDataStatus', 3 );
            });
        },
        stopTaskData( {commit}, data) {
            commit( 'stopTaskDataStatus', 1 );
            QatTaskAPI.stopTaskData( data )
            .then( function( response ){
                if ( response.data != undefined ) {
                    commit( 'stopTaskData', response.data );
                    commit( 'stopTaskDataStatus', 2 );
                }else {
                    commit( 'stopTaskData', [ response ] );
                    commit( 'stopTaskDataStatus' , 3 ); 
                }
            })
            .catch( function(){
                commit( 'stopTaskData', [ 'Connection failed' ] );
                commit( 'stopTaskDataStatus', 3 );
            });
        }
    },
    mutations: {
        qatTaskDirDataStatus( state, status ){
            state.qatTaskDirDataStatus = status;
        },
        qatTaskDirData( state, qatTaskDirData ){
            state.qatTaskDirData = qatTaskDirData;
        },
        qatTaskDirFileDataStatus( state, status ){
            state.qatTaskDirFileDataStatus = status;
        },
        qatTaskDirFileData( state, qatTaskDirFileData ){
            state.qatTaskDirFileData = qatTaskDirFileData;
        },
        addTaskFileDirStatus( state, status ){
            state.addTaskFileDirStatus = status;
        },
        addTaskFileDir( state, addTaskFileDir ){
            state.addTaskFileDir = addTaskFileDir;
        },
        deleteTaskFileDirStatus( state, status ){
            state.deleteTaskFileDirStatus = status;
        },
        deleteTaskFileDir( state, deleteTaskFileDir ){
            state.deleteTaskFileDir = deleteTaskFileDir;
        },
        qatTaskStorageTypeDataStatus( state, status ){
            state.qatTaskStorageTypeDataStatus = status;
        },
        qatTaskStorageTypeData( state, qatTaskStorageTypeData ){
            state.qatTaskStorageTypeData = qatTaskStorageTypeData;
        },
        qatTaskDirTreeDataStatus( state, status ){
            state.qatTaskDirTreeDataStatus = status;
        },
        qatTaskDirTreeData( state, qatTaskDirTreeData ){
            state.qatTaskDirTreeData = qatTaskDirTreeData;
        },
        saveTaskDataStatus( state, status ){
            state.saveTaskDataStatus = status;
        },
        saveTaskData( state, saveTaskData ){
            state.saveTaskData = saveTaskData;
        },
        qatTaskDataStatus( state, status ){
            state.qatTaskDataStatus = status;
        },
        qatTaskData( state, qatTaskData ){
            state.qatTaskData = qatTaskData;
        },
        deleteTaskDataStatus( state, status ){
            state.deleteTaskDataStatus = status;
        },
        deleteTaskData( state, deleteTaskData ){
            state.deleteTaskData = deleteTaskData;
        },
        runTaskDataStatus( state, status ){
            state.runTaskDataStatus = status;
        },
        runTaskData( state, runTaskData ){
            state.runTaskData = runTaskData;
        },
        stopTaskDataStatus( state, status ){
            state.stopTaskDataStatus = status;
        },
        stopTaskData( state, stopTaskData ){
            state.stopTaskData = stopTaskData;
        }


    },
    getters: {
        qatTaskDirDataStatus( state ){
            return state.qatTaskDirDataStatus;
        },
        qatTaskDirData( state ){
            return state.qatTaskDirData;
        },
        qatTaskDirFileDataStatus( state ){
            return state.qatTaskDirFileDataStatus;
        },
        qatTaskDirFileData( state ){
            return state.qatTaskDirFileData;
        },
        addTaskFileDirStatus( state ){
            return state.addTaskFileDirStatus;
        },
        addTaskFileDir( state ){
            return state.addTaskFileDir;
        },
        deleteTaskFileDirStatus( state ){
            return state.deleteTaskFileDirStatus;
        },
        deleteTaskFileDir( state ){
            return state.deleteTaskFileDir;
        },
        qatTaskStorageTypeDataStatus( state ){
            return state.qatTaskStorageTypeDataStatus;
        },
        qatTaskStorageTypeData( state ){
            return state.qatTaskStorageTypeData;
        },
        qatTaskDirTreeDataStatus( state ){
            return state.qatTaskDirTreeDataStatus;
        },
        qatTaskDirTreeData( state ){
            return state.qatTaskDirTreeData;
        },
        saveTaskDataStatus( state ){
            return state.saveTaskDataStatus;
        },
        saveTaskData( state ){
            return state.saveTaskData;
        },
        qatTaskDataStatus( state ){
            return state.qatTaskDataStatus;
        },
        qatTaskData( state ){
            return state.qatTaskData;
        },
        deleteTaskDataStatus( state ){
            return state.deleteTaskDataStatus;
        },
        deleteTaskData( state ){
            return state.deleteTaskData;
        },
        runTaskDataStatus( state ){
            return state.runTaskDataStatus;
        },
        runTaskData( state ){
            return state.runTaskData;
        },
        stopTaskDataStatus( state ){
            return state.stopTaskDataStatus;
        },
        stopTaskData( state ){
            return state.stopTaskData;
        }
    }
}