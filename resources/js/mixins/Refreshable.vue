<script>
    export default {
        methods: {
            async refreshResource() {
                let currentFilters = _.cloneDeep(this.$store.state[`${this.resourceName}`]['filters']);
                currentFilters.push({class: 'autorefresh_btn'})

                this.$emit('refreshing', true);

                await this.$store.commit(`${this.resourceName}/storeFilters`, currentFilters);

                setTimeout(() => {
                    this.$emit('refreshing', false);
                }, 1000);
            }
        }
    }
</script>
