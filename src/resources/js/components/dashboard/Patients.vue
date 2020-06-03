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
                            class="hidden-sm-and-down mr-4"
                        ></v-text-field>
                        <v-btn color="primary" link :to="{ name: 'dashboard.create-patient' }">
                            <v-icon left>mdi-plus</v-icon>
                            Добавить
                        </v-btn>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text class="pa-0">
                        <v-data-table
                            :headers="headers"
                            :items="patients"
                            :server-items-length="totalPatients"
                            :options.sync="options"
                            :loading="isLoading"
                            :items-per-page="15"
                            :footer-props="footerProps"
                            no-data-text="Нет записей"
                            loading-text="Загрузка информации..."
                            class="card-table elevation-1"
                        >
                            <template v-slot:item.avatar="{ item }">
                                <v-avatar size="32">
                                    <img :src="item.image_path" :alt="item.full_name"/>
                                </v-avatar>
                            </template>

                            <template v-slot:item.phone="{ item }">
                                <span class="nowrap">{{ item.phone }}</span>
                            </template>

                            <template v-slot:item.action="{ item }">
                                <v-menu left offset-y origin="center center" :nudge-bottom="10" transition="scale-transition">
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon v-on="on">
                                            <v-icon>mdi-dots-vertical</v-icon>
                                        </v-btn>
                                    </template>

                                    <v-list class="pa-0">
                                        <template v-for="(menuItem, index) in patientActions">
                                            <v-divider
                                                v-if="menuItem.divider"
                                                :key="index"
                                            ></v-divider>
                                            <v-list-item
                                                v-else
                                                @click="menuItem.click ? menuItem.click(item.id) : null"
                                                :key="index"
                                                ripple="ripple"
                                            >
                                                <v-list-item-icon v-if="menuItem.icon" class="mr-4">
                                                    <v-icon>{{ menuItem.icon }}</v-icon>
                                                </v-list-item-icon>
                                                <v-list-item-content>
                                                    <v-list-item-title>{{ menuItem.title }}</v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </template>
                                    </v-list>
                                </v-menu>
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
    const MALE = 0;
    const FEMALE = 1;

    export default {
        name: "Patients",

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
                        text: 'Пол',
                        align: 'left',
                        sortable: false,
                        value: 'gender',
                    },
                    {
                        text: 'Дата рождения',
                        align: 'left',
                        sortable: true,
                        value: 'birth_date',
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false,
                        value: 'action',
                    },
                ],
                patientActions: [
                    {
                        icon: "mdi-eye",
                        click: (id) => {
                            this.$router.push({name: 'dashboard.patient-profile', params: { id }});
                        },
                        title: "Просмотр профиля пациента",
                    },

                    { divider: true },

                    {
                        icon: "mdi-account-details",
                        click: (id) => {
                            this.$router.push({name: 'dashboard.visit-history', params: { id }});
                        },
                        title: "Просмотр истории визитов",
                    },

                    { divider: true },

                    {
                        icon: "mdi-account-cash",
                        click: (id) => {
                            this.$router.push({name: 'dashboard.payment-history', params: { id }});
                        },
                        title: "Просмотр истории оплат",
                    },

                    { divider: true },

                    {
                        icon: "mdi-delete",
                        click: (id) => {
                            this.deletePatient(id);
                        },
                        title: "Удалить пациента",
                    },
                ]
            }
        },

        computed: {
            patients() {
                if (this.$store.state.patients.patients && this.$store.state.patients.patients.length) {
                    return this.$store.state.patients.patients.map(patient => {
                        let gender = null;

                        if (patient.gender || patient.gender === 0) {
                            gender = (+patient.gender === MALE) ? 'мужчина' : (+patient.gender === FEMALE) ? 'женщина' : null;
                        }

                        return {
                            'id': patient.id,
                            'full_name': patient.full_name,
                            'phone': patient.phone,
                            'email': patient.email || null,
                            'gender': gender,
                            'birth_date': patient.birth_date ? this.$options.filters.date(patient.birth_date) : null,
                            'image_path': patient.image_path || '/storage/images/no-profile-image.png',
                        };
                    });
                }

                return [];
            },
            totalPatients() {
                return this.$store.state.patients.pagination.total || 0;
            }
        },

        watch: {
            options: {
                handler() {
                    this.loadPatients();
                },
                deep: true,
            },
            search() {
                this.options.page = 1;
                this.loadPatients();
            }
        },

        methods: {
            loadPatients() {
                let params = {
                    q: this.search,
                    page: this.options.page || 1,
                    limit: this.options.itemsPerPage || 15,
                    sort_by: this.options.sortBy || [],
                    sort_desc: this.options.sortDesc || [],
                };

                this.isLoading = true;
                this.$store.dispatch('patients/loadPatients', { params })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            deletePatient(id) {
                if (confirm('Вы уверены что хотите удалить этого пациента?')) {
                    this.$store.dispatch('patients/deletePatient', id)
                        .then(() => {
                                this.loadPatients();
                            });
                }
            },
        }
    }
</script>

<style scoped>

</style>
