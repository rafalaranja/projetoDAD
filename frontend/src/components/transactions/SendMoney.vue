<script setup>
import { ref } from 'vue';

let quantity = ref('');

const validateInput = () => {
    quantity.value = quantity.value.replace(/[^0-9.]/g, '');
};
</script>

<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Send Money</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="mt-2">Send to:</h5>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select VCard
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
            <h5 class="mt-4">Payment Type:</h5>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select Payment Type
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    <a class="dropdown-item" href="#">MBWay</a>
                    <a class="dropdown-item" href="#">Paypal</a>
                    <a class="dropdown-item" href="#">Credit Card</a>
                </div>
            </div>

            <h5 class="mt-4">Quantity:</h5>
            <form>
                <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
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