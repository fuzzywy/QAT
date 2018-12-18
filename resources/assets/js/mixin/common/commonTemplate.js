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
		}
	}
}