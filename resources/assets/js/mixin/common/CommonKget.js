export const CommonKget = {
	methods: {
        processLoadCity(data) {
            console.log('processLoadCity');
            console.log(data);
            this.$store.dispatch( 'loadQatParamCityData', data);
        },
        processLoadOperator(data) {
            console.log('processLoadOperator');
            console.log(data);
            this.$store.dispatch( 'loadQatParamOperatorData', data);
        }
	}
}