<!DOCTYPE html>
<html>
<head>
    <title>Seller Products</title>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        table, th, td{
            border:1px solid #000;
        }

        th, td{
            padding:6px;
            text-align:left;
        }

        th{
            background:#f2f2f2;
        }
    </style>
</head>

<body>

<h2>Seller Products List</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
<th>Date</th>
</tr>

@foreach($products as $product)

<tr>

<td>{{ $product->id }}</td>

<td>{{ $product->getTranslation('name') }}</td>

<td>{{ $product->unit_price }}</td>

<td>{{ optional($product->stocks->first())->qty }}</td>

<td>{{ $product->created_at }}</td>

</tr>

@endforeach

</table>

</body>
</html>