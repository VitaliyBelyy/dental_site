<template>
<div class="teeth-map">
  <v-container fluid class="teeth-map__container" v-if="!isLoading">
    <v-row no-gutters class="teeth-map__upper-jaw pb-2" justify="center">
      <v-col v-for="tooth in teethMap.upper_jaw" :key="tooth.id">
        <div :class="['teeth-map__tooth-wrapper', {'active': inputModel === tooth.id}]">
          <simple-svg
            @click.native="handleChange(tooth.id)"
            :custom-id="'tooth-' + tooth.id"
            :src="tooth.icon_path"
            :custom-class-name="`teeth-map__tooth ${tooth.reverse ? 'teeth-map__tooth--reverse' : ''}`"
          />
          <span class="teeth-map__tooth-index">{{ tooth.index }}</span>
        </div>
      </v-col>
    </v-row>
    <v-row no-gutters class="teeth-map__lower-jaw pt-2" justify="center">
      <v-col v-for="tooth in teethMap.lower_jaw" :key="tooth.id">
        <div :class="['teeth-map__tooth-wrapper', {'active': inputModel === tooth.id}]">
          <simple-svg
            @click.native="handleChange(tooth.id)"
            :custom-id="'tooth-' + tooth.id"
            :src="tooth.icon_path"
            :custom-class-name="`teeth-map__tooth ${tooth.reverse ? 'teeth-map__tooth--reverse' : ''}`"
          />
          <span class="teeth-map__tooth-index">{{ tooth.index }}</span>
        </div>
      </v-col>
    </v-row>
  </v-container>
  <div class="card-progress" v-else>
    <v-progress-circular
      :size="50"
      color="primary"
      indeterminate
    ></v-progress-circular>
  </div>
</div>
  
</template>

<script>
  export default {
    name: "TeethMap",

    props: {
      value: {
        type: Number,
        required: true
      },
    },

    data() {
      return {
        isLoading: false,
      }
    },

    computed: {
      teethMap() {
        return this.$store.state.teeth.teethMap;
      },
      inputModel: {
        get() {
          return this.value;
        },
        set(value) {
          this.$emit('change', value);
        }
      },
    },

    created() {
      this.loadTeeth();
    },

    methods: {
      loadTeeth() {
        this.isLoading = true;
        this.$store.dispatch('teeth/loadTeeth')
          .finally(() => {
            this.isLoading = false;
          });
      },
      handleChange(value) {
        this.inputModel = value === this.value ? null : value;
      }
    }
  }
</script>

<style lang="scss" scoped>
  .card-progress {
    min-height: 300px;
  }
</style>