@extends('layouts.app')
@section('title', 'Hasil Prediksi KNN')

@section('content')
    <div class="container">
        <h1 class="mb-4">Result House Price</h1>

        <!-- Tampilkan pesan error/success -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tampilkan hasil akurasi jika ada -->
        @if (isset($accuracy))
            <div class="card">
                <h4 class="card-header">Hasil Akurasi Prediksi (k = {{ $k }}):</h4>
                <ul style="margin-top: 1rem">
                    <li><strong>RMSE:</strong> {{ number_format($accuracy['rmse'], 2) }}</li>
                    <li><strong>Akurasi:</strong> {{ number_format($accuracy['accuracy_percentage'], 2) }}%</li>
                    <li><strong>MAPE:</strong> {{ number_format($accuracy['mape'], 2) }}%</li>
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Data Testing dengan Hasil Prediksi
                        <span class="float-right">Total Data: {{ $testings->total() }}</span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>NO</th>
                                        <th>SqFt</th>
                                        <th>Beds</th>
                                        <th>Baths</th>
                                        <th>Year</th>
                                        <th>Lot</th>
                                        <th>Grg</th>
                                        <th>Neighborhood</th>
                                        <th>Actual</th>
                                        <th>Predicted</th>
                                        <th>Difference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testings as $index => $testing)
                                        <tr>
                                            <td>{{ $testings->firstItem() + $index }}</td>
                                            <td>{{ number_format($testing->Square_Footage) }}</td>
                                            <td>{{ $testing->Num_Bedrooms }}</td>
                                            <td>{{ $testing->Num_Bathrooms }}</td>
                                            <td>{{ $testing->Year_Built }}</td>
                                            <td>{{ number_format($testing->Lot_Size, 2) }}</td>
                                            <td>{{ $testing->Garage_Size }}</td>
                                            <td>{{ $testing->Neighborhood_Quality }}/10</td>
                                            <td>${{ number_format($testing->House_Price, 2) }}</td>
                                            <td>${{ number_format($testing->predicted_price, 2) }}</td>
                                            <td
                                                class="{{ $testing->House_Price - $testing->predicted_price > 0 ? 'text-danger' : 'text-success' }}">
                                                ${{ number_format(abs($testing->House_Price - $testing->predicted_price), 2) }}
                                                ({{ number_format(abs(($testing->House_Price - $testing->predicted_price) / $testing->House_Price) * 100, 2) }}%)
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($testings->hasPages())
                            <div class="d-flex justify-content-between mt-3">
                                <div class="showing-results">
                                    Menampilkan {{ $testings->firstItem() }} sampai {{ $testings->lastItem() }} dari
                                    {{ $testings->total() }} data
                                </div>
                                <div>
                                    {{ $testings->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
