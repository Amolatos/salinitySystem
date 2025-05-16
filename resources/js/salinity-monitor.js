// Initialize the salinity chart
const initSalinityChart = () => {
    const ctx = document.getElementById('salinityChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array(24).fill('').map((_, i) => `${23-i}h ago`),
            datasets: [{
                label: 'EC Value (mS/cm)',
                data: Array(24).fill(null),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
};

// Update the readings display
const updateReadings = (data) => {
    // Update EC Value
    document.querySelector('#ec-value').textContent = data.ec_value;
    document.querySelector('#ec-progress').style.width = `${(data.ec_value / 10) * 100}%`;

    // Update Temperature
    document.querySelector('#temperature').textContent = data.temperature;
    document.querySelector('#temp-progress').style.width = `${(data.temperature / 50) * 100}%`;

    // Update Location
    document.querySelector('#location').textContent = `${data.latitude}, ${data.longitude}`;

    // Update Last Updated
    const lastUpdated = new Date(data.created_at);
    document.querySelector('#last-updated').textContent = lastUpdated.toLocaleTimeString();

    // Update Chart
    if (window.salinityChart) {
        const newData = window.salinityChart.data.datasets[0].data;
        newData.shift();
        newData.push(data.ec_value);
        window.salinityChart.update();
    }
};

// Fetch latest readings
const fetchLatestReadings = async () => {
    try {
        const response = await fetch('/api/latest-reading');
        const data = await response.json();
        updateReadings(data);
    } catch (error) {
        console.error('Error fetching readings:', error);
    }
};

// Initialize everything when the page loads
document.addEventListener('DOMContentLoaded', () => {
    // Initialize the chart
    window.salinityChart = initSalinityChart();

    // Fetch initial data
    fetchLatestReadings();

    // Set up auto-refresh
    setInterval(fetchLatestReadings, 60000); // Update every minute

    // Set up time period buttons
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', async () => {
            const period = button.dataset.period;
            try {
                const response = await fetch(`/api/readings?period=${period}`);
                const data = await response.json();
                // Update chart with new data
                window.salinityChart.data.datasets[0].data = data.readings.map(r => r.ec_value);
                window.salinityChart.data.labels = data.readings.map(r => {
                    const date = new Date(r.created_at);
                    return date.toLocaleTimeString();
                });
                window.salinityChart.update();
            } catch (error) {
                console.error('Error fetching historical data:', error);
            }
        });
    });
}); 