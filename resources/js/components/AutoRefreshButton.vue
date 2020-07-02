<template>
    <div class="autorefresh-btn flex flex-row relative border-2 border-30 rounded"
         v-click-outside="hideMenu">
        <div
            class="btn dropdown-trigger h-dropdown-trigger flex items-center cursor-pointer select-none bg-30 px-3 border-2 border-30"
            @click="refreshResourceTable"
        >
            <i class="text-80 fa fa-2x fa-refresh" v-bind:class="{'fa-spin': refreshing}"></i>
        </div>
        <div
            class="dropdown-trigger h-dropdown-trigger flex items-center cursor-pointer select-none bg-30 px-3 border-2 border-30"
            @click="toggleShowMenu">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" class="ml-2">
                <path v-bind:fill="`var(--90)`"
                      d="M8.292893.292893c.390525-.390524 1.023689-.390524 1.414214 0 .390524.390525.390524 1.023689 0 1.414214l-4 4c-.390525.390524-1.023689.390524-1.414214 0l-4-4c-.390524-.390525-.390524-1.023689 0-1.414214.390525-.390524 1.023689-.390524 1.414214 0L5 3.585786 8.292893.292893z"></path>
            </svg>
        </div>
        <div class="jdlabs-dropdown-menu select-none overflow-hidden bg-white border border-60 shadow rounded-lg"
             v-show="showFilterMenu">
            <scroll-wrap :height="550" class="flex flex-wrap">
                <div class="w-full">
                    <autorefresh :async-filter="filter"
                                 :filter-key="filter.class"
                                 @refreshing="catchRefreshing($event)"
                                 :resource-name="resourceName"/>
                </div>
            </scroll-wrap>
        </div>
    </div>
</template>

<script>
    import ClickOutside from 'vue-click-outside';
    import Refreshable from '../mixins/Refreshable';

    export default {
        mixins: [Refreshable],

        directives: {
            ClickOutside
        },

        props: {
            resourceName: String,
            filter: Object
        },

        data() {
            return {
                refreshing: false,
                showFilterMenu: false
            }
        },

        methods: {
            catchRefreshing(value) {
                this.refreshing = value;
            },

            toggleShowMenu() {
                this.showFilterMenu = !this.showFilterMenu;
            },

            clearFilters() {
                this.$emit('clear-selected-filters');
            },

            hideMenu() {
                this.showFilterMenu = false;
            },

            refreshResourceTable() {
                this.refreshing = true;
                this.refreshResource();

                setTimeout(() => {
                    this.refreshing = false;
                }, 1000);
            },
        }
    }
</script>
