import Common from '../common.js';
export default {
    loadSiteData: function( postData ) {
        return axios.post('site/loadSiteData', postData)
        .catch(function(error) {
            return Common.catchError(error);
        });
    },
    exportSiteData: function( postData ) {
        return axios.post('site/exportSiteData', postData)
        .catch(function(error) {
            return Common.catchError(error);
        });
    }
 }