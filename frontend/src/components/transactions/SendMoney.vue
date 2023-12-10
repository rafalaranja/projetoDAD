<script setup>
import { ref } from 'vue';
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  vcards: {
    type: Array,
    default: () => [],
  },
});
let quantity = ref('');

const validateInput = () => {
    if (quantity.value < 0) {
        quantity.value = 0;
    }
};

</script>

<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Send Money</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="mt-2">Send to:</h5>
            <select id="inputState" class="form-control">
                <option disabled selected value> Select a VCard </option>
                <option>932313102</option>
                <option>933230965</option>
            </select>
            <select id="inputState" class="form-control" >
                <option disabled selected value > Select a VCard </option>
                <option v-for="vcard in vcards" :key="vcard.phone_number" >{{vcard.phone_number}}</option>
            </select>
            <h5 class="mt-4">Payment Type:</h5>
            <select id="inputState" class="form-control">
                <option disabled selected value> Select a Payment Option </option>
                <option>MBWay</option>
                <option>IBAN</option>
            </select>
            <h5 class="mt-4">Quantity:</h5>
            <form>
                <div class="form-group">
                    <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                        placeholder="Enter Quantity" v-model.number="quantity" @input="validateInput">
                    <small id="emailHelp" class="form-text text-muted">Enter a number (use a dot to separate decimal
                        places)</small>
                </div>
            </form>


            <div class="d-flex justify-content-center mx-5 mt-4">
                <button type="button" class="btn btn-success btn-lg mt-3">SEND</button>
            </div>
        </div>
    </div>
</template>