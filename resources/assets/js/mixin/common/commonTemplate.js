export const common = {
    methods: {
        processLoadingLoginUser() {
            this.$store.dispatch( 'loadQatLoginUser' );
        },
        processLoadTemplateData(datasource, datatype) {
            this.$store.dispatch( 'loadQatTemplateData', {
                dataSource: datasource,
                dataType: datatype
            });
        },
        processinsertTemplateName(auth, templateName, parent) {
            this.$store.dispatch( 'insertTemplateName', {
                auth: auth,
                templateName: templateName,
                parent: parent
            });
        },
        processremoveTemplateName(auth, templateName, id) {
            this.$store.dispatch( 'removeTemplateName', {
                auth: auth,
                templateName: templateName,
                id: id
            });
        },
        processloadElementData(templateName, parent, grandparent, auth) {
            this.$store.dispatch( 'loadQatElementData', {
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                auth: auth
            })
        },
        processLoadElementOrder(element, templateName, parent, grandparent, auth) {
            this.$store.dispatch( 'orderQatElementData', {
                element: element,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                auth: auth
            })
        },
        processSelectKpiFormula(clickElement, elements) {
            this.$store.dispatch( 'selectKpiFormula', {
                clickElement: clickElement,
                elements: elements
            })
        },
        //查找公式
        processLoadFormulaData(/*id, label, templateName, parent, grandparent,*/ /*auth*/) {
            this.$store.dispatch( 'loadQatFormulaData', {
                /*id: id,
                label: label,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,*/
                /*auth: auth*/
            })
        },
        //新建公式
        processAddFormula(name, formula, precision, mode) {
            this.$store.dispatch( 'addQatFormula', {
                kpiName: name,
                kpiFormula: formula,
                kpiPrecision: precision,
                format: mode
            })
        },
        //删除公式
        processDeleteFormula(id) {
            this.$store.dispatch( 'deleteQatFormula', {
                id: id
            })
        },
        //编辑公式
        processModifyFormula(id, kpiName, kpiFormula, kpiPrecision) {
            this.$store.dispatch( 'modifyQatFormula', {
                id: id,
                kpiName: kpiName,
                kpiFormula: kpiFormula,
                kpiPrecision: kpiPrecision
            })
        },
        //插入公式到element
        processInsertElement(templateName, parent, grandparent, ids) {
            this.$store.dispatch( 'insertQatElement', {
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                ids: ids
            })
        },
        //kpiList删除
        processDeleteElement(id, templateName, parent, grandparent) {
            this.$store.dispatch( 'deleteQatElement', {
                id: id,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent
            })
        },
        //新建模板
        processAddTempalte(name, mode) {
            this.$store.dispatch( 'addQatTemplate', {
                templateName: name,
                format: mode
            })
        }
    }
}