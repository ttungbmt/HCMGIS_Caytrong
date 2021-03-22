<template>
    <portal to="modals">
        <transition name="fade">
            <modal @modal-close="hide" v-if="modalOpen">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 900px">
                    <loading-view :loading="loading">
                        <div class="p-4" v-html="html"></div>
                    </loading-view>
                    <slot />
                </div>
            </modal>
        </transition>
    </portal>
</template>

<script>
    export default {
        name: 'NVModal',
        props: ['request'],
        data(){
            return {
                modalOpen: false,
                html: '',
                loading: false
            }
        },
        mounted() {

        },
        methods: {
            show(){
                this.modalOpen = true;

                if(this.request) this.fetchRequest()
            },
            hide(){
                this.modalOpen = false;
            },
            async fetchRequest(){
                this.loading = true

                const {url, method, data} = this.request
                const {data: resp} = await Nova.request().post(url, data)

                this.html = resp.html
                this.loading = false
            }
        },
    }
</script>
