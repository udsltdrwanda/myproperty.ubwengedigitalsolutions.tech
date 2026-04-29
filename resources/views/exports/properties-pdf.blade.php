<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Properties List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
        }
        td {
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Properties List</h1>

    <table>
        <thead>
            <tr>
                <th>UPI</th>
                {{-- <th>Name</th> --}}
                <th>District</th>
                <th>Sector</th>
                <th>Cell</th>
                <th>Village</th>
                <th>Property Use</th>
                <th>Area (m²)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($properties as $property)
            <tr>
                <td>{{ $property->upi }}</td>
                <td>{{ $property->name }}</td>
                {{-- <td>{{ optional($property->districtRelation)->name }}</td> --}}
                <td>{{ optional($property->sectorRelation)->name }}</td>
                <td>{{ optional($property->cellRelation)->name }}</td>
                <td>{{ optional($property->villageRelation)->name }}</td>
                <td>{{ $property->property_use }}</td>
                <td>{{ $property->area }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ date('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
