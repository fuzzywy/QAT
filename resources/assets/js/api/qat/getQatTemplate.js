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
    insertQatTemplateName: function(auth, templateName, parent) {
        return axios.get( 'insertQatTemplateName', {
            params: {
                auth: auth,
                templateName: templateName,
                parent: parent
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
    removeQatTemplateName: function(auth, templateName, id) {
        return axios.get( 'removeQatTemplateName', {
            params: {
                auth: auth,
                templateName: templateName,
                id: id
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
    },
    selectKpiFormula: function(clickElement, elements) {
        return axios.get( 'selectKpiFormula', {
            params: {
                clickElement: clickElement,
                elements: elements
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
    addQatFormula: function(kpiName, kpiFormula, kpiPrecision, format) {
        return axios.get( 'addQatFormula', {
            params: {
                kpiName: kpiName,
                kpiFormula: kpiFormula,
                kpiPrecision: kpiPrecision,
                format: format
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
    deleteQatFormula: function(id) {
        return axios.get( 'deleteQatFormula', {
            params: {
                id: id
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
    modifyQatFormula: function(id, kpiName, kpiFormula, kpiPrecision) {
        return axios.get( 'modifyQatFormula', {
            params: {
                id: id,
                kpiName: kpiName,
                kpiFormula: kpiFormula,
                kpiPrecision: kpiPrecision
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
    insertQatElement: function(templateName, parent, grandparent, ids) {
        return axios.get( 'insertQatElement', {
            params: {
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                ids: ids
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