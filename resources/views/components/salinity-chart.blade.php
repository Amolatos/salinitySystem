@props(['measurements', 'chartId'])

<div class="w-full">
    <canvas id="{{ $chartId }}"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($measurements->pluck('measured_at')->map->format('Y-m-d H:i')) !!},
            datasets: [
                {
                    label: 'Salinity (PPT)',
                    data: {!! json_encode($measurements->pluck('salinity_value')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                },
                {
                    label: 'Temperature (°C)',
                    data: {!! json_encode($measurements->pluck('temperature')) !!},
                    borderColor: 'rgb(239, 68, 68)',
                    tension: 0.1
                },
                {
                    label: 'pH Level',
                    data: {!! json_encode($measurements->pluck('ph_level')) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Measurement History'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush 