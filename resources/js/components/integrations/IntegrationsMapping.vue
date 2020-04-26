<template>
    <v-tabs
        class="fusedship-tab"
        background-color="white"
        v-model="tab"
        color="dark">
        <v-tabs-slider></v-tabs-slider>
        <v-tab href="#to-machship">
            TO Machship
        </v-tab>
        <v-tab href="#from-machship">
            FROM Machship
        </v-tab>
        <v-tab href="#value-lookups">
            Value Lookups
        </v-tab>
        <v-tab href="#status-mapper">
            Status Mapper
        </v-tab>

        <v-tab-item value="to-machship">
            <IntegrationsMappingForm :id="integrationId" :direction="'to_machship'" />
        </v-tab-item>

        <v-tab-item value="from-machship">
            <IntegrationsMappingForm :id="integrationId" :direction="'from_machship'" />
        </v-tab-item>

        <v-tab-item value="value-lookups">
            <IntegrationsValueLookupsForm :id="integrationId" :direction="'to_machship'" />
        </v-tab-item>

        <v-tab-item value="status-mapper">
            <StatusMapping :id="integrationId" />
        </v-tab-item>

    </v-tabs>
</template>

<script>
import IntegrationsMappingForm from './IntegrationsMappingForm'
import IntegrationsMappingCarriers from './IntegrationsMappingCarriers'
import IntegrationsValueLookupsForm from './IntegrationsValueLookupsForm'
import StatusMapping from '../tabs/StatusMapping'
import { EventBus } from '../../event-bus.js'

export default {

    components: {
        IntegrationsMappingForm,
        IntegrationsMappingCarriers,
        IntegrationsValueLookupsForm,
        StatusMapping
    },

    data() {
        return {
            tab: 'tab-mapping',
            integrationId: null
        }
    },

    created() {
        this.integrationId = parseInt(this.$route.params.id)
    }
}
</script>