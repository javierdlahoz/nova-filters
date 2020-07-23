<template>
    <div class="flex filter-menu-wrapper">
        <dropdown
            class="flex-grow relative"
            v-if="filteredFilters.length > 0 || softDeletes || !viaResource"
            v-click-outside="hideMenu"
        >
            <div
                class="dropdown-trigger h-dropdown-trigger flex items-center cursor-pointer select-none bg-30 px-3 border-2 border-30 rounded"
                :class="{ 'bg-primary border-primary': filtersAreApplied }"
                @click="toggleShowMenu"
                :active="filtersAreApplied"
            >
                <icon
                    type="filter"
                    :class="filtersAreApplied ? 'text-white' : 'text-80'"
                />

                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" class="ml-2">
                    <path v-bind:fill="filtersAreApplied ? `#fff` : `var(--90)`"
                          d="M8.292893.292893c.390525-.390524 1.023689-.390524 1.414214 0 .390524.390525.390524 1.023689 0 1.414214l-4 4c-.390525.390524-1.023689.390524-1.414214 0l-4-4c-.390524-.390525-.390524-1.023689 0-1.414214.390525-.390524 1.023689-.390524 1.414214 0L5 3.585786 8.292893.292893z"></path>
                </svg>

                <span v-if="filtersAreApplied" class="ml-2 font-bold text-white text-80">
                    {{ activeFilterCount }}
                </span>
            </div>

            <div class="jdlabs-dropdown-menu select-none overflow-hidden bg-white border border-60 shadow rounded-lg"
                 v-bind:class="wrapperStyles"
                 v-show="showFilterMenu">
                <scroll-wrap :height="550" class="flex flex-wrap">

                    <!-- Custom Filters -->
                    <component
                        v-bind:class="filterStyles"
                        v-for="filter in filteredFilters"
                        :resource-name="resourceName"
                        :key="filter.name"
                        :filter-key="filter.class"
                        :is="filter.component"
                        :lens="lens"
                        @input="$emit('filter-changed')"
                        @change="$emit('filter-changed')"
                    />

                    <!-- Soft Deletes -->
                    <div v-bind:class="filterStyles">
                        <h3
                            slot="default"
                            class="text-sm uppercase tracking-wide text-80 bg-30 p-3"
                        >
                            {{ __('Trashed') }}
                        </h3>

                        <div class="p-2">
                            <select
                                slot="select"
                                class="block w-full form-control-sm form-select"
                                dusk="trashed-select"
                                :value="trashed"
                                @change="trashedChanged"
                            >
                                <option value="" v-bind:selected="!trashed || trashed === ''">&mdash;</option>
                                <option value="with" v-bind:selected="trashed === 'with'">{{ __('With Trashed') }}</option>
                                <option value="only" v-bind:selected="trashed === 'only'">{{ __('Only Trashed') }}</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="filtersAreApplied" class="bg-30 border-b border-60 w-full py-4">
                        <button
                            @click="clearFilters"
                            class="flex items-center justify-center py-2 w-full block text-xs uppercase tracking-wide text-center text-80 dim font-bold focus:outline-none"
                        >
                            <i class="fa fa-2x fa-trash mr-2"></i>
                            {{ __('Reset Filters') }}
                        </button>
                    </div>
                </scroll-wrap>
            </div>
        </dropdown>

        <!-- Per Page Natural -->
        <div v-if="!viaResource">
            <div class="flex flex-row p-2 per-page items-center text-80 bg-30 ml-4 border-2 border-30 rounded">
                <div class="mr-2 label font-bold uppercase"> {{ __('Per Page') }}</div>
                <select
                    slot="select"
                    dusk="per-page-select"
                    class="block w-full form-control-sm form-select"
                    :value="perPage"
                    @change="perPageChanged"
                >
                    <option v-for="option in perPageOptions" :key="option">
                        {{ option }}
                    </option>
                </select>
            </div>
        </div>
        <div v-else v-if="perPageFilter">
            <div class="flex flex-row p-2 per-page items-center text-80 bg-30 ml-4 border-2 border-30 rounded">
                <div class="mr-2 label font-bold uppercase"> {{ perPageFilter.name }}</div>
                <select
                    slot="select"
                    :dusk="`${perPageFilter.name}-filter-select`"
                    class="block w-full form-control-sm form-select"
                    :value="perPageFilter.currentValue"
                    @change="dynamicPerPageChanged"
                >
                    <option v-for="option in perPageFilter.options" :key="option.value">
                        {{ option.value }}
                    </option>
                </select>
            </div>
        </div>

        <div class="" v-if="autoRefreshFilter">
            <jdlabs-autorefresh-btn :resource-name="resourceName" :filter="autoRefreshFilter"/>
        </div>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
    directives: {
        ClickOutside
    },
    props: {
        resourceName: String,
        lens: {
            type: String,
            default: '',
        },
        softDeletes: Boolean,
        viaResource: String,
        viaHasOne: Boolean,
        trashed: {
            type: String,
            validator: value => ['', 'with', 'only'].indexOf(value) != -1,
        },
        perPage: [String, Number],
        showTrashedOption: {
            type: Boolean,
            default: true,
        },
    },

    data () {
        return {
            showFilterMenu: false,
            perPageOptions: [25, 50, 100]
        }
    },

    methods: {
        trashedChanged (event) {
            this.$emit('trashed-changed', event.target.value)
        },

        perPageChanged (event) {
            this.$emit('per-page-changed', event.target.value)
        },

        toggleShowMenu () {
            this.showFilterMenu = !this.showFilterMenu
        },

        clearFilters () {
            this.$emit('clear-selected-filters')
        },

        applyFilters () {
            this.$emit('filter-changed')
        },

        hideMenu () {
            this.showFilterMenu = false
        },

        dynamicPerPageChanged (event) {
            this.$store.commit(`${this.resourceName}/updateFilterState`, {
                filterClass: this.perPageFilter.class,
                value: event.target.value,
            })

            this.$emit('change')
        },

        getFilterByClass(classname) {
            try {
                let filters = this.filters.filter(filter => filter.class === classname)

                if (filters.length > 0) {
                    return filters[0]
                }
            } catch (e) {
                return null
            }

            return null
        }
    },

    computed: {
        /**
         * Return the filters from state
         */
        filters () {
            return this.$store.getters[`${this.resourceName}/filters`]
        },

        perPageFilter () {
            return this.getFilterByClass('App\\Nova\\Filters\\PerPage')
        },

        autoRefreshFilter () {
            return this.getFilterByClass('Jdlabs\\Nova\\Filters\\Autorefresh')
        },

        filteredFilters () {
            try {
                return this.filters.filter(filter => !['App\\Nova\\Filters\\PerPage', 'Jdlabs\\Nova\\Filters\\Autorefresh'].includes(filter.class))
            } catch (e) {
                return this.filters
            }
        },

        /**
         * Determine via state whether filters are applied
         */
        filtersAreApplied () {
            return this.$store.getters[`${this.resourceName}/filtersAreApplied`]
        },

        /**
         * Return the number of active filters
         */
        activeFilterCount () {
            return this.$store.getters[`${this.resourceName}/activeFilterCount`]
        },

        /**
         * Return the styles to be used on the filters
         */
        filterStyles () {
            if (this.filteredFilters.length < 4) {
                return 'w-full'
            }

            return `w-1/4`
        },

        wrapperStyles () {
            if (this.filteredFilters.length < 4) {
                return 'filters-small'
            }

            return `filters-full`
        }
    },
}
</script>
