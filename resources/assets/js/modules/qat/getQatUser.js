import QatUserAPI from "../../api/qat/getQatUser.js";
export const qatUser = {
    state: {
        getUserData: [],
        getUserDataStatus: 0,

        showReviewData: [],
        showReviewDataStatus: 0,

        getRoleData: [],
        getRoleDataStatus: 0,

        reviewUser: [],
        reviewUserStatus: 0,

        showUserData: [],
        showUserDataStatus: 0,

        modifyUser: [],
        modifyUserStatus: 0,

        deleteUser: [],
        deleteUserStatus: 0,

        showRoleData: [],
        showRoleDataStatus: 0,

        modifyRole: [],
        modifyRoleStatus: 0,

        deleteRole: [],
        deleteRoleStatus: 0,

        showPermissionData: [],
        showPermissionDataStatus: 0
    },
    actions: {
        getUser( { commit } ) {
            commit( 'getUserDataStatus', 1 );
            QatUserAPI.getUser()
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'getUserData', response.data );
                    commit( 'getUserDataStatus', 2 );
                }else {
                    commit( 'getUserData', [ response ] );
                    commit( 'getUserDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'getUserData', [ 'Connection failed' ] );
                commit( 'getUserDataStatus', 3 );
            } )
        },
        showReview( { commit } ) {
            commit( 'showReviewDataStatus', 1 );
            QatUserAPI.showReview()
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'showReviewData', response.data );
                    commit( 'showReviewDataStatus', 2 );
                }else {
                    commit( 'showReviewData', [ response ] );
                    commit( 'showReviewDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'showReviewData', [ 'Connection failed' ] );
                commit( 'showReviewDataStatus', 3 );
            } )
        },
        getRole( { commit } ) {
            commit( 'getRoleDataStatus', 1 );
            QatUserAPI.getRole()
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'getRoleData', response.data );
                    commit( 'getRoleDataStatus', 2 );
                }else {
                    commit( 'getRoleData', [ response ] );
                    commit( 'getRoleDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'getRoleData', [ 'Connection failed' ] );
                commit( 'getRoleDataStatus', 3 );
            } )
        },
        reviewUser( { commit } , data) {
            commit( 'reviewUserStatus', 1 );
            return new Promise(function(resolve, reject){
                QatUserAPI.reviewUser( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'reviewUser', response.data );
                            commit( 'reviewUserStatus', 2 );
                        } else {
                            commit( 'reviewUser', [ response ] );
                            commit( 'reviewUserStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'reviewUser', [ 'Connection failed' ] );
                    commit( 'reviewUserStatus', 3 );
                });
            });
        },
        showUser( { commit } ) {
            commit( 'showUserDataStatus', 1 );
            QatUserAPI.showUser()
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'showUserData', response.data );
                    commit( 'showUserDataStatus', 2 );
                }else {
                    commit( 'showUserData', [ response ] );
                    commit( 'showUserDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'showUserData', [ 'Connection failed' ] );
                commit( 'showUserDataStatus', 3 );
            } )
        },
        modifyUser( { commit } , data) {
            commit( 'modifyUserStatus', 1 );
            return new Promise(function(resolve, reject){
                QatUserAPI.modifyUser( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'modifyUser', response.data );
                            commit( 'modifyUserStatus', 2 );
                        } else {
                            commit( 'modifyUser', [ response ] );
                            commit( 'modifyUserStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'modifyUser', [ 'Connection failed' ] );
                    commit( 'modifyUserStatus', 3 );
                });
            });
        },
        deleteUser( { commit } , data) {
            commit( 'deleteUserStatus', 1 );
            return new Promise(function(resolve, reject){
                QatUserAPI.deleteUser( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'deleteUser', response.data );
                            commit( 'deleteUserStatus', 2 );
                        } else {
                            commit( 'deleteUser', [ response ] );
                            commit( 'deleteUserStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'deleteUser', [ 'Connection failed' ] );
                    commit( 'deleteUserStatus', 3 );
                });
            });
        },
        showRole( { commit } ) {
            commit( 'showRoleDataStatus', 1 );
            QatUserAPI.showRole()
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'showRoleData', response.data );
                    commit( 'showRoleDataStatus', 2 );
                }else {
                    commit( 'showRoleData', [ response ] );
                    commit( 'showRoleDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'showRoleData', [ 'Connection failed' ] );
                commit( 'showRoleDataStatus', 3 );
            } )
        },
        modifyRole( { commit } , data) {
            commit( 'modifyRoleStatus', 1 );
            return new Promise(function(resolve, reject){
                QatUserAPI.modifyRole( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'modifyRole', response.data );
                            commit( 'modifyRoleStatus', 2 );
                        } else {
                            commit( 'modifyRole', [ response ] );
                            commit( 'modifyRoleStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'modifyRole', [ 'Connection failed' ] );
                    commit( 'modifyRoleStatus', 3 );
                });
            });
        },
        deleteRole( { commit } , data) {
            commit( 'deleteRoleStatus', 1 );
            return new Promise(function(resolve, reject){
                QatUserAPI.deleteRole( data )
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'deleteRole', response.data );
                            commit( 'deleteRoleStatus', 2 );
                        } else {
                            commit( 'deleteRole', [ response ] );
                            commit( 'deleteRoleStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'deleteRole', [ 'Connection failed' ] );
                    commit( 'deleteRoleStatus', 3 );
                });
            });
        },
        showPermission( { commit }, data) {
            commit( 'showPermissionDataStatus', 1 );
            QatUserAPI.showPermission(data)
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'showPermissionData', response.data );
                    commit( 'showPermissionDataStatus', 2 );
                }else {
                    commit( 'showPermissionData', [ response ] );
                    commit( 'showPermissionDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'showPermissionData', [ 'Connection failed' ] );
                commit( 'showPermissionDataStatus', 3 );
            } )
        }
    },
    mutations: {
        getUserDataStatus( state, status ) {
            state.getUserDataStatus = status;
        },
        getUserData( state, getUserData ) {
            state.getUserData = getUserData
        },
        showReviewDataStatus( state, status ) {
            state.showReviewDataStatus = status;
        },
        showReviewData( state, showReviewData ) {
            state.showReviewData = showReviewData
        },
        getRoleDataStatus( state, status ) {
            state.getRoleDataStatus = status;
        },
        getRoleData( state, getRoleData ) {
            state.getRoleData = getRoleData
        },
        reviewUserStatus( state, status ) {
            state.reviewUserStatus = status;
        },
        reviewUser( state, reviewUser ) {
            state.reviewUser = reviewUser
        },
        showUserDataStatus( state, status ) {
            state.showUserDataStatus = status;
        },
        showUserData( state, showUserData ) {
            state.showUserData = showUserData
        },
        modifyUserStatus( state, status ) {
            state.modifyUserStatus = status;
        },
        modifyUser( state, modifyUser ) {
            state.modifyUser = modifyUser
        },
        deleteUserStatus( state, status ) {
            state.deleteUserStatus = status;
        },
        deleteUser( state, deleteUser ) {
            state.deleteUser = deleteUser
        },
        showRoleDataStatus( state, status ) {
            state.showRoleDataStatus = status;
        },
        showRoleData( state, showRoleData ) {
            state.showRoleData = showRoleData
        },
        modifyRoleStatus( state, status ) {
            state.modifyRoleStatus = status;
        },
        modifyRole( state, modifyRole ) {
            state.modifyRole = modifyRole
        },
        deleteRoleStatus( state, status ) {
            state.deleteRoleStatus = status;
        },
        deleteRole( state, deleteRole ) {
            state.deleteRole = deleteRole
        },
        showPermissionDataStatus( state, status ) {
            state.showPermissionDataStatus = status;
        },
        showPermissionData( state, showPermissionData ) {
            state.showPermissionData = showPermissionData
        }

    },
    getters: {
        getUserDataStatus( state ){
            return state.getUserDataStatus;
        },
        getUserData( state ){
            return state.getUserData;
        },
        showReviewDataStatus( state ){
            return state.showReviewDataStatus;
        },
        showReviewData( state ){
            return state.showReviewData;
        },
        getRoleDataStatus( state ){
            return state.getRoleDataStatus;
        },
        getRoleData( state ){
            return state.getRoleData;
        },
        reviewUserStatus( state ){
            return state.reviewUserStatus;
        },
        reviewUser( state ){
            return state.reviewUser;
        },
        showUserDataStatus( state ){
            return state.showUserDataStatus;
        },
        showUserData( state ){
            return state.showUserData;
        },
        modifyUserStatus( state ){
            return state.modifyUserStatus;
        },
        modifyUser( state ){
            return state.modifyUser;
        },
        deleteUserStatus( state ){
            return state.deleteUserStatus;
        },
        deleteUser( state ){
            return state.deleteUser;
        },
        showRoleDataStatus( state ){
            return state.showRoleDataStatus;
        },
        showRoleData( state ){
            return state.showRoleData;
        },
        modifyRoleStatus( state ){
            return state.modifyRoleStatus;
        },
        modifyRole( state ){
            return state.modifyRole;
        },
        deleteRoleStatus( state ){
            return state.deleteRoleStatus;
        },
        deleteRole( state ){
            return state.deleteRole;
        },
        showPermissionDataStatus( state ){
            return state.showPermissionDataStatus;
        },
        showPermissionData( state ){
            return state.showPermissionData;
        }
    }
}