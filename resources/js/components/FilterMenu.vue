<template>
    <div class="custom-filter-menu theear-filter-menu">
        <theear-filter-menu
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
            @filter-changed="$emit('filter-changed')"
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
        methods: {
            trashedChanged(trashedStatus) {
                this.trashed = trashedStatus
                this.updateQueryString({ [this.trashedParameter]: this.trashed })
            },
            /**
             * Update the per page parameter in the query string
             */
            updatePerPageChanged(perPage) {
                this.perPage = perPage
                this.perPageChanged()
                this.$emit('per-page-changed', perPage)
            }
        },
    }
</script>
