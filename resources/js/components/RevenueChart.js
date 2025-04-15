// resources/js/components/RevenueChart.js
import { Chart } from 'chart.js';

export default {
    props: ['data'],
    mounted() {
        this.renderChart();
    },
    methods: {
        renderChart() {
            const ctx = this.$refs.canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.data.labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: this.data.values,
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
}