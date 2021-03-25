<template>
    <form-map view-type="detail" v-bind="$props"/>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
