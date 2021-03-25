<template>
    <default-field :errors="errors" :field="field" :show-help-text="showHelpText" class="lh-text-auto-complete">
        <template slot="field">
            <place-suggestion :field="field"></place-suggestion>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'
    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],
    }
</script>
