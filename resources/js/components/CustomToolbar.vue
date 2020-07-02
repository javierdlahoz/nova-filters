<template>
    <div class="custom-index-toolbar jdlabs-filter-menu">
        <jdlabs-filter-menu
            :resource-name="resourceName"
            :soft-deletes="softDeletes"
            :via-resource="viaResource"
            :via-has-one="viaHasOne"
            :trashed="trashed"
            :per-page="perPage"
            :per-page-options="perPageOptions"
            :show-trashed-option="
              authorizedToForceDeleteAnyResources ||
              authorizedToRestoreAnyResources
            "
            @clear-selected-filters="clearSelectedFilters"
            @filter-changed="filterChanged"
            @trashed-changed="trashedChanged"
            @per-page-changed="updatePerPageChanged"
        />
    </div>
</template>
<script>
    import {
        Filterable,
        PerPageable,
        InteractsWithQueryString,
        InteractsWithResourceInformation,
        mapProps,
    } from 'laravel-nova'
    import FilterBar from '../mixins/FilterBar'

    export default {
        mixins: [
            Filterable,
            PerPageable,
            InteractsWithQueryString,
            InteractsWithResourceInformation,
            FilterBar
        ],
        mounted() {
            this.$el.parentElement.parentElement.classList.add('flex-col');
        },
    }
</script>
