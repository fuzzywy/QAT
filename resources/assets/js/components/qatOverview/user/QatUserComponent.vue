<template>
    <el-tabs class="box-card_" v-model="activeName" type="border-card" @tab-click="handleClick">
        <el-tab-pane :label="this.$t('messages.menu.Review')" name="Review"><review></review></el-tab-pane>
        <el-tab-pane :label="this.$t('messages.menu.User')" name="User"><user></user></el-tab-pane>
        <el-tab-pane :label="this.$t('messages.menu.Role')" name="Role"><role></role></el-tab-pane>
    </el-tabs>
</template>
<script>
    import Review from './Review.vue';
    import User from './User.vue';
    import Role from './Role.vue';

    export default {

        components: {
            Review: Review,
            User: User,
            Role: Role
        },
        data() {
            return {
                activeName: 'Review'
            };
        },
        methods: {
            handleClick(tab, event) {
                this.$store.dispatch('getRole');
                switch(tab.name){
                    case 'Review':
                        this.$store.dispatch('showReview');
                    break;
                    case 'User':
                        this.$store.dispatch('showUser');
                    break;
                    case 'Role':
                        this.$store.dispatch('showRole');
                    break;
                    default:

                    break;
                }
            }
        }
    };
</script>