import QatSiteAPI from "../../api/qat/getQatSite.js";
export const qatSite = {
    state: {
        loadSiteData: [],
        loadSiteDataStatus: 0,

        exportSiteData:[],
        exportSiteDataStatus: 0
    },
    actions: {
        loadSiteData( { commit }, data ) {
            commit( 'loadSiteDataStatus', 1 );
            QatSiteAPI.loadSiteData(data)
            .then( (response) => {
                if ( response.data != undefined ) {
                    commit( 'loadSiteData', response.data );
                    commit( 'loadSiteDataStatus', 2 );
                }else {
                    commit( 'loadSiteData', [ response ] );
                    commit( 'loadSiteDataStatus', 3 );
                }
            } )
            .catch( function() {
                commit( 'loadSiteData', [ 'Connection failed' ] );
                commit( 'loadSiteDataStatus', 3 );
            } )
        },
        exportSiteData( { commit }, data ) {
            commit( 'exportSiteDataStatus', 1 );
            return new Promise(function(resolve, reject){
                QatSiteAPI.exportSiteData(data)
                .then(function(response){
                    setTimeout(function(){
                        if (response.data != undefined) {
                            commit( 'exportSiteData', response.data );
                            commit( 'exportSiteDataStatus', 2 );
                        } else {
                            commit( 'exportSiteData', [ response ] );
                            commit( 'exportSiteDataStatus' , 3 ); 
                        }
                        resolve();
                    }, 1000);                                           
                })
                .catch( function(){
                    commit( 'exportSiteData', [ 'Connection failed' ] );
                    commit( 'exportSiteDataStatus', 3 );
                });
            });
        }
    },
    mutations: {
        loadSiteDataStatus( state, status ) {
            state.loadSiteDataStatus = status;
        },
        loadSiteData( state, loadSiteData ) {
            state.loadSiteData = loadSiteData
        },
        exportSiteDataStatus( state, status ) {
            state.exportSiteDataStatus = status;
        },
        exportSiteData( state, exportSiteData ) {
            state.exportSiteData = exportSiteData
        }
    },
    getters: {
        getLoadSiteData( state ) {
            return state.loadSiteData;
        },
        getLoadSiteDataStatus( state ) {
            return state.loadSiteDataStatus;
        },
        getExportSiteData( state ) {
            return state.exportSiteData;
        },
        getExportSiteDataStatus( state ) {
            return state.exportSiteDataStatus;
        }
    }
}