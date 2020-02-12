<template>
    <v-app id="inspire" class="app dashboard-layout">
        <app-drawer class="app__drawer" :show-drawer="showDrawer"></app-drawer>
        <app-toolbar class="app__toolbar" @side-icon-click="handleDrawerVisiable"></app-toolbar>
        <v-content>
            <!-- Page Header -->
            <page-header></page-header>
            <div class="page-wrapper">
                <router-view></router-view>
            </div>
        </v-content>
    </v-app>
</template>

<script>
    import AppDrawer from "../components/dashboard/AppDrawer";
    import AppToolbar from "../components/dashboard/AppToolbar";
    import PageHeader from "../components/dashboard/PageHeader";

    export default {
        name: "Dashboard",

        components: {
            AppDrawer,
            AppToolbar,
            PageHeader
        },

        data() {
            return {
                showDrawer: true
            }
        },

        methods: {
            handleDrawerVisiable() {
                this.showDrawer = !this.showDrawer;
            }
        },
    }
</script>

<style scoped>
    .dashboard-layout {
        background: #fafafa;
    }
</style>
