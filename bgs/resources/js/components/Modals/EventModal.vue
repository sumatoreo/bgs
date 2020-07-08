<template>
    <div class="modal" tabindex="-1" role="dialog" id="eventModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить в мероприятие</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="event">Событие</label>
                            <select class="custom-select custom-select-sm" id="event" v-model="event">
                                <option disabled selected>Выберите событие</option>
                                <option v-for="event in events" :key="event.id" :value="event.id">{{ event.name }}
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Закрыть
                    </button>
                    <button type="button" class="btn btn-primary" @click="add" :disabled="event === ''">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="spinner"/>
                        Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "../../app";

    export default {
        name: "EventModal",
        props: ['participant'],
        data() {
            return {
                spinner: false,
                events: [],
                event: ''
            }
        },
        mounted() {
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
                })
        },
        methods: {
            add() {
                this.spinner = true;

                axios
                    .put('/api/events/' + this.event, {
                        'action': 'attach',
                        'participant_id': this.participant.id
                    })
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.event = '';
                            this.spinner = false;

                            eventBus.$emit('eventLoad');

                            $('#eventModal').modal('toggle');
                        }
                    })
                    .catch(({response}) => {
                        if (response.data.status === 'error') {
                            response.data.stack.forEach(message => {
                                console.warn(message);

                                this.spinner = false;
                            })
                        }
                    })
            }
        }
    }
</script>

<style scoped>

</style>
