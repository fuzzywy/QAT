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
        processinsertTemplateName(auth, templateName, parent, dataSource) {
            this.$store.dispatch( 'insertTemplateName', {
                auth: auth,
                templateName: templateName,
                parent: parent,
                dataSource: dataSource
            });
        },
        processremoveTemplateName(auth, templateName, id, dataSource) {
            this.$store.dispatch( 'removeTemplateName', {
                auth: auth,
                templateName: templateName,
                id: id,
                dataSource: dataSource
            });
        },
        processloadElementData(templateName, parent, grandparent, auth, dataSource) {
            this.$store.dispatch( 'loadQatElementData', {
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                auth: auth,
                dataSource: dataSource,
            })
        },
        processLoadElementOrder(element, templateName, parent, grandparent, auth, dataSource) {
            this.$store.dispatch( 'orderQatElementData', {
                element: element,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                auth: auth,
                dataSource: dataSource
            })
        },
        processSelectKpiFormula(clickElement, elements, dataSource) {
            this.$store.dispatch( 'selectKpiFormula', {
                clickElement: clickElement,
                elements: elements,
                dataSource: dataSource
            })
        },
        //查找公式
        processLoadFormulaData(/*id, label, templateName, parent, grandparent,*/ /*auth*/ dataSource) {
            this.$store.dispatch( 'loadQatFormulaData', {
                /*id: id,
                label: label,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,*/
                /*auth: auth*/
                dataSource: dataSource
            })
        },
        //新建公式
        processAddFormula(name, formula, precision, mode, dataSource) {
            this.$store.dispatch( 'addQatFormula', {
                kpiName: name,
                kpiFormula: formula,
                kpiPrecision: precision,
                format: mode,
                dataSource: dataSource
            })
        },
        //删除公式
        processDeleteFormula(id, dataSource) {
            this.$store.dispatch( 'deleteQatFormula', {
                id: id,
                dataSource: dataSource
            })
        },
        //编辑公式
        processModifyFormula(id, kpiName, kpiFormula, kpiPrecision, dataSource) {
            this.$store.dispatch( 'modifyQatFormula', {
                id: id,
                kpiName: kpiName,
                kpiFormula: kpiFormula,
                kpiPrecision: kpiPrecision,
                dataSource: dataSource
            })
        },
        //插入公式到element
        processInsertElement(templateName, parent, grandparent, ids, dataSource) {
            this.$store.dispatch( 'insertQatElement', {
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                ids: ids,
                dataSource: dataSource
            })
        },
        //kpiList删除
        processDeleteElement(id, templateName, parent, grandparent, dataSource) {
            this.$store.dispatch( 'deleteQatElement', {
                id: id,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,
                dataSource: dataSource
            })
        },
        //新建模板
        processAddTempalte(name, mode, dataSource) {
            this.$store.dispatch( 'addQatTemplate', {
                templateName: name,
                format: mode,
                dataSource: dataSource
            })
        }
    }
}