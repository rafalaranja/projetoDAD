<script setup>
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';

const canvasRef = ref(null);    //graph1
const pieCanvasRef = ref(null); //graph2

onMounted(() => {
    const ctx = canvasRef.value.getContext('2d');
    const pieCtx = pieCanvasRef.value.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                'November', 'December'],
            datasets: [{
                label: '# of Transactions',
                data: [12, 19, 3, 5, 22, 3, 4, 8, 10, 7, 18, 9],
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
});
</script>

<template>
    <div class="row justify-content-center">
        <div class="card m-4 col-12 col-md-6">
            <div class="card-header">
                Number of Transactions per Month
            </div>
            <div class="card-body">
                <canvas ref="canvasRef"></canvas>
            </div>
        </div>
        <div class="card m-4 col-12 col-md-6">
            <div class="card-header">
                Payment Type Distribution
            </div>
            <div class="card-body d-flex justify-content-center">
                <div class="canvas-pie">
                    <canvas ref="pieCanvasRef"></canvas>
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
</style>