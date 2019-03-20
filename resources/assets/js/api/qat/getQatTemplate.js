export default {
    getQatTemplate: function(dataSource, dataType) {
        return axios.post( 'template/getQatTemplate', {
            dataSource: dataSource,
            dataType: dataType
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
        return axios.post( 'template/getQatLoginUser' )
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
        return axios.post( 'template/getQatTemplateData', {
            dataSource: dataSource,
            dataType: dataType
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
    insertQatTemplateName: function(auth, templateName, parent, dataSource) {
        return axios.post( 'template/insertQatTemplateName', {
            auth: auth,
            templateName: templateName,
            parent: parent,
            dataSource: dataSource
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
    removeQatTemplateName: function(auth, templateName, id, dataSource) {
        return axios.post( 'template/removeQatTemplateName', {
            auth: auth,
            templateName: templateName,
            id: id,
            dataSource: dataSource
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
    loadQatElementData: function(templateName, parent, grandparent, auth, dataSource) {
        console.log(dataSource);
        return axios.post( 'template/loadQatElementData', {
            templateName: templateName,
            parent: parent,
            grandparent: grandparent,
            auth: auth,
            dataSource: dataSource
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
    orderQatElementData: function(element, templateName, parent, grandparent, auth, dataSource) {
        return axios.post( 'template/orderQatElementData', {
            element: element,
            templateName: templateName,
            parent: parent,
            grandparent: grandparent,
            auth: auth,
            dataSource: dataSource
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
    loadQatFormulaData: function(/*id, label, templateName, parent, grandparent,*/ /*auth*/ dataSource) {
        return axios.post( 'template/loadQatFormulaData', {
            dataSource: dataSource
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
    selectKpiFormula: function(clickElement, elements, dataSource) {
        return axios.post( 'template/selectKpiFormula', {
            // params: {
                clickElement: clickElement,
                elements: elements,
                dataSource: dataSource
            // }
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
    addQatFormula: function(kpiName, kpiFormula, kpiPrecision, format, dataSource) {
        return axios.post( 'template/addQatFormula', {
            kpiName: kpiName,
            kpiFormula: kpiFormula,
            kpiPrecision: kpiPrecision,
            format: format,
            dataSource: dataSource
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
    deleteQatFormula: function(id, dataSource) {
        return axios.post( 'template/deleteQatFormula', {
            id: id,
            dataSource: dataSource
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
    modifyQatFormula: function(id, kpiName, kpiFormula, kpiPrecision, dataSource) {
        return axios.post( 'template/modifyQatFormula', {
            id: id,
            kpiName: kpiName,
            kpiFormula: kpiFormula,
            kpiPrecision: kpiPrecision,
            dataSource: dataSource
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
    insertQatElement: function(templateName, parent, grandparent, ids, dataSource) {
        return axios.post( 'template/insertQatElement', {
            templateName: templateName,
            parent: parent,
            grandparent: grandparent,
            ids: ids,
            dataSource: dataSource
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
    deleteQatElement: function(id, templateName, parent, grandparent, dataSource) {
        return axios.post( 'template/deleteQatElement', {
            id: id,
            templateName: templateName,
            parent: parent,
            grandparent: grandparent,
            dataSource: dataSource
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
    addQatTemplate: function(templateName, format, dataSource) {
        return axios.post( 'template/addQatTemplate', {
            templateName: templateName,
            format: format,
            dataSource: dataSource
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