<template>
    <section class="container">

    </section>

</template>

<script>
    import {Drag, Drop} from 'vue-drag-drop';

    export default {
        name: 'Home',

        data() {
            return {
                msg  : 'Welcome to Your Vue.js Frontend App',
                input: '',
            };
        },

        components: {
            Drag,
            Drop,
        },
        methods   : {
            // 拖放图片后的操作
            handleDrop(data) {
                let image = new Image();
                image.src = data.image;
                this.dropElements.push(image);
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">


</style>