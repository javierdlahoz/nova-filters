Nova.booting((Vue, router, store) => {
    Vue.component('jdlabs-autorefresh-btn', require('./components/AutoRefreshButton').default)
    Vue.component('autorefresh', require('./components/AutoRefreshFilter').default)
    Vue.component('filter-menu', require('./components/FilterMenu').default)
    Vue.component('jdlabs-filter-menu', require('./components/JdlabsFilterMenu').default)
})
