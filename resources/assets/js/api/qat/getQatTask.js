import Common from '../common.js';
export default {
    getQatTaskDirData: function() {
        return axios.get( 'log/getDir')
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatTaskDirFileData: function( data ) {
        return axios.post( 'log/getFileByDir', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    addTaskFileDir: function( data ) {
        return axios.post( 'log/addDir', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    deleteTaskFileDir: function( data ) {
        return axios.post( 'log/deleteDir', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatTaskStorageTypeData: function( data ) {
    	return axios.get( 'storage/getStorageType')
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getQatTaskDirTreeData: function( data ) {
        return axios.post( 'storage/getStorageDirByType', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    saveTask: function( data ) {
        return axios.post( 'storage/saveTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    getTaskData: function( data ) {
        return axios.post( 'storage/getTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    deleteTaskData: function( data ) {
        return axios.post( 'storage/deleteTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    runTaskData: function( data ) {
        return axios.post( 'storage/runTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    },
    stopTaskData: function( data ) {
        return axios.post( 'storage/stopTask', data)
        .catch(function(error){
            Common.catchError(error);
        });
    }
}