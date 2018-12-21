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
        processinsertTemplateName(auth, templateName) {
            this.$store.dispatch( 'insertTemplateName', {
                auth: auth,
                templateName: templateName
            });
        },
        processremoveTemplateName(auth, templateName) {
            this.$store.dispatch( 'removeTemplateName', {
                auth: auth,
                templateName: templateName
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
        processLoadFormulaData(/*id, label, templateName, parent, grandparent,*/ /*auth*/) {
            this.$store.dispatch( 'loadQatFormulaData', {
                /*id: id,
                label: label,
                templateName: templateName,
                parent: parent,
                grandparent: grandparent,*/
                /*auth: auth*/
            })
        }
    }
}