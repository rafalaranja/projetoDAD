<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const canvasRef = ref(null);    //graph1
const pieCanvasRef = ref(null); //graph2
const lineCanvasRef = ref(null); //graph3
const statistics = ref([]);

const loadStatistics = async () => {
  try {
    const response = await axios.get(`statistics/{user}/load`);
    statistics.value = response.data.data

    console.log(response.data.data)
  } catch (error) {
    console.log(error);
  }
};

onMounted(async () => {
    const ctx = canvasRef.value.getContext('2d');
    const pieCtx = pieCanvasRef.value.getContext('2d');
    const lineCtx = lineCanvasRef.value.getContext('2d');

    loadStatistics();

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                'November', 'December'],
            datasets: [{
                label: '# of Transactions',
                data: statistics.value,
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

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['MB', 'IBAN', 'VCard', 'Visa', 'Paypal', 'MBWAY'],
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c'],
            }]
        },
        options: {
            responsive: true,
        }
    });

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['MB', 'IBAN', 'VCard', 'Visa', 'Paypal', 'MBWAY'],
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c'],
            }]
        },
        options: {
            responsive: true,
        }
    });

    watch(statistics, () => {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                    'November', 'December'],
                datasets: [{
                    label: '# of Transactions',
                    data: statistics.value,
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
    });
});
</script>

<template>
    <div class="graph-container">
      <div class="card m-4">
        <div class="card-header">Number of Transactions per Month</div>
        <div class="card-body">
          <canvas ref="canvasRef"></canvas>
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
        <div class="card-header">Payment Type Distribution</div>
        <div class="card-body d-flex justify-content-center">
          <div class="canvas-pie">
            <canvas ref="lineCanvasRef"></canvas>
          </div>
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
  .card canvas {
    width: 100%;
    height: auto;
  }
</style>
