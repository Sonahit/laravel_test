<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <title>S7</title>
</head>
<script>

function downloadCSV(){
  //TODO: sub column csv problem
  const table = document.getElementById('main-table');
  const items = [].reduce.call(table.rows, function(res, row) {
      res[row.cells[0].textContent.slice(0,-1)] = row.cells[1].textContent;
      return res;
    }, {});
  const replacer = (key, value) => value === null ? "NO DATA" : value; // specify how you want to handle null values here
  const header = Object.keys(items[0]);
  let csv = items
    .map(row => header.map( fieldName => JSON.stringify(row[fieldName], replacer)).join(','));
  csv.unshift(header.join(','));
  csv = csv.join('\r\n');
  const downloadLink = document.createElement("a");
  const blob = new Blob(["\ufeff", csv]);
  const url = URL.createObjectURL(blob);
  downloadLink.href = url;
  downloadLink.download = "data.csv";

  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}

</script>
<body>
    @if($billed_meals_collection->links())
      {!! $billed_meals_collection->links() !!}
    @endif
    <main>
      <button onclick="downloadCSV()">clickme</button>
      <table id="main-table">
        <thead>
            <tr>
                <th rowSpan="2">Номер полёта</th>
                <th rowSpan="2">Дата полёта</th>
                <th rowSpan="2">Класс</th>
                <th rowSpan="2">Тип номенклатуры</th>
                <th colSpan="2">Код</th>
                <th colSpan="2">Количество</th>
                <th colSpan="2">Цена</th>
                <th colSpan="2">Дельта</th>
            </tr>
            <tr>
                <th>План</th>
                <th>Факт</th>

                <th>План</th>
                <th>Факт</th>

                <th>План</th>
                <th>Факт</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($billed_meals_collection as $key => $billed_meal)
          @if ($billed_meal)
            <tr>
                <td>  {{ $billed_meal['id']}} </td>
                <td>  {{ $billed_meal['date'] }}</td>
                <td>  {{ $billed_meal['class'] }}</td>
                <td>  {{ $billed_meal['type'] }}</td>
                <td>  {{ if_data(implode(", ",$billed_meal['plan_attributes']['codes']))}}</td>
                <td>  {{ implode(", ",$billed_meal['fact_attributes']['codes'])}}</td>
                <td>  {{ if_data($billed_meal['plan_attributes']['qty'], 0)  }}</td>
                <td>  {{ $billed_meal['fact_attributes']['qty']  }}</td>
                <td>  {{ round(if_data($billed_meal['plan_attributes']['price'], 0), 2) }}</td>
                <td>  {{ round($billed_meal['fact_attributes']['price'], 2) }}</td>
                <td>  {{ round($billed_meal['fact_attributes']['price'] - if_data($billed_meal['plan_attributes']['price'], 0), 2) }}</td>
            </tr>
          @endif
        @endforeach
        @php
          function if_data($data, $no_data = "NO DATA"){
              return $data ? $data : $no_data;
          }   
        @endphp
      </tbody>
    </table>   
    </main>
  <script src="js/app.js"></script>
</body>
</html>
