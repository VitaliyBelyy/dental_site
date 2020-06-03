<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" md="8" class="py-0">
                <user-form 
                    edit
                    :title="profileEditing ? 'Редактировать профиль' : 'Редактировать данные пользователя'"
                    :user="user"
                    :is-loading="dataProcessing"
                    @on-submit="updateUser"
                ></user-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import UserForm from "./UserForm";

    export default {
        name: "UpdatePatient",

        props: ['id'],

        components: {
            UserForm,
        },

        data() {
            return {
                isLoading: false,
                dataProcessing: false,
            }
        },

        computed: {
            user() {
                return this.$store.state.users.selectedUser || {};
            },
            profileEditing() {
                console.log(this.id == this.$store.state.auth.user.id);
                return this.id == this.$store.state.auth.user.id;
            }
        },

        created() {
            this.loadUser();
        },

        methods: {
            loadUser() {
                this.isLoading = true;
                this.$store.dispatch('users/loadUser', this.id)
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            updateUser(data) {
                this.dataProcessing = true;
                this.$store.dispatch('users/updateUser', { id: this.id, data })
                    .then(data => {
                        if (this.profileEditing) {
                            this.$store.dispatch('auth/updateUser', data);
                        }

                        this.$router.go(-1);
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
