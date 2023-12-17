<script setup>
import { ref, onMounted, inject } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { useUserStore } from "../../stores/users.js";
import Chart from 'chart.js/auto';
import { createApp } from 'vue'
import { toastInjectionKey } from "vue-toastification";


const router = useRouter();
const userStore = useUserStore();

const toast = useToast();
const socket = inject("socket");
const vcards = ref(null);
const lineCanvasRef = ref(null);
const chartData = ref([]);
const balanceVcard = ref([]);
const isToastDisplayed = ref(false);
const loadVCards = async () => {
  try {
    const response = await axios.get("vcard/" + userStore.user.id + "/load");
    vcards.value = response.data.data;
    const transactions = response.data.data;
    balanceVcard.value = vcards.value.balance;
  } catch (error) {
    console.error("", error);
    throw error;
  }
};

const loadTransactions = async () => {
  try {
    const response = await axios.get("/transactions");
    const transactions = response.data.data;


    const monthlyTransactionCounts = Array(12).fill(0);


    for (const transaction of transactions) {

      const date = new Date(transaction.date);


      monthlyTransactionCounts[date.getMonth()]++;
    }


    chartData.value = monthlyTransactionCounts;
  } catch (error) {
    console.log(error);
  }
};


const createChart = () => {
  const barCtx = lineCanvasRef.value.getContext('2d');

  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
        label: "Transactions",
        backgroundColor: "#503596",
        hoverBackgroundColor: "#503264",
        borderColor: "black",
        borderWidth: 2,
        data: chartData.value,
      }],
    },
  });
};



onMounted(async () => {
  await loadTransactions();
  createChart();
  await loadVCards();
  socket.on("balance", async () => {
    if (!isToastDisplayed) {
      isToastDisplayed = true;
    }
    try {
      toast.info(`Atualização no saldo!!`);
      console.log("hey");
      await loadVCards();
    } catch (error) {
      console.error("Erro ao processar resposta do WebSocket:", error);
      console.log("Erro no bloco catch:", error);
    } finally {
      isToastDisplayed = false;
    }
  });
});
</script>

<template>
  <div class="row" v-if="vcards">

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Balance</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ balanceVcard }}€</div>
            </div>
            <div class="col-auto">
              <i class="bi bi-wallet2 text-gray"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Savings</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">320€</div>
            </div>
            <div class="col-auto">
              <i class="bi bi-piggy-bank"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Max Debit
              </div>
              <div class="row no-gutters align-items-center">
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ vcards.max_debit }}€</div>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-coin"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                PENDING REQUESTS</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
            </div>
            <div class="col-auto">
              <i class="bi bi-envelope-exclamation"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-if="userStore.user.type != 'A'">
    <div class=" row">
      <div class="col-lg-12 mb-1">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-dark">Number of Transactions per Month (current year)</h6>
          </div>
          <div class="card-body">
            <canvas ref="lineCanvasRef" width="100" height="24"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
