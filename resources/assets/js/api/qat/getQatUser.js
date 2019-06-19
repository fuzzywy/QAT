import Common from '../common.js';
export default {
    getUser: function() {
        return axios.get( 'user/getUser')
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    showReview: function() {
        return axios.get( 'user/showReview')
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    getRole: function() {
        return axios.get( 'user/getRoles')
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    reviewUser: function( data ) {
        return axios.post( 'user/reviewUser', data)
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    showUser: function() {
        return axios.get( 'user/showUser')
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    modifyUser: function( data ) {
        return axios.post( 'user/modifyUser', data)
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    deleteUser: function( data ) {
        return axios.post( 'user/deleteUser', data)
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    showRole: function() {
        return axios.get( 'user/showRole')
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    modifyRole: function( data ) {
        return axios.post( 'user/modifyRole', data)
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    deleteRole: function( data ) {
        return axios.post( 'user/deleteRole', data)
        .catch(function(error){
            return Common.catchError(error);
        });
    },
    showPermission: function( data ) {
        return axios.post( 'user/showPermission', data)
        .catch(function(error){
            return Common.catchError(error);
        });
    }
 }