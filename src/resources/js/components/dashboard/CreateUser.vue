<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <user-form title="Новый пользователь" :is-loading="dataProcessing" @on-submit="createUser"></user-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import UserForm from "./UserForm";

    export default {
        name: "CreateUser",

        components: {
            UserForm,
        },

        data() {
            return {
                dataProcessing: false,
            }
        },

        methods: {
            createUser(data) {
                this.dataProcessing = true;
                this.$store.dispatch('users/createUser', { data })
                    .then(() => {
                        this.$router.push({ name: 'dashboard.users' });
                    })
                    .finally(() => {
                        this.dataProcessing = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>
