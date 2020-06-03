<template>
    <v-app class="auth-layout">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm9 md6 lg4>
                        <router-view></router-view>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        name: "Auth"
    }
</script>

<style scoped>
    .auth-layout {
        background: url("/storage/images/auth-bg.jpg") center;
        background-size: cover;
        background-attachment: fixed;
    }
</style>
