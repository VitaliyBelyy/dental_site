let getters = {
  patientDebt: state => state.selectedPatient ? state.selectedPatient.total_accrued - state.selectedPatient.total_paid : 0
};

export default getters;
