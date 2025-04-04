@extends('layouts.app')
@section('title', 'Data Training')

@section('content')
    <div class="container">
        <h1 class="mb-4">Data Training House Price</h1>

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
                    <div class="card-header">Import Training Data ({{ $trainings->total() }})</div>
                    <div class="card-body">
                        <form action="{{ route('import.training') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="trainingFile">Excel File</label>
                                <input type="file" class="form-control" id="trainingFile" name="file" required>
                                <small class="form-text text-muted" style="-webkit-text-fill-color: white">
                                    Format: Square_Footage, Num_Bedrooms, Num_Bathrooms, Year_Built, Lot_Size, Garage_Size,
                                    Neighborhood_Quality, House_Price
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Import</button>
                        </form>
                    </div>
                </div>
                <div class="mb-3">
                    <form action="{{ route('training.deleteAll') }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete ALL training data? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete All Training Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Training Data</div>
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
                                    @forelse ($trainings as $index => $training)
                                        <tr>
                                            <td>{{ $trainings->firstItem() + $index }}</td>
                                            <td>{{ number_format($training->Square_Footage) }}</td>
                                            <td>{{ $training->Num_Bedrooms }}</td>
                                            <td>{{ $training->Num_Bathrooms }}</td>
                                            <td>{{ $training->Year_Built }}</td>
                                            <td>{{ number_format($training->Lot_Size, 2) }}</td>
                                            <td>{{ $training->Garage_Size }}</td>
                                            <td>{{ $training->Neighborhood_Quality }}/10</td>
                                            <td>${{ number_format($training->House_Price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No training data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($trainings->count())
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                                <div class="showing-results mb-2 mb-md-0">
                                    Showing {{ $trainings->firstItem() }} to {{ $trainings->lastItem() }} of
                                    {{ $trainings->total() }} results
                                </div>
                                <div class="pagination-round">
                                    {{ $trainings->onEachSide(1)->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
