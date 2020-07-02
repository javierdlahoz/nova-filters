<script>
    export default {
        props: {
            resourceName: String
        },
        computed: {
            /**
             * Get the name of the trashed constraint query string variable.
             */
            trashedParameter() {
                return this.viaRelationship
                    ? this.viaRelationship + '_trashed'
                    : this.resourceName + '_trashed'
            },

            /**
             * Get the name of the per page query string variable.
             */
            perPageParameter() {
                return this.viaRelationship
                    ? this.viaRelationship + '_per_page'
                    : this.resourceName + '_per_page'
            },

            /**
             * Get the name of the page query string variable.
             */
            pageParameter() {
                return this.viaRelationship
                    ? this.viaRelationship + '_page'
                    : this.resourceName + '_page'
            },

            viaRelationship() {
                return ''
            },
        }
    }
</script>
