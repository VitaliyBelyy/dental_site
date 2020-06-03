<template>
    <v-card class="dashboard-card">
        <v-card-title class="py-4 px-6">
            <h3 class="dashboard-card__title">{{ title }}</h3>
        </v-card-title>

        <v-divider></v-divider>

        <v-card-text class="py-4 px-6">
            <v-form>
                <v-container class="pa-0">
                    <v-row>
                        <v-col cols="12">
                            <v-btn text class="upload-button" @click="pickFile">
                                <v-img
                                    :src="avatar"
                                    width="125"
                                    height="125"
                                ></v-img>
                                <v-icon small color="grey lighten-5" class="upload-button__icon">mdi-camera</v-icon>
                            </v-btn>
                            <input
                                type="file"
                                style="display: none"
                                ref="uploader"
                                accept="image/*"
                                @change="onFilePicked"
                            >
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                name="full_name"
                                type="text"
                                v-model="form.fullName"
                                maxlength="255"
                                :error-messages="fullNameErrors"
                                @blur="$v.form.fullName.$touch()"
                            >
                                <template v-slot:label>
                                    Имя <span class="red--text">*</span>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                name="phone"
                                type="text"
                                append-icon="mdi-phone"
                                v-model="form.phone"
                                v-mask="phoneMask"
                                maxlength="255"
                                :error-messages="phoneErrors"
                                @blur="$v.form.phone.$touch()"
                            >
                                <template v-slot:label>
                                    Телефон <span class="red--text">*</span>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                label="E-mail"
                                name="email"
                                type="email"
                                append-icon="mdi-email"
                                v-model="form.email"
                                maxlength="255"
                                :error-messages="emailErrors"
                                @blur="$v.form.email.$touch()"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-radio-group v-model="form.gender" :mandatory="false" row>
                                <v-radio label="Мужчина" value="0"></v-radio>
                                <v-radio label="Женщина" value="1"></v-radio>
                            </v-radio-group>
                        </v-col>
                        <v-col cols="12">
                            <v-dialog
                                ref="dialog"
                                v-model="dialog"
                                :return-value.sync="form.birthDate"
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="form.birthDate"
                                        label="Дата рождения"
                                        append-icon="mdi-calendar"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    ref="picker"
                                    v-model="form.birthDate"
                                    :max="new Date().toISOString().substr(0, 10)"
                                    min="1950-01-01"
                                    scrollable
                                    @change="$refs.dialog.save(form.birthDate)"
                                >
                                </v-date-picker>
                            </v-dialog>
                        </v-col>
                        <v-col cols="12">
                            <v-select
                                v-model="form.anamnesisId"
                                :loading="isLoadingAnamnesis"
                                :items="anamnesis"
                                item-text="name"
                                item-value="id"
                                label="Анамнез"
                            ></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                name="medical_info"
                                label="Медицинские сведения"
                                v-model="form.medicalInfo"
                                autocomplete="none"
                                maxlength="1000"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                            <small><span class="red--text">*</span> Обязательные поля</small>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
        </v-card-text>

        <v-card-actions class="pt-0 pb-4 px-6">
            <v-spacer></v-spacer>
            <v-btn color="primary"
                   :loading="isLoading"
                   @click.prevent="submit"
            >Сохранить</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { mask } from 'vue-the-mask';
    import { required, email, minLength } from 'vuelidate/lib/validators';

    export default {
        name: "PatientForm",

        directives: {
            mask,
        },

        props: {
            title: {
                type: String,
                required: true
            },
            patient: {
                type: Object,
            },
            isLoading: {
                type: Boolean,
                default: false
            }
        },

        validations: {
            form: {
                fullName: {
                    required,
                },
                phone: {
                    required,
                    minLength: minLength(19),
                },
                email: {
                    email,
                },
            }
        },

        data() {
            return {
                isLoadingAnamnesis: false,
                dialog: false,
                phoneMask: '+38 (0##) ###-##-##',
                imagePath: null,
                form: {
                    photo: null,
                    fullName: null,
                    phone: null,
                    email: null,
                    gender: null,
                    birthDate: null,
                    anamnesisId: null,
                    medicalInfo: null,
                }
            }
        },

        watch: {
            patient() {
                this.initForm();
            },
            dialog(val) {
                val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'));
            },
        },

        computed: {
            avatar() {
                return this.imagePath || '/storage/images/no-profile-image.png';
            },
            anamnesis() {
                return this.$store.state.patients.anamnesis || [];
            },
            fullNameErrors() {
                const errors = [];
                if (!this.$v.form.fullName.$dirty) return errors;
                !this.$v.form.fullName.required && errors.push('Поле \'Имя\' не должно быть пустым.');
                return errors;
            },
            phoneErrors() {
                const errors = [];
                if (!this.$v.form.phone.$dirty) return errors;
                !this.$v.form.phone.required && errors.push('Поле \'Телефон\' не должно быть пустым.');
                !this.$v.form.phone.minLength && errors.push('Некорректный формат номера телефона.');
                return errors;
            },
            emailErrors() {
                const errors = [];
                if (!this.$v.form.email.$dirty) return errors;
                !this.$v.form.email.email && errors.push('Некорректный формат электронного адреса.');
                return errors;
            },
        },

        created() {
            this.loadAnamnesis();
        },

        methods: {
            loadAnamnesis() {
                this.isLoadingAnamnesis = true;
                this.$store.dispatch('patients/loadAnamnesis')
                    .finally(() => {
                        this.isLoadingAnamnesis = false;
                    });
            },
            pickFile() {
                this.$refs.uploader.click();
            },
            onFilePicked(e) {
                const files = e.target.files;

                if(files[0] !== undefined) {
                    let imageName = files[0].name;

                    if(imageName.lastIndexOf('.') <= 0) {
                        return;
                    }

                    const fr = new FileReader();
                    fr.readAsDataURL(files[0]);
                    fr.addEventListener('load', () => {
                        this.imagePath = fr.result;
                        this.form.photo = files[0];
                    });
                } else {
                    this.imagePath = null;
                    this.form.photo = null;
                }
            },
            submit() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    let data = this.prepareData();

                    this.$emit('on-submit', data);
                }
            },
            prepareData() {
                return {
                    photo: this.form.photo || null,
                    full_name: this.form.fullName,
                    phone: this.form.phone,
                    email: this.form.email || null,
                    gender: this.form.gender || null,
                    birth_date: this.form.birthDate || null,
                    anamnesis_id: this.form.anamnesisId || null,
                    medical_info: this.form.medicalInfo || null,
                };
            },
            initForm() {
                this.imagePath = this.patient.image_path || null;
                this.form = {
                    photo: null,
                    fullName: this.patient.full_name || null,
                    phone: this.patient.phone || null,
                    email: this.patient.email || null,
                    gender: (this.patient.gender || this.patient.gender === 0) ? this.patient.gender.toString() : null,
                    birthDate: this.patient.birth_date ? this.$options.filters.date(this.patient.birth_date) : null,
                    anamnesisId: (this.patient.anamnesis && this.patient.anamnesis.id) ? this.patient.anamnesis.id : null,
                    medicalInfo: this.patient.medical_info || null,
                };
            }
        }
    }
</script>

<style scoped>
    .upload-button {
        position: relative;
        height: auto !important;
        min-width: initial !important;
        padding: 0 !important;
    }
    .upload-button__icon {
        position: absolute;
        right: 10px;
        bottom: 5px;
    }
</style>
