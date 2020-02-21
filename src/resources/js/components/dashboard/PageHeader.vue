<template>
    <v-container fluid class="pt-6" v-if="pageTitle">
        <v-row class="ma-0" justify="center">
            <v-col cols="12" class="py-0">
                <h3 class="page-header">{{ pageTitle }}</h3>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "PageHeader",

    data() {
        return {

        }
    },

    computed: {
        pageTitle() {
            return this.$route.meta.title || '';
        }
    }
}
</script>

<style scoped>
    .page-header {
        font-weight: 300;
        letter-spacing: normal;
        font-size: 32px;
        line-height: 1.2;
    }
</style>
