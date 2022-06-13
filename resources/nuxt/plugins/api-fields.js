export default ({ $axios }, inject) => {
  inject('fields', {
    list: (subscriberId) => $axios.$get(`/api/subscribers/${subscriberId}/fields`),
    set: (subscriberId, fields) => $axios.$put(`/api/subscribers/${subscriberId}/fields`, fields),
  })
}
