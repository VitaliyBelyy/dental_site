<template>
    <v-container fluid>
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <v-card>
                    <v-toolbar flat color="white">
                        <v-text-field
                            flat
                            solo
                            prepend-icon="search"
                            placeholder="Type something"
                            v-model="search"
                            hide-details
                            class="hidden-sm-and-down"
                        ></v-text-field>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text class="pa-0">
                        <v-data-table
                            :headers="headers"
                            :items="users"
                            :server-items-length="totalUsers"
                            :options.sync="options"
                            :loading="isLoading"
                            :items-per-page="5"
                            :footer-props="footerProps"
                            :multi-sort="true"
                            class="elevation-1"
                        >
                            <template v-slot:item.fullname="{ item }">
                                <v-chip color="red" dark v-if="!!item.deleted_at">{{ item.fullname }}</v-chip>
                                <span v-else>{{ item.fullname }}</span>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <v-tooltip
                                    bottom
                                    v-if="!!item.deleted_at"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon @click="restoreUser(item.id)">
                                            <v-icon small v-on="on">mdi-delete-restore</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Restore deleted user</span>
                                </v-tooltip>
                                <v-tooltip
                                    bottom
                                    v-else
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon @click="deleteUser(item.id)">
                                            <v-icon small v-on="on">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Delete user</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "Users",

        data() {
            return {
                search: null,
                isLoading: false,
                options: {},
                footerProps: {
                    'items-per-page-options': [5, 15, 30]
                },
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        sortable: false,
                        value: 'id',
                    },
                    {
                        text: 'Full Name',
                        align: 'left',
                        sortable: true,
                        value: 'fullname',
                    },
                    {
                        text: 'Email',
                        align: 'left',
                        sortable: true,
                        value: 'email',
                    },
                    {
                        text: 'Actions',
                        align: 'left',
                        sortable: false,
                        value: 'action',
                    },
                ],
            }
        },

        computed: {
            users() {
                return this.$store.state.users.users || [];
            },
            totalUsers() {
                return this.$store.state.users.pagination.total || 0;
            }
        },

        // created() {
        //     this.loadUsers();
        // },

        watch: {
            options: {
                handler() {
                    this.loadUsers();
                },
                deep: true,
            },
            search() {
                this.options.page = 1;
                this.loadUsers();
            }
        },

        methods: {
            loadUsers() {
                let params = {
                    q: this.search,
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 5,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('users/loadUsers', { params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            deleteUser(id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    this.$store.dispatch('users/deleteUser', id);
                }
            },
            restoreUser(id) {
                this.$store.dispatch('users/restoreUser', id);
            },
        }
    }
</script>

<style scoped>

</style>
