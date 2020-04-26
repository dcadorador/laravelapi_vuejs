<template>
    <v-card class="mt-3" color="light">
        <v-card-title class="subtitle-2">
            Mapping Carrier and Services <v-spacer></v-spacer><i class="fas fa-truck"></i>
        </v-card-title>
        <v-form
            ref="form"
            class="p-3 pt-0"
            @submit="onSubmit">
            <v-container>
                <v-row dense>
                    <!-- carrier -->
                    <v-col lg="4">
                        <v-autocomplete
                            v-model="carrier"
                            :items="carriers"
                            menu-props="auto"
                            label="Carrier"
                            :rules="[v => !!v || 'Carriers is required']"
                        />
                    </v-col>

                    <!-- carrier service -->
                    <v-col v-if="carrier" lg="6">
                        <v-autocomplete
                            v-model="carrierService"
                            :items="carrierServices"
                            menu-props="auto"
                            label="Carrier Services"
                            :rules="[v => !!v || 'Carrier Services is required']"
                        />
                    </v-col>

                    <v-col v-if="carrierService" class="text-right">
                        <v-btn type="submit" class="mt-3" color="success">Save</v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>

        <v-data-table
            v-if="items"
            :items="items"
            :headers="headers"
            :disable-sort="true">
            <!-- action to view records -->
            <template v-slot:item.actions="{ item }">
                <v-btn small color="error" @click="onDelete(item)">Delete</v-btn>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
import { EventBus } from '../../event-bus.js'

export default {
    props: {
        id: { type: Number, default: null }
    },

    data() {
        return {
            items: [],
            headers: [
                { text: 'Carrier', value: 'carrier' },
                { text: 'Carrier Service', value: 'service' },
                { text: 'Actions', value: 'actions' },
            ],

            carrier: null,
            carriers: [],
            isSubmit: false,
            carrierService: null,
            carrierServices: []
        }
    },

    mounted() {
        this.fetchMappingCarrierServices()
        this.fetchCarriers()
    },

    watch: {
        carrier(value) {
            if (value) {
                console.log('on change carrier : ', value)
                this.fetchCarrierServices(value)
            }

        }
    },


    methods: {

        fetchMappingCarrierServices() {
            this.$http
                .get('/api/fieldmappers/carriers/' + this.id + '/services')
                .then(({ data }) => {
                    if (data.status) {
                        for (let [key, value] of Object.entries(data.result)) {
                            this.items.push({
                                carrier: value.carrier,
                                service: value.service
                            })
                        }
                    }
                    console.log('mapping carriers and services', data)
                })
        },

        fetchCarriers() {
            this.$http
                .get('/api/fieldmappers/carriers/' + this.id)
                .then(({ data }) => {
                    console.log('field mappers : ', data)
                    this.carriers = data.map(icarrier => {
                        return {
                            value: icarrier,
                            text: icarrier.name
                        }
                    })
                })
        },

        fetchCarrierServices(carrier) {
            let params = { integration_id: this.id, carrier_id: carrier.id }
            this.$http
                .post('/api/fieldmappers/carrier/services', params)
                .then(({ data }) => {
                    console.log('carrier service data : ', data)
                    this.carrierServices = data.map(iservice => {
                        return {
                            value: iservice,
                            text: iservice.name
                        }
                    })
                })
        },

        onDelete(map) {
            // remove current item selected
            let index = this.items.findIndex(item => {
                return item.id == map.id
            })

            if (index >= 0) {
                this.items.splice(index, 1)
                this.$http
                    .delete('/api/fieldmappers/carrier/' + this.id)
                    .then(({ data }) => {
                        console.log('carrier services mapping deleted')
                    })
            }
        },

        onSubmit(e) {
            e.preventDefault()

            let params = {
                integration_id: this.id,
                carrier_id: this.carrier.id,
                carrier_name: this.carrier.name,
                carrier_service_name : this.carrierService.name,
                carrier_service_id: this.carrierService.id
            }

            if (this.$refs.form.validate()) {
                this.isSubmit = true
                this.$http
                    .post('/api/fieldmappers/carrier', params)
                    .then(({ data }) => {
                        this.items.push({
                            carrier: this.carrier.name,
                            service: this.carrierService.name
                        })
                        EventBus.$emit('show-success', "Successfully saved carrier service mapping!");
                        console.log('carrier service saved', data)
                        this.carrier = null
                        this.carrierService = null
                    })
            } else {
                EventBus.$emit('show-error', "Fail to save carrier service mapping! please try again.");
            }

        }
    }

}

</script>