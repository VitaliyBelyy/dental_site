<template>
    <v-navigation-drawer class="app__drawer" :mini-variant.sync="mini" app v-model="showDrawer" :width="drawWidth">
        <v-toolbar color="primary darken-1" dark>
            <img :src="logo" width="36" height="36" alt="App logo"/>
            <v-toolbar-title class="ml-0 pl-3">
                <span class="hidden-sm-and-down">Vue Material</span>
            </v-toolbar-title>
        </v-toolbar>

        <vue-perfect-scrollbar class="drawer-menu__scroll" :settings="scrollSettings">
            <v-list dense nav expand class="py-6">
                <v-list-item-group color="primary">
                    <v-list-item
                        v-for="item in menu"
                        :to="!item.href ? { name: item.name } : null"
                        :href="item.href"
                        ripple="ripple"
                        :disabled="item.disabled"
                        :key="item.name"
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
    import menu from "@/js/config/menu";
    import VuePerfectScrollbar from "vue-perfect-scrollbar";

    export default {
        name: "AppDrawer",

        components: {
            VuePerfectScrollbar
        },

        props: {
            expanded: {
                type: Boolean,
                default: true
            },
            drawWidth: {
                type: [Number, String],
                default: "260"
            },
            showDrawer: Boolean
        },

        data() {
            return {
                mini: false,
                menu: menu,
                scrollSettings: {
                    maxScrollbarLength: 160
                }
            }
        },

        computed: {
            logo() {
                return "/storage/images/logo.svg";
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
