<template>
    <v-app-bar color="primary" fixed dark app>
        <v-toolbar-title>
            <v-app-bar-nav-icon @click="handleDrawerToggle"></v-app-bar-nav-icon>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
            <v-menu offset-y origin="center center" :nudge-bottom="10" transition="scale-transition">
                <template v-slot:activator="{ on }">
                    <v-btn text large v-on="on">
                        <v-avatar size="30px" class="mr-2">
                            <img :src="avatar" alt="Doctor"/>
                        </v-avatar>
                        {{ (user && user.full_name) ? user.full_name : 'unknown user' }}
                        <v-icon class="ml-2">mdi-chevron-down</v-icon>
                    </v-btn>
                </template>

                <v-list class="pa-0">
                    <v-list-item
                        v-for="(item, index) in accountMenu"
                        :to="!item.href ? { name: item.name } : null"
                        :href="item.href"
                        @click.prevent="item.click"
                        ripple="ripple"
                        :disabled="item.disabled"
                        :key="index"
                    >
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon v-if="item.icon">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-toolbar-items>
    </v-app-bar>
</template>

<script>
    export default {
        name: "AppToolbar",

        data() {
            return {
                accountMenu: [
                    {
                        icon: "mdi-pencil",
                        href: "#",
                        title: 'Edit profile',
                        click: this.handleEdit
                    },
                    {
                        icon: "mdi-exit-to-app",
                        href: "#",
                        title: 'Logout',
                        click: this.handleLogout
                    }
                ]
            }
        },

        methods: {
            handleDrawerToggle() {
                this.$store.dispatch('app/setDrawerState', !this.$store.state.app.drawer);
            },
            handleEdit() {
                let user = this.$store.state.auth.user;
                
                if (user && user.id) {
                    this.$router.push({ name: 'dashboard.edit-user', params: { id: user.id }});
                }
            },
            handleLogout() {
                this.$store.dispatch('auth/logout')
                    .then(() => {
                        this.$router.push({ name: 'auth.login' });
                    })
            },
        },

        computed: {
            avatar() {
                return "/storage/images/avatar.svg";
            },
            user() {
                return this.$store.state.auth.user;
            }
        },
    }
</script>

<style scoped>

</style>
