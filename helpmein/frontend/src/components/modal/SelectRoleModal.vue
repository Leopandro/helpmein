<template>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Выберите роль</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <button type="button" v-on:click="selectRole(role)" class="btn btn-secondary" v-for="role of data">{{role.title}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Modal} from "bootstrap";
export default {
    name: 'SelectRoleModal',
    data() {
        return {
            data: [],
            modal: ''
        }
    },
    props: {
        parent: ''
    },
    methods: {
        async selectRole(role) {
            this.modal.hide();
            this.parent.loginAs(role);
        },
        openModal(data) {
            this.data = data;
            this.modal.show()
        }
    },
    mounted() {
        let modal = new Modal(document.getElementById("exampleModal"))
        this.modal = modal;
    }
};
</script>
<style lang="css" scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.7);
}
</style>
