<template>
    <div>
        <div v-if="!loading">Loading...</div>
        <div v-else>
            <div v-if="isString(content)" v-html="content"></div>
            <div v-else>

            </div>
          <!--  <div class="pop-actions flex" v-if="html.popup">
                <div class="action-item" @click="openModal">
                    <a href="#" class="px-1">Chi tiết</a>
                </div>
                <div class="action-item">
                    <a href="#" class="px-1">Liên kết</a>
                </div>
            </div>-->

            <slot />
        </div>
    </div>
</template>

<script>
    import {isString} from 'lodash-es'

    export default {
        name: 'LPopupContent',
        props: {
            loading: {
                type: Boolean,
                default: false
            },
            content: {
                type: [String, Object],
                default: ''
            },
            data: {
                type: Object,
            },
            actions: {
                type: Array,
                default: []
            }
        },
        methods: {
            isString
        }
    }
</script>