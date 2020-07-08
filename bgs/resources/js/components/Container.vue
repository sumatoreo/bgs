<template>
    <div class="container-fluid p-2" ref="container">
        <div class="d-flex">
            <div class="w-50 p-3">
                <events/>
            </div>
            <div class="w-50 p-3">
                <participants/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Container",
        components: {
            Events: () => import('./Events'),
            Participants: () => import('./Participants')
        }
    }
</script>

<style scoped>

</style>
