<script setup>
import { onMounted, ref, reactive, inject } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import axios from "axios";
import { useUserStore } from "../../stores/users.js";

const socket = inject("socket");
const userStore = useUserStore();

const vcards = ref([]);
const form = reactive({
  id: 1,
  vcard: "",
  value: "",
  vcard_dest: "",
});
const formSend = reactive({
  vcard: "",
  type: 'D',
  payment_type: "MBWay",
  value: "",
  payment_reference: "",
  description:"",
});
const toast = useToast();
const router = useRouter();
const validateInput = () => {
  if (form.value < 0) {
    form.value = 0;
  }
};
const asks = ref([]);

const loadVcards = async () => {
  try {
    const response = await axios.get("vcards");
    vcards.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
};
const submitForm = async () => {
  try {
    console.log(form);
    const ask = form;
    socket.emit("ask", ask);
    toast.success("Pedido feito com sucesso!");
    //router.push("/");
  } catch (error) {
    console.error(error);
    if (error.response && error.response.data && error.response.data.message) {
      toast.error(error.response.data.message);
    }
  }
};
const submitCash = async (vcard1,value) => {
  try {
      formSend.payment_reference=vcard1;
      formSend.value = value;
      console.log(formSend);
      let response = await axios.post("/transactions", formSend);
      const vcard = formSend.vcard;
      formSend.vcard = formSend.payment_reference;
      formSend.payment_reference = vcard;
      formSend.type='C';
      response = await axios.post("/transactions", formSend);
      socket.emit("balanceUpdate", form.vcard);
      toast.success("Dinheiro enviado");
      const idTransaction = response.data.data.id-1;
      router.push(`/transactions/${idTransaction}`);
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

socket.on("askResponse", function (ask) {
  try {
    // Exibir um toast com a informação recebida do WebSocket
    toast.info(`recebeu um pedido de: ${ask.vcard}\nValor: ${ask.value}`);
    asks.value.push(ask);
  } catch (error) {
    console.error("Erro ao processar resposta do WebSocket:", error);
  }
});

onMounted(loadVcards);
form.vcard = userStore.user ? userStore.user.id ?? null : null;
formSend.vcard = userStore.user ? userStore.user.id ?? null : null;


</script>

<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Ask Money</h1>
  </div>
  <div class="card">
    <div class="card-body">
      <h5 class="mt-2">Ask to:</h5>
      <select id="inputState" class="form-control" v-model="form.vcard_dest">
        <option disabled selected value> Select a VCard </option>
        <option v-for="vcard in vcards" :key="vcard.id">{{ vcard.phone_number }}</option>
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


      <div class="d-flex justify-content-center mx-5 mt-4">
        <button type="button" class="btn btn-success btn-lg mt-3" @click="submitForm">ASK</button>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>VCard</th>
            <th>Value</th>
            <th>Enviar</th>
            <!-- Adicione mais colunas conforme necessário -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="ask in asks" :key="ask.id">
            <td>{{ ask.vcard }}</td>
            <td>{{ ask.value }}</td>
            <td><button type="button" class="btn btn-success" @click="() => submitCash(ask.vcard, ask.value)"  >SEND</button></td>
            <!-- Adicione mais colunas conforme necessário -->
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>