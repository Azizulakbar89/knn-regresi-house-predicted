@extends('layouts.app')
@section('title', 'Data Testing')

@section('content')

    <div class="container">
        <h1 class="mb-4">Data Testing House Price</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Import Testing Data ({{ $testings->total() }})</div>
                    <div class="card-body">
                        <form action="{{ route('import.testing') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="testingFile">Excel File</label>
                                <input type="file" class="form-control" id="testingFile" name="file" required>
                                <small class="form-text text-muted" style="-webkit-text-fill-color: white">
                                    Format: Square_Footage, Num_Bedrooms, Num_Bathrooms, Year_Built, Lot_Size, Garage_Size,
                                    Neighborhood_Quality, House_Price (optional)
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Import</button>
                        </form>
                    </div>
                </div>
                <div class="mb-3">
                    <form action="{{ route('testing.deleteAll') }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete ALL testing data? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete All Testing Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Run Prediction</div>
                    <div class="card-body">
                        <form action="{{ route('predict') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="kValue" class="col-sm-2 col-form-label">K Value</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="kValue" name="k" value="5"
                                        min="1" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Predict</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Testing Data with Predictions</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>SqFt</th>
                                        <th>Beds</th>
                                        <th>Baths</th>
                                        <th>Year</th>
                                        <th>Lot</th>
                                        <th>Garage</th>
                                        <th>Neighborhood</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testings as $index =>$testing)
                                        <tr>
                                            <td>{{ $testings->firstItem() + $index }}</td>
                                            <td>{{ number_format($testing->Square_Footage) }}</td>
                                            <td>{{ $testing->Num_Bedrooms }}</td>
                                            <td>{{ $testing->Num_Bathrooms }}</td>
                                            <td>{{ $testing->Year_Built }}</td>
                                            <td>{{ number_format($testing->Lot_Size, 2) }}</td>
                                            <td>{{ $testing->Garage_Size }}</td>
                                            <td>{{ $testing->Neighborhood_Quality }}/10</td>
                                            <td>
                                                @if ($testing->House_Price)
                                                    ${{ number_format($testing->House_Price, 2) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No testing data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($testings->count())
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                                <div class="showing-results mb-2 mb-md-0">
                                    Showing {{ $testings->firstItem() }} to {{ $testings->lastItem() }} of
                                    {{ $testings->total() }} results
                                </div>
                                <div class="pagination-round">
                                    {{ $testings->onEachSide(1)->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
