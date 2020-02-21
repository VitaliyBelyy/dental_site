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
                        <v-col cols="12" sm="6">
                            <v-text-field
                                name="first_name"
                                type="text"
                                v-model="form.firstName"
                                maxlength="255"
                                :error-messages="firstNameErrors"
                                @blur="$v.form.firstName.$touch()"
                            >
                                <template v-slot:label>
                                    First Name <span class="red--text">*</span>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                name="last_name"
                                type="text"
                                v-model="form.lastName"
                                maxlength="255"
                                :error-messages="lastNameErrors"
                                @blur="$v.form.lastName.$touch()"
                            >
                                <template v-slot:label>
                                    Last Name <span class="red--text">*</span>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                label="Phone"
                                name="phone"
                                type="text"
                                append-icon="mdi-phone"
                                v-model="form.phone"
                                v-mask="phoneMask"
                                maxlength="255"
                                :error-messages="phoneErrors"
                                @blur="$v.form.phone.$touch()"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                label="Email"
                                name="email"
                                type="email"
                                append-icon="mdi-email"
                                v-model="form.email"
                                maxlength="255"
                                :error-messages="emailErrors"
                                @blur="$v.form.email.$touch()"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-radio-group v-model="form.gender" :mandatory="false" row>
                                <v-radio label="Male" value="0"></v-radio>
                                <v-radio label="Female" value="1"></v-radio>
                            </v-radio-group>
                        </v-col>
                        <v-col cols="12">
                            <v-dialog
                                ref="dialog"
                                v-model="modal"
                                :return-value.sync="form.birthDate"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="form.birthDate"
                                        label="Birth date"
                                        append-icon="mdi-calendar"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="form.birthDate" scrollable :max="new Date().toISOString()">
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.dialog.save(form.birthDate)">OK</v-btn>
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
                                label="Anamnesis"
                            ></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                name="medical_info"
                                label="Medical information"
                                v-model="form.medicalInfo"
                                autocomplete="none"
                                maxlength="1000"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                            <small><span class="red--text">*</span> indicates required field</small>
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
            >Save</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { mask } from 'vue-the-mask';
    import { required, email, minLength } from 'vuelidate/lib/validators';
    import moment from 'moment';

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
                firstName: {
                    required,
                },
                lastName: {
                    required,
                },
                phone: {
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
                modal: false,
                phoneMask: '+38 (0##) ###-##-##',
                imagePath: null,
                form: {
                    photo: null,
                    firstName: null,
                    lastName: null,
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
            }
        },

        computed: {
            avatar() {
                return this.imagePath || '/storage/images/no-profile-image.png';
            },
            anamnesis() {
                return this.$store.state.patients.anamnesis || [];
            },
            firstNameErrors() {
                const errors = [];
                if (!this.$v.form.firstName.$dirty) return errors;
                !this.$v.form.firstName.required && errors.push('The first name is required.');
                return errors;
            },
            lastNameErrors() {
                const errors = [];
                if (!this.$v.form.lastName.$dirty) return errors;
                !this.$v.form.lastName.required && errors.push('The last name is required.');
                return errors;
            },
            phoneErrors() {
                const errors = [];
                if (!this.$v.form.phone.$dirty) return errors;
                !this.$v.form.phone.minLength && errors.push('The phone must be a valid phone number.');
                return errors;
            },
            emailErrors() {
                const errors = [];
                if (!this.$v.form.email.$dirty) return errors;
                !this.$v.form.email.email && errors.push('The email must be a valid email address.');
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
                    first_name: this.form.firstName,
                    last_name: this.form.lastName,
                    phone: this.form.phone || null,
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
                    firstName: this.patient.first_name || null,
                    lastName: this.patient.last_name || null,
                    phone: this.patient.phone || null,
                    email: this.patient.email || null,
                    gender: (this.patient.gender || this.patient.gender === 0) ? this.patient.gender.toString() : null,
                    birthDate: this.patient.birth_date ? moment(this.patient.birth_date).format('YYYY-MM-DD') : null,
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
