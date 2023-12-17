<script setup>
import { onMounted, ref, reactive, inject } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import axios from "axios";
import { useUserStore } from "../../stores/users.js";

const userStore = useUserStore();
const socket = inject("socket");
const vcards = ref([]);
const categories = ref([]);
const form = reactive({
  vcard: "",
  type: "D",
  payment_type: "",
  value: "",
  payment_reference: "",
  description: "",
  categorie: "",
});
const toast = useToast();
const router = useRouter();
const validateInput = () => {
  if (form.value < 0) {
    form.value = 0;
  }
};
const props = defineProps({
  transactions: {
    type: Array,
    default: () => [],
  },
});

const loadVcards = async () => {
  try {
    const response = await axios.get("vcards");
    vcards.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
};
const loadCategories = async () => {
  try {
    const response = await axios.get(`categories/send/${userStore.user.id}`);
    console.log(response.data);
    categories.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
};
const emit = defineEmits(['view']);
const viewTransaction = (transaction) => {
  emit("view", transaction);
  console.log(transaction);
};

const submitForm = async () => {
  try {
    if (form.payment_type === "MBWay") {
      let response = await axios.post("/transactions", form);
      const vcard = form.vcard;
      form.vcard = form.payment_reference;
      form.payment_reference = vcard;
      form.type = 'C';
      form.categorie = "";
      response = await axios.post("/transactions", form);
      const idTransaction = response.data.data.id - 1;
      socket.emit("balanceUpdate", form.vcard);
      router.push(`/transactions/${idTransaction}`);
    } else {
      const response = await axios.post("/transactions", form);
      toast.success("Transação feita com sucesso!");
      const idTransaction = response.data.data.id;
      router.push(`/transactions/${idTransaction}`);
    }
    //const idTransaction = response.data.id;
    //router.push(`/transactions/${idTransaction}`);
  } catch (error) {
    console.error(error);
    if (
      error.response &&
      error.response.data &&
      error.response.data.message &&
      error.response.data.message.includes("Integrity constraint violation")
    ) {
      toast.error("Este número de telefone já existe.");
    } else if (error.response && error.response.data && error.response.data.message) {
      toast.error(error.response.data.message);
    } else {
      toast.error("Ocorreu um erro ao criar o vCard!");
    }
  }
};

onMounted(() => {
  loadVcards();
  loadCategories();
});

form.vcard = userStore.user ? userStore.user.id ?? null : null;


</script>

<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Send Money</h1>
  </div>
  <div class="card">
    <div class="card-body">
      <h5 class="mt-4">Payment Type:</h5>
      <select v-model="form.payment_type" id="inputState" class="form-control">
        <option disabled selected value> Select a Payment Option </option>
        <option>MBWay</option>
        <option>IBAN</option>
      </select>

      <h5 class="mt-2" v-show="form.payment_type">Send to:</h5>
      <select id="inputState" class="form-control" v-show="form.payment_type === 'MBWay'"
        v-model="form.payment_reference">
        <option disabled selected value> Select a VCard </option>
        <option v-for="vcard in vcards" :key="vcard.id">{{ vcard.phone_number }}</option>
      </select>
      <input class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter IBAN"
        @input="validateInput" v-model="form.payment_reference" v-show="form.payment_type === 'IBAN'">
      <h5 class="mt-2">Category:</h5>
      <select id="inputState" class="form-control" v-model="form.categorie">
        <option disabled selected value> Select a Category </option>
        <option v-for="categorie in categories" :key="categorie.id" :value="categorie.id">{{ categorie.name }}</option>
      </select>

      <h5 class="mt-4">Quantity:</h5>
      <form>
        <div class="form-group">
          <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
            placeholder="Enter Quantity" @input="validateInput" v-model="form.value">
          <small id="emailHelp" class="form-text text-muted">Enter a number (use a dot to separate decimal
            places)</small>
        </div>
      </form>
      <h5 class="mt-4">Description:</h5>
      <form>
        <div class="form-group">
          <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
            placeholder="Enter Description" @input="validateInput" v-model="form.description">
          <small id="emailHelp" class="form-text text-muted">Enter a description (optional)</small>
        </div>
      </form>
      <div class="d-flex justify-content-center mx-5 mt-4">
        <button type="button" class="btn btn-success btn-lg mt-3" @click="submitForm">SEND</button>
      </div>
    </div>
  </div>
</template>