<template>
    <div class="modal" tabindex="-1" role="dialog" id="updateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать пользователя</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <ul>
                            <li v-for="error in errors" :key="error">{{error}}</li>
                        </ul>
                    </div>
                    <form>
                        <div class="form-group">
                            <label for="first_name">Имя</label>
                            <input v-model="form.first_name" type="text" class="form-control" name="first_name" id="first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Фамилия</label>
                            <input v-model="form.last_name" type="text" class="form-control" name="last_name" id="last_name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input v-model="form.email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Закрыть
                    </button>
                    <button type="button" class="btn btn-primary" @click="update">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="spinner"/>
                        Обновить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "../../app";

    export default {
        name: "UpdateModal",
        props: ['participant'],
        data() {
            return {
                spinner: false,
                errors: [],
                form: {
                    first_name: '',
                    last_name: '',
                    email: '',
                }
            }
        },
        watch: {
            participant: function (newParticipant, oldParticipant) {
                this.form.first_name = newParticipant.first_name;
                this.form.last_name = newParticipant.last_name;
                this.form.email = newParticipant.email;
            }
        },
        methods: {
            update() {
                this.errors = [];
                this.spinner = true;

                axios
                    .put('/api/participants/' + this.participant.id, this.form)
                    .then(response => {
                        if (response.data.status === 'success') {
                            eventBus.$emit('updateModal', {
                                payload: response.data.payload
                            })

                            this.spinner = false;

                            $('#updateModal').modal('toggle');
                        }
                    })
                    .catch(({response}) => {
                        if (response.data.status === 'error') {
                            response.data.stack.forEach(message => {
                                this.errors = message;
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
