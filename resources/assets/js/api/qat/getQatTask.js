import Common from '../common.js';
export default {
    getQatTaskStorageTypeData: function( data ) {
    	return axios.get( 'storage/getStorageType')
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
    getTaskData: function() {
        return axios.get( 'storage/getTask')
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