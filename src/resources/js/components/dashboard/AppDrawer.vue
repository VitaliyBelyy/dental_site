<template>
    <v-navigation-drawer class="app__drawer" :mini-variant.sync="mini" app v-model="drawer" :width="drawWidth">
        <v-toolbar color="primary darken-1" dark>
            <img :src="logo" width="36" height="36" alt="App logo"/>
            <v-toolbar-title class="ml-0 pl-3">
                <span class="hidden-sm-and-down">Dental CRM</span>
            </v-toolbar-title>
        </v-toolbar>

        <vue-perfect-scrollbar class="drawer-menu__scroll" :settings="scrollSettings">
            <v-list dense nav expand class="py-6">
                <v-list-item-group color="primary">
                    <v-list-item
                        v-for="item in menu"
                        :key="item.name"
                        ripple="ripple"
                        :to="item.name ? { name: item.name } : null"
                        exact
                    >
                        <v-list-item-icon v-if="item.icon">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title class="drawer-menu__item">{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </vue-perfect-scrollbar>
    </v-navigation-drawer>
</template>

<script>
    import VuePerfectScrollbar from "vue-perfect-scrollbar";
    import menu from "@/js/config/menu";

    export default {
        name: "AppDrawer",

        components: {
            VuePerfectScrollbar
        },

        props: {
            drawWidth: {
                type: [Number, String],
                default: "260"
            },
        },

        data() {
            return {
                mini: false,
                scrollSettings: {
                    maxScrollbarLength: 160
                }
            }
        },

        computed: {
            logo() {
                return "/storage/images/logo.svg";
            },
            isAdmin() {
                return this.$store.getters['auth/isAdmin'];
            },
            menu() {
                return menu.filter(item => item.admin ? this.isAdmin : true);
            },
            drawer: {
                get: function () {
                    return this.$store.state.app.drawer;
                },
                set: function (newValue) {
                    this.$store.dispatch('app/setDrawerState', newValue);
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
    .app__drawer {
        overflow: hidden;

        .drawer-menu__scroll {
            height: calc(100vh - 64px);
            overflow: auto;
        }
    }

    .drawer-menu__item {
        font-size: 0.925rem !important;
    }
</style>
