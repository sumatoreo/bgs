<template>
    <div>
        <button type="button" class="btn btn-success btn-sm mb-3" @click="addModal()">
            Добавить
        </button>
        <form>
            <div class="form-group">
                <label for="event">Событие</label>
                <select class="custom-select custom-select-sm" id="event" v-model="event" @change="change">
                    <option selected :value="0">Все события</option>
                    <option v-for="event in events" :key="event.id" :value="event.id">{{ event.name }}
                    </option>
                </select>
            </div>
        </form>
        <ul class="list-group">
            <li class="list-group-item" v-for="participant in participants" :key="participant.id">
                {{ participant.first_name }}, {{ participant.last_name }}, {{ participant.email }}
                <button type="button" class="btn btn-link btn-sm" @click="addEventModal(participant)" :disabled="participant.id === spinner.main">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="participant.id === spinner.event"/>
                    Добавить в Event
                </button>
                <button type="button" class="btn btn-danger btn-sm" @click="remove(participant)" :disabled="participant.id === spinner.main">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="participant.id === spinner.remove"/>
                    Удалить
                </button>
                <button type="button" class="btn btn-primary btn-sm" @click="updateModal(participant)" :disabled="participant.id === spinner.main">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="participant.id === spinner.update"/>
                    Редактировать
                </button>
            </li>
        </ul>
        <add-modal/>
        <update-modal :participant="participant"/>
        <event-modal :participant="participant" />
    </div>
</template>

<script>
    import {eventBus} from "../app";

    export default {
        name: "Participants",
        components: {
            AddModal: () => import('./Modals/AddModal'),
            UpdateModal: () => import('./Modals/UpdateModal'),
            EventModal: () => import('./Modals/EventModal')
        },
        data() {
            return {
                participant: '',
                participants: [],
                events: [],
                event: 0,
                spinner: {
                    main: null,
                    event: null,
                    update: null,
                    remove: null,
                }
            }
        },
        mounted() {
            eventBus.$on('addModal', ({payload}) => {
                this.participants.push(payload);
            });

            eventBus.$on('updateModal', ({payload}) => {
                this.participants = this.participants.filter(function (item) {
                    return item.id !== payload.id
                })

                this.participants.push(payload);
                this.participants.sort(function (a, b) {
                    if (a.id > b.id) return 1;
                    if (a.id < b.id) return -1;
                    return 0;
                });
            });

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

            axios
                .get('/api/participants')
                .then(response => {
                    if (response.data.status === 'success') {
                        this.participants = response.data.payload;
                        this.participants.sort(function (a, b) {
                            if (a.id > b.id) return 1;
                            if (a.id < b.id) return -1;
                            return 0;
                        });
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
            change() {
                let config = {};

                if (this.event > 0) {
                    config = {
                        params: {
                            event_id: this.event
                        }
                    };
                }

                axios
                    .get('/api/participants', config)
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.participants = response.data.payload;
                            this.participants.sort(function (a, b) {
                                if (a.id > b.id) return 1;
                                if (a.id < b.id) return -1;
                                return 0;
                            });
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
            addEventModal(participant) {
                this.participant = participant;
                $('#eventModal').modal();
            },
            addModal() {
                $('#addModal').modal()
            },
            updateModal(participant) {
                this.participant = participant;
                $('#updateModal').modal()
            },
            remove(participant) {
                this._runSpinner(participant.id, 'remove')

                axios
                    .delete('/api/participants/' + participant.id)
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.participants.splice(this.participants.indexOf(participant), 1);
                            this._stopSpinner();
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
            _runSpinner(id, action) {
                this.spinner.main = id;
                this.spinner[action] = id;
            },
            _stopSpinner() {
                this.spinner.main = null;
                this.spinner.event = null;
                this.spinner.update = null;
                this.spinner.remove = null;
            }
        }
    }
</script>

<style scoped>

</style>
