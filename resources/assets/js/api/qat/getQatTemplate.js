export default {
    getQatTemplate: function(dataSource, dataType) {
        return axios.get( 'getQatTemplate', {
            params: {
                dataSource: dataSource,
                dataType: dataType
            }
        })
        .catch(function(error) {
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    getQatLoginUser: function() {
        return axios.get( 'getQatLoginUser' )
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    getQatTemplateData: function(dataSource, dataType) {
        return axios.get( 'getQatTemplateData', {
            params: {
                dataSource: dataSource,
                dataType: dataType
            }
        })
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    insertQatTemplateName: function(auth, templateName) {
        return axios.get( 'insertQatTemplateName', {
            params: {
                auth: auth,
                templateName: templateName
            }
        })
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    removeQatTemplateName: function(auth, templateName) {
        return axios.get( 'removeQatTemplateName', {
            params: {
                auth: auth,
                templateName: templateName
            }
        })
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    loadQatElementData: function(templateName, parent, grandparent, auth) {
        return axios.get( 'loadQatElementData', {
            params: {
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                auth: auth
            }
        })
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    orderQatElementData: function(element, templateName, parent, grandparent, auth) {
        return axios.get( 'orderQatElementData', {
            params: {
                element: element,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                auth: auth
            }
        })
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    },
    loadQatFormulaData: function(/*id, label, templateName, parent, grandparent,*/ /*auth*/) {
        return axios.get( 'loadQatFormulaData', {
            params: {
                /*id: id,
                label: label,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,*/
                /*auth: auth*/
            }
        })
        .catch(function(error){
            if (error.response) {
                if ( error.response.status == '404' ) {
                    return error.response.status + ' Not Found';
                }else {
                    return error.response.status;
                }
            } else if (error.request) {
                return 'Request failed'
            } else {
                return 'Other errors'
            }
        });
    }
}