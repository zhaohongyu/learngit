<template>

    <div id="app" class="wrapper">
        <main-content></main-content>
    </div>

</template>

<script>

    import mainContent from './components/common/mainContent.vue';

    export default {
        components: {
            mainContent: mainContent,
        },
        data () {
            return {}
        },
    }

</script>

<style scoped>
    @import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css";
    @import "https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css";
</style>