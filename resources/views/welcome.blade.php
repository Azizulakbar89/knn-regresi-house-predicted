@extends('layouts.app')
@section('title', 'Dashboard')
<style>
    .apexcharts-toolbar svg {
        fill: white !important;
    }

    .apexcharts-menu,
    .apexcharts-menu-item,
    .apexcharts-toolbar {
        background: transparent !important;
        /* Biar transparan */
        color: #ffffff !important;
    }

    .apexcharts-menu-item:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        /* Efek hover */
    }
</style>
@section('content')
    <div class="container">
        <div class="container-fluid">
            <!--Start Dashboard Content-->
            <div class="card mt-3">
                <div class="card-content">
                    <div class="row row-group m-0">
                        <div class="col-12 col-lg-6 col-xl-3 border-light">
                            <div class="card-body">
                                @php
                                    $highestPrice = App\Models\HouseTesting::max('predicted_price');
                                @endphp

                                <h5 class="text-white mb-0">
                                    @if ($highestPrice)
                                        ${{ number_format($highestPrice) }}
                                    @else
                                        N/A
                                    @endif
                                    <span class="float-right"><i class="fa fa-usd"></i></span>
                                </h5>

                                <div class="progress my-3" style="height:3px;">
                                    <div class="progress-bar" style="width:100%"></div>
                                </div>

                                <p class="mb-0 text-white small-font">
                                    Highest Actual Price
                                    <span class="float-right">
                                        <i class="zmdi zmdi-trending-up"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-3 border-light">
                            <div class="card-body">
                                @php
                                    $lowestPrice = App\Models\HouseTesting::min('predicted_price');
                                @endphp

                                <h5 class="text-white mb-0">
                                    @if ($lowestPrice)
                                        ${{ number_format($lowestPrice) }}
                                    @else
                                        N/A
                                    @endif
                                    <span class="float-right"><i class="fa fa-usd"></i></span>
                                </h5>

                                <div class="progress my-3" style="height:3px;">
                                    <div class="progress-bar" style="width:100%"></div>
                                </div>

                                <p class="mb-0 text-white small-font">
                                    Lowest Actual Price
                                    <span class="float-right">
                                        <i class="zmdi zmdi-trending-down"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-3 border-light">
                            <div class="card-body">
                                @php
                                    // Get the record with maximum price difference
                                    $maxDifferenceRecord = App\Models\HouseTesting::selectRaw(
                                        '*, ABS(House_Price - predicted_price) as price_difference',
                                    )
                                        ->whereNotNull('House_Price')
                                        ->whereNotNull('predicted_price')
                                        ->orderByDesc('price_difference')
                                        ->first();

                                    $difference = $maxDifferenceRecord
                                        ? $maxDifferenceRecord->House_Price - $maxDifferenceRecord->predicted_price
                                        : 0;
                                    $percentage =
                                        $maxDifferenceRecord && $maxDifferenceRecord->House_Price != 0
                                            ? ($difference / $maxDifferenceRecord->House_Price) * 100
                                            : 0;
                                @endphp

                                <h5 class="text-white mb-0">
                                    @if ($maxDifferenceRecord)
                                        ${{ number_format(abs($difference)) }}
                                        <small class="d-block mt-1" style="font-size: 14px;">
                                            ({{ number_format(abs($percentage), 2) }}%)
                                        </small>
                                    @else
                                        N/A
                                    @endif
                                    <span class="float-right"></span>
                                </h5>

                                <div class="progress my-3" style="height:3px;">
                                    <div class="progress-bar @if ($maxDifferenceRecord) {{ $difference > 0 ? 'bg-danger' : 'bg-success' }} @endif"
                                        style="width: {{ $maxDifferenceRecord ? min(abs($percentage), 100) : 100 }}%">
                                    </div>
                                </div>

                                <p class="mb-0 text-white small-font">
                                    Largest Price Difference
                                    <span class="float-right">
                                        @if ($maxDifferenceRecord)
                                            <i
                                                class="zmdi zmdi-{{ $difference > 0 ? 'alert-triangle' : 'check-circle' }}"></i>
                                        @endif
                                    </span>
                                </p>

                                @if ($maxDifferenceRecord)
                                    <div class="mt-2 text-white small-font">
                                        <div>Actual: ${{ number_format($maxDifferenceRecord->House_Price) }}</div>
                                        <div>Predicted: ${{ number_format($maxDifferenceRecord->predicted_price) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 col-xl-3 border-light">
                            <div class="card-body">
                                @php
                                    // Get the record with maximum price difference
                                    $minDifferenceRecord = App\Models\HouseTesting::selectRaw(
                                        '*, ABS(House_Price - predicted_price) as price_difference',
                                    )
                                        ->whereNotNull('House_Price')
                                        ->whereNotNull('predicted_price')
                                        ->orderBy('price_difference')
                                        ->first();

                                    $difference = $minDifferenceRecord
                                        ? $minDifferenceRecord->House_Price - $minDifferenceRecord->predicted_price
                                        : 0;
                                    $percentage =
                                        $minDifferenceRecord && $minDifferenceRecord->House_Price != 0
                                            ? ($difference / $minDifferenceRecord->House_Price) * 100
                                            : 0;
                                @endphp

                                <h5 class="text-white mb-0">
                                    @if ($minDifferenceRecord)
                                        ${{ number_format(abs($difference), 2) }}
                                        <small class="d-block mt-1" style="font-size: 14px;">
                                            ({{ number_format(abs($percentage), 2) }}%)
                                        </small>
                                    @else
                                        N/A
                                    @endif
                                    <span class="float-right"></span>
                                </h5>

                                <div class="progress my-3" style="height:3px;">
                                    <div class="progress-bar @if ($minDifferenceRecord) {{ $difference > 0 ? 'bg-danger' : 'bg-success' }} @endif"
                                        style="width: {{ $minDifferenceRecord ? min(abs($percentage), 100) : 100 }}%">
                                    </div>
                                </div>

                                <p class="mb-0 text-white small-font">
                                    Smallest Price Difference
                                    <span class="float-right">
                                        @if ($minDifferenceRecord)
                                            <i
                                                class="zmdi zmdi-{{ $difference > 0 ? 'plus-circle' : 'minus-circle' }}"></i>
                                        @endif
                                    </span>
                                </p>

                                @if ($minDifferenceRecord)
                                    <div class="mt-2 text-white small-font">
                                        <div>Actual: ${{ number_format($minDifferenceRecord->House_Price) }}</div>
                                        <div>Predicted: ${{ number_format($minDifferenceRecord->predicted_price) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">Predicted Price Range by Year
                        </div>
                        <div class="card-body">
                            <ul class="list-inline">
                                <li class="list-inline-item"><i class="fa fa-circle mr-2" style="color: #14abef"></i>Highest
                                    Predicted</li>
                                <li class="list-inline-item"><i class="fa fa-circle mr-2" style="color: #ff4757"></i>Lowest
                                    Predicted</li>
                            </ul>
                            <div class="chart-container-1" style="position: relative; height:400px; width:100%">
                                <div id="priceRangeChart"></div>
                                <div id="chartError" class="alert alert-danger d-none"></div>
                            </div>
                        </div>

                        <div class="row m-0 row-group text-center border-top border-light-3">
                            <div class="col-12 col-lg-4">
                                <div class="p-3">
                                    <h5 class="mb-0">${{ number_format($priceStats['avgHigh'], 2) }}</h5>
                                    <small class="mb-0">Avg Highest Price</small>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="p-3">
                                    <h5 class="mb-0">${{ number_format($priceStats['avgLow'], 2) }}</h5>
                                    <small class="mb-0">Avg Lowest Price</small>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="p-3">
                                    <h5 class="mb-0">{{ $priceStats['years'] }}</h5>
                                    <small class="mb-0">Years Tracked</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--End Row-->

            <!--End Dashboard Content-->

            <!--start overlay-->
            <div class="overlay toggle-menu"></div>
            <!--end overlay-->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.getElementById('priceRangeChart');
            const errorDiv = document.getElementById('chartError');

            // Initialize chart with empty data
            const chart = new ApexCharts(chartElement, {
                chart: {
                    type: 'line',
                    height: 350,
                    animations: {
                        enabled: false
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                series: [{
                        name: 'Highest Predicted',
                        data: []
                    },
                    {
                        name: 'Lowest Predicted',
                        data: []
                    }
                ],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#14abef', '#ff4757'],
                xaxis: {
                    type: 'category',
                    categories: [],
                    labels: {
                        style: {
                            colors: '#ffffff', // Warna putih
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        style: {
                            colors: '#ffffff', // Warna putih
                            fontSize: '12px'
                        }
                    }
                },
                tooltip: {
                    theme: "dark",
                    y: {
                        formatter: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        colors: '#ffffff', // Warna teks legend jadi putih
                        useSeriesColors: false // Pastikan tidak menggunakan warna default dari chart
                    }
                }

            });

            chart.render();

            // Load data function
            async function loadChartData() {
                try {
                    const response = await fetch('{{ route('showPriceAnalysis') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Network response failed');

                    const data = await response.json();

                    if (!data.success) throw new Error(data.message || 'Invalid data');
                    if (!data.years.length) throw new Error('No data available');

                    // Update chart with all years data
                    chart.updateOptions({
                        xaxis: {
                            categories: data.years
                        }
                    });

                    chart.updateSeries([{
                            data: data.highestPrices
                        },
                        {
                            data: data.lowestPrices
                        }
                    ]);

                    errorDiv.classList.add('d-none');
                } catch (error) {
                    console.error('Error:', error);
                    errorDiv.textContent = 'Error: ' + error.message;
                    errorDiv.classList.remove('d-none');
                }
            }

            // Initial load
            loadChartData();
        });
    </script>
@endsection
