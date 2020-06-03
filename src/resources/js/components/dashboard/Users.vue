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
                            placeholder="Поиск..."
                            v-model="search"
                            hide-details
                            class="hidden-sm-and-down"
                        ></v-text-field>
                        <v-btn color="primary" link :to="{ name: 'dashboard.create-user' }">
                            <v-icon left>mdi-plus</v-icon>
                            Добавить
                        </v-btn>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text class="pa-0">
                        <v-data-table
                            :headers="headers"
                            :items="users"
                            :server-items-length="totalUsers"
                            :options.sync="options"
                            :loading="isLoading"
                            :items-per-page="15"
                            :footer-props="footerProps"
                            class="card-table elevation-1"
                        >
                            <template v-slot:item.avatar="{ item }">
                                <v-avatar size="32">
                                    <img :src="item.image_path || '/storage/images/no-profile-image.png'" :alt="item.full_name"/>
                                </v-avatar>
                            </template>

                            <template v-slot:item.phone="{ item }">
                                <span class="nowrap">{{ item.phone }}</span>
                            </template>

                            <template v-slot:item.action="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon link :to="{ name: 'dashboard.edit-user', params: { id: item.id }}">
                                            <v-icon small v-on="on">mdi-pencil</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Редактировать</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon @click="deleteUser(item.id)">
                                            <v-icon small v-on="on">mdi-delete</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Удалить</span>
                                </v-tooltip>
                            </template>

                            <template v-slot:footer.page-text="props">
                                {{props.pageStart}} - {{props.pageStop}} из {{props.itemsLength}}
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
                    'items-per-page-options': [15, 30, 45],
                    'items-per-page-text': 'Элементов на странице:'
                },
                headers: [
                    {
                        text: 'ID',
                        align: 'left',
                        sortable: false,
                        value: 'id',
                    },
                    {
                        text: 'Фото',
                        align: 'left',
                        sortable: false,
                        value: 'avatar',
                    },
                    {
                        text: 'Имя',
                        align: 'left',
                        sortable: true,
                        value: 'full_name',
                    },
                    {
                        text: 'Телефон',
                        align: 'left',
                        sortable: false,
                        value: 'phone',
                    },
                    {
                        text: 'E-mail',
                        align: 'left',
                        sortable: false,
                        value: 'email',
                    },
                    {
                        text: '',
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
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || null,
                    sort_desc: this.options.sortDesc || null,
                };

                this.isLoading = true;
                this.$store.dispatch('users/loadUsers', { params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            deleteUser(id) {
                if (confirm('Вы уверены что хотите удалить этого пользователя?')) {
                    this.$store.dispatch('users/deleteUser', id)
                        .then(() => {
                                this.loadUsers();
                            });
                }
            },
        }
    }
</script>

<style scoped>
    .text--crossed {
        text-decoration: line-through;
    }
</style>
