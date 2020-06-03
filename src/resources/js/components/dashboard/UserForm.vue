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
                        <v-col cols="12">
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
                        <v-col cols="12">
                            <v-text-field
                                :disabled="edit"
                                name="email"
                                type="email"
                                append-icon="mdi-email"
                                v-model="form.email"
                                maxlength="255"
                                :error-messages="emailErrors"
                                @blur="$v.form.email.$touch()"
                            >
                                <template v-slot:label>
                                    E-mail <span class="red--text">*</span>
                                </template>
                            </v-text-field>
                        </v-col>  
                        <v-col cols="12" v-if="!edit">
                            <v-text-field
                                name="password"
                                type="text"
                                v-model="form.password"
                                maxlength="255"
                                append-icon='mdi-refresh'
                                @click:append="generatePassword"
                                :error-messages="passwordErrors"
                                @blur="$v.form.password.$touch()"
                            >
                                <template v-slot:label>
                                    Пароль <span class="red--text">*</span>
                                </template>
                            </v-text-field>
                        </v-col>
                        <template v-else>
                            <v-col cols="12">
                                <v-text-field
                                    label="Текущий пароль"
                                    name="current_password"
                                    v-model="form.currentPassword"
                                    maxlength="255"
                                    :append-icon="showCurrentPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                    @click:append="showCurrentPassword = !showCurrentPassword"
                                    :type="showCurrentPassword ? 'text' : 'password'"
                                    :error-messages="currentPasswordErrors"
                                    @blur="$v.form.currentPassword.$touch()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    label="Новый пароль"
                                    name="new_password"
                                    v-model="form.newPassword"
                                    maxlength="255"
                                    :append-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                    @click:append="showNewPassword = !showNewPassword"
                                    :type="showNewPassword ? 'text' : 'password'"
                                    :error-messages="newPasswordErrors"
                                    @blur="$v.form.newPassword.$touch()"
                                ></v-text-field>
                            </v-col>
                        </template>
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
    import { required, requiredIf, email, minLength } from 'vuelidate/lib/validators';

    export default {
        name: "UserForm",

        directives: {
            mask,
        },

        props: {
            title: {
                type: String,
                required: true
            },
            user: {
                type: Object,
                default: null
            },
            isLoading: {
                type: Boolean,
                default: false
            },
            edit: {
                type: Boolean,
                default: false
            }
        },

        validations() {
            let rules = {
                form: {
                    fullName: {
                        required,
                    },
                    phone: {
                        required,
                        minLength: minLength(19),
                    },
                    email: {
                        required,
                        email,
                    },
                }
            };

            if (!this.edit) {
                rules.form.password = {
                    required,
                    minLength: minLength(8),
                };
            } else {
                rules.form.currentPassword = {
                    required: requiredIf('newPassword'),
                    minLength: minLength(8),
                };
                rules.form.newPassword = {
                    required: requiredIf('currentPassword'),
                    minLength: minLength(8),
                };
            }

            return rules;
        },

        data() {
            return {
                phoneMask: '+38 (0##) ###-##-##',
                imagePath: null,
                size: 10,
                characters: 'a-z,A-Z,0-9,#',
                showCurrentPassword: false,
                showNewPassword: false,
                form: {
                    photo: null,
                    fullName: null,
                    phone: null,
                    email: null,
                    password: null,
                    currentPassword: null,
                    newPassword: null
                }
            }
        },

        watch: {
            user() {
                this.initForm();
            },
        },

        computed: {
            avatar() {
                return this.imagePath || '/storage/images/no-profile-image.png';
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
                !this.$v.form.email.required && errors.push('Поле \'E-mail\' не должно быть пустым.');
                !this.$v.form.email.email && errors.push('Некорректный формат электронного адреса.');
                return errors;
            },
            passwordErrors() {
                if (!this.edit) {
                    const errors = [];
                    if (!this.$v.form.password.$dirty) return errors;
                    !this.$v.form.password.required && errors.push('Поле \'Пароль\' не должно быть пустым.');
                    !this.$v.form.password.minLength && errors.push('Минимальная длина пароля 8 символов.');
                    return errors;
                }

                return [];
            },
            currentPasswordErrors() {
                if (this.edit) {
                    const errors = [];
                    if (!this.$v.form.currentPassword.$dirty) return errors;
                    !this.$v.form.currentPassword.required && errors.push('Поле \'Текущий пароль\' не должно быть пустым.');
                    !this.$v.form.currentPassword.minLength && errors.push('Минимальная длина пароля 8 символов.');
                    return errors;
                }

                return [];
            },
            newPasswordErrors() {
                if (this.edit) {
                    const errors = [];
                    if (!this.$v.form.newPassword.$dirty) return errors;
                    !this.$v.form.newPassword.required && errors.push('Поле \'Новый пароль\' не должно быть пустым.');
                    !this.$v.form.newPassword.minLength && errors.push('Минимальная длина пароля 8 символов.');
                    return errors;
                }

                return [];
            },
        },

        created() {
            if (!this.edit) {
                this.generatePassword(); 
            }
        },

        methods: {
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
            generatePassword() {
                let charactersArray = this.characters.split(',');
                let CharacterSet = '';
                let password = '';

                if( charactersArray.indexOf('a-z') >= 0) {
                    CharacterSet += 'abcdefghijklmnopqrstuvwxyz';
                }
                if( charactersArray.indexOf('A-Z') >= 0) {
                    CharacterSet += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                }
                if( charactersArray.indexOf('0-9') >= 0) {
                    CharacterSet += '0123456789';
                }
                if( charactersArray.indexOf('#') >= 0) {
                    CharacterSet += '![]{}()%&*$#^<>~@|';
                }

                for(let i = 0; i < this.size; i++) {
                    password += CharacterSet.charAt(Math.floor(Math.random() * CharacterSet.length));
                }

                this.form.password = password;
            },
            submit() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    let data = this.prepareData();

                    this.$emit('on-submit', data);
                }
            },
            prepareData() {
                let data = {
                    photo: this.form.photo || null,
                    full_name: this.form.fullName || null,
                    phone: this.form.phone || null,
                };

                if (!this.edit) {
                    data.email = this.form.email || null;
                    data.password = this.form.password || null;
                } else {
                    data.current_password = this.form.currentPassword || null;
                    data.new_password = this.form.newPassword || null;
                }

                return data;
            },
            initForm() {
                this.imagePath = this.user.image_path || null;
                this.form = {
                    photo: null,
                    fullName: this.user.full_name || null,
                    phone: this.user.phone || null,
                    email: this.user.email || null,
                    currentPassword: null,
                    newPassword: null,
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
