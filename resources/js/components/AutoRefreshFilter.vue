<template>
    <div>
        <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
            {{ filter.name }}
        </h3>

        <div class="p-2">
            <select
                :dusk="filter.name + '-filter-select'"
                class="block w-full form-control-sm form-select"
                :value="value"
                @change="handleChange"
            >
                <option v-for="option in filter.options" :value="option.name">
                    {{ option.value }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
    import Refreshable from '../mixins/Refreshable';

    export default {
        mixins: [Refreshable],
        props: {
            resourceName: {
                type: String,
                required: true,
            },
            filterKey: {
                type: String,
                required: true,
            },
            asyncFilter: Object
        },

        data() {
            return {
                timeoutReference: null
            }
        },

        async created() {
            if (!this.asyncFilter) {
                await this.emitRefresh();
            }
        },

        beforeDestroy () {
            clearTimeout(this.timeoutReference);
            this.timeoutReference = null;
        },

        methods: {
            handleChange(event) {
                this.$store.commit(`${this.resourceName}/updateFilterState`, {
                    filterClass: this.filterKey,
                    value: event.target.value
                });

                clearTimeout(this.timeoutReference);

                if (!event.target.value === null) {
                    this.emitRefresh();
                }
                this.$emit('change')
            },
            emitRefresh() {
                this.timeoutReference = setTimeout(async () => {
                    if (this.shouldRefresh() && this.value) {
                        this.emitRefresh();
                    }
                    this.refreshResource();
                }, this.timeout);
            },
            shouldRefresh() {
                return document.querySelector('[data-testid="confirm-action-modal"]') ? false : true;
            }
        },

        computed: {
            filter() {
                const filter = this.$store.getters[`${this.resourceName}/getFilter`](
                    this.filterKey
                );

                return filter !== undefined ? filter : this.asyncFilter;
            },

            value() {
                return this.filter.currentValue;
            },

            timeout() {
                return this.value * 1000;
            }
        },
    }
</script>
