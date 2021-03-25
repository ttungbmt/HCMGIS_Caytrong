<template>
    <div class="item-template">
        <p>{{ item.label }}</p>
    </div>
</template>

<script>
    export default {
        props: {
            item: { required: true },
        },
    }
</script>
