<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenFoodFacts Price Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>OpenFoodFacts Price Checker</h1>
        
        <form action="/" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="product_code" class="form-label">Product Code</label>
                <input type="text" class="form-control" id="product_code" name="product_code" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif

        @if(isset($prices) && isset($productCode))
            <h2>Prices for Product Code: {{ $productCode }}</h2>
            @if(count($prices) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Store</th>
                            <th>Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prices as $price)
                            <tr>
                                <td>{{ $price['store'] ?? 'Unknown' }}</td>
                                <td>{{ $price['price'] ?? 'N/A' }}</td>
                                <td>{{ $price['date'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No prices found for this product.</p>
            @endif
        @endif
    </div>
</body>
</html>