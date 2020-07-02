Nova.booting((Vue, router, store) => {
    Vue.component('theear-autorefresh-btn', require('./components/AutoRefreshButton').default)
    Vue.component('autorefresh', require('./components/AutoRefreshFilter').default)
    // Vue.component('custom-index-toolbar', require('./components/CustomToolbar').default)
    Vue.component('filter-menu', require('./components/FilterMenu').default)
    Vue.component('theear-filter-menu', require('./components/TheEarFilterMenu').default)
})
