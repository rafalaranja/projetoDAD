<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const canvasRef = ref(null);    //graph1
const pieCanvasRef = ref(null); //graph2
const bubbleCanvasRef = ref(null); //graph3
const linearCanvasRef = ref(null); //graph4
const chartData = ref([]);
const totalTransactions = ref(0);

const loadStatistics = async () => {
  try {
    const response = await axios.get("/transactions");
    const statistics = response.data.data
    totalTransactions.value = response.data.data.length;

    const monthlyTransactionCounts = Array(12).fill(0);

    for (const transaction of statistics) {
      const date = new Date(transaction.date);
      monthlyTransactionCounts[date.getMonth()]++;
    }
    chartData.value = monthlyTransactionCounts;
  } catch (error) {
    console.log(error);
  }
};

const createChart = () => {
  const ctx = canvasRef.value.getContext('2d');
  const pieCtx = pieCanvasRef.value.getContext('2d');
  const bubbleCtx = bubbleCanvasRef.value.getContext('2d');
  const linearCtx = linearCanvasRef.value.getContext('2d');

  loadStatistics();

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
        'November', 'December'],
      datasets: [{
        label: '# of Transactions',
        data: chartData.value,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  new Chart(linearCtx, {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
        'November', 'December'],
      datasets: [{
        data: [65, 59, 80, 81, 56, 55, 40, 30, 20, 10, 5, 1],
        backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c'],
      }]
    },
    options: {
      responsive: true,
    }
  });

  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['MB', 'IBAN', 'VCard', 'Visa', 'Paypal', 'MBWAY'],
      datasets: [{
        data: chartData.value,
        backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c'],
      }]
    },
    options: {
      responsive: true,
    }
  });

  new Chart(bubbleCtx, {
    type: 'bubble',
    data: {
      labels: ['0-2000', '2000-5000', '5000-10000', '10000-50000', '50000-100000', '100000+'],
      datasets: [{
        data: [
          { x: 10, y: 20, r: 5 },
          { x: 15, y: 10, r: 10 },
          { x: 20, y: 30, r: 15 },
        ],
        backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c'],
      }]
    },
    options: {
      responsive: true,
    }
  });

};

onMounted(async () => {
  await loadStatistics();
  createChart();
});
</script>

<template>
  <h2 class="mt-2">TOTAL TRANSACTIONS: {{ totalTransactions }}</h2>
  <div class="graph-container">
    <div class="card m-4">
      <div class="card-header">Average Transaction Amount</div>
      <div class="card-body">
        <canvas ref="linearCanvasRef"></canvas>
      </div>
    </div>
    <div class="card m-4">
      <div class="card-header">Payment Type Distribution</div>
      <div class="card-body d-flex justify-content-center">
        <div class="canvas-pie">
          <canvas ref="pieCanvasRef"></canvas>
        </div>
      </div>
    </div>
    <div class="card m-4">
      <div class="card-header">VCard Balance Distribution</div>
      <div class="card-body d-flex justify-content-center">
        <div class="canvas-pie">
          <canvas ref="bubbleCanvasRef"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="card m-4">
    <div class="card-header">Number of Transactions per Month</div>
    <div class="card-body d-flex justify-content-center">
      <div class="canvas">
        <canvas ref="canvasRef"></canvas>
      </div>
    </div>
  </div>
</template>
  

<style scoped>
canvas {
  width: 100%;
  height: 100%;
}

.canvas-pie {
  width: 100%;
  max-width: 300px;
  height: auto;
}

.card-header {
  font-size: 1.15rem;
}

.graph-container {
  display: flex;
  flex-wrap: nowrap;
}

.card {
  flex: 1;
  margin-right: 10px;
}

.card:first-child canvas {
  height: 300px;
}

.card:last-child {
  margin-right: 0;
}

/* Updated style for the last card's canvas */
.card:last-child canvas {
  height: 400px;
  /* Set the desired height */
}
</style>

