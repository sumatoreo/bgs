<template>
    <ul class="list-group">
        <li class="list-group-item" v-for="event in events" :key="event.id">
            {{ event.name }}
            <ul class="list-group" v-if="event.participants.length > 0">
                <li class="list-group-item" v-for="participant in event.participants" :key="participant.id">
                    {{ participant.first_name }}, {{ participant.last_name }}, {{ participant.email }}
                    <button type="button" class="btn btn-link btn-sm" @click="detach(event, participant)"
                            :disabled="participant.id === spinner">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                              v-if="participant.id === spinner"/>
                        Убрать
                    </button>
                </li>
            </ul>
        </li>
    </ul>
</template>

<script>
    import {eventBus} from "../app";

    export default {
        name: "Events",
        data() {
            return {
                events: [],
                spinner: ''
            }
        },
        mounted() {
            this.load();

            eventBus.$on('eventLoad', () => {
                this.load();
            });
        },
        methods: {
            load() {
                axios
                    .get('/api/events')
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.events = response.data.payload;
                        }
                    })
                    .catch(({response}) => {
                        if (response.data.status === 'error') {
                            response.data.stack.forEach(message => {
                                console.warn(message);
                            })
                        }
                    });
            },
            detach(event, participant) {
                this.spinner = participant.id;

                axios
                    .put('/api/events/' + event.id, {
                        'action': 'detach',
                        'participant_id': participant.id
                    })
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.spinner = '';
                            this.load();
                        }
                    })
                    .catch(({response}) => {
                        if (response.data.status === 'error') {
                            response.data.stack.forEach(message => {
                                console.warn(message);

                                this.spinner = '';
                            })
                        }
                    })
            }
        }
    }
</script>

<style scoped>

</style>
