<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="w-full" style="position: relative; height: 400px;">
    <canvas id="{{ $chartId }}"></canvas>
</div>

<script>
    const ctx_{{ $chartId }} = document.getElementById('{{ $chartId }}').getContext('2d');

    const chartConfig_{{ $chartId }} = {
        type: '{{ $config["type"] }}',
        data: {
            labels: @json($config["labels"]),
            datasets: @json($config["datasets"]),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: '{{ $title }}'
                }
            },
            @if($config["type"] === "line")
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 0 });
                        }
                    }
                }
            }
            @elseif($config["type"] === "bar")
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
            @endif
        }
    };

    new Chart(ctx_{{ $chartId }}, chartConfig_{{ $chartId }});
</script>
