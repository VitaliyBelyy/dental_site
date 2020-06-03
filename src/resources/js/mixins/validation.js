export default {
  data() {
    return {
      validationErrors: {},
    }
  },
  methods: {
    handleErrors(errorResponse) {
      this.clearErrors();
      for (let error in errorResponse) {
        this.$set(this.validationErrors, error, errorResponse[error]);
      }
    },
    getError(field) {
      return Object.keys(this.validationErrors).includes(field) ? this.validationErrors[field] : [];
    },
    clearErrors() {
      this.$set(this, 'validationErrors', {});
    }
  }
}
