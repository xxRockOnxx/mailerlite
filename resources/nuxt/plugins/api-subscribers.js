export default ({ $axios }, inject) => {
  inject('subscribers', {
    list: () => $axios.$get('/api/subscribers'),
    create: (data) => $axios.$post('/api/subscribers', data),
    update: (id, data) => $axios.$put(`/api/subscribers/${id}`, data),
    delete: (id) => $axios.$delete(`/api/subscribers/${id}`),
  })
}
