<template>
<div class="teeth-map">
  <template v-if="windowWidth <= 500">
    <v-select
      clearable
      hide-details
      item-text="id"
      item-value="id"
      v-model="inputModel"
      :items="mobileOptions"
      label="Select teeth"
    ></v-select>
  </template>

  <template v-else>
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
            <span class="teeth-map__tooth-index">{{ tooth.id }}</span>
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
            <span class="teeth-map__tooth-index">{{ tooth.id }}</span>
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
  </template>
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
        windowWidth: window.innerWidth,
      }
    },

    computed: {
      teethMap() {
        return this.$store.state.teeth.teethMap;
      },
      mobileOptions() {
        return this.teethMap.upper_jaw && this.teethMap.lower_jaw ? this.teethMap.upper_jaw.concat(this.teethMap.lower_jaw) : [];
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
      window.addEventListener("resize", this.handleResize);
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
      },
      handleResize() {
        this.windowWidth = window.innerWidth;
      }
    },

    destroyed() {
      window.removeEventListener("resize", this.handleResize);
    }
  }
</script>

<style lang="scss" scoped>
  .card-progress {
    min-height: 300px;
  }
</style>