<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/users.js";
import Chart from 'chart.js/auto';
import { createApp } from 'vue'


const router = useRouter();
const userStore = useUserStore();
const vcards = ref(null);
const lineCanvasRef = ref(null);
const chartData = ref([]);

const loadVCards = async () => {
  try {
    const response = await axios.get("vcard/" + userStore.user.id + "/load");
    vcards.value = response.data.data;
    const transactions = response.data.data;

  } catch (error) {
    console.log(error);
  }
};

const loadTransactions = async () => {
  try {
    const response = await axios.get("/transactions");
    const transactions = response.data.data;

    // Initialize an array to hold the count of transactions for each month
    const monthlyTransactionCounts = Array(12).fill(0);

    // Iterate over the transactions
    for (const transaction of transactions) {
      // Parse the date of the transaction
      const date = new Date(transaction.date);

      // Increment the count for the month of the transaction
      monthlyTransactionCounts[date.getMonth()]++;
    }

    // Update the chart data
    chartData.value = monthlyTransactionCounts;
  } catch (error) {
    console.log(error);
  }
};

// Define the function to create the chart
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

// Run the loadVCards function and create the chart when the component is mounted
onMounted(async () => {
  await loadTransactions();
  loadVCards();
  createChart();
});
</script>

<template>
  <!-- Content Row -->
  <div class="row" v-if="vcards">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Balance</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ vcards.balance }}€</div>
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
                Pending Requests</div>
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
