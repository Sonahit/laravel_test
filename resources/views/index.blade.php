<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/index.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/nav.css') }}">
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
    @if(method_exists($billed_meals_collection, 'links'))
      {!! $billed_meals_collection->links() !!}
    @endif
    <main>
      <section id="options">
        <section class="options_download">
          <button class="options__download__pdf" onclick="downloadPDF()">Download PDF</button>
          <button class="options__download__xml"onclick="downloadXML()">Download XML</button>
          <button class="options__download__csv"onclick="downloadCSV()">Download CSV</button>
        </section>
        <section class="options_getData">
          <label>Отобразить количество позиций на одну страницу</label>
          <input id="input_getData" type="text" name="page" placeholder="Per page">  
          <button onclick="Database.getMoreData()">Отобразить</button>
        </section>
      </section>
      <table class="main-table">
        <thead class="main-table__thead">
            <tr class="main-table__tr">
                <th class="main-table__th--sortable" data-sort="flight_id" rowSpan="2"><span class="asc">Номер полёта</span></th>
                <th class="main-table__th--sortable" data-sort="flight_date" rowSpan="2"><span class="asc">Дата полёта</span></th>
                <th class="main-table__th" rowSpan="2"><span>Класс</span></th>
                <th class="main-table__th" rowSpan="2"><span>Тип номенклатуры</span></th>
                <th class="main-table__th" colSpan="2"><span>Код</span></th>
                <th class="main-table__th" colSpan="2"><span>Количество</span></th>
                <th class="main-table__th" colSpan="2"><span>Цена</span></th>
                <th class="main-table__th--sortable" data-sort="delta" rowSpan="2"><span class="asc">Дельта</span></th>
            </tr>
            <tr class="main-table__tr">
                <th class="main-table__th--sortable" data-sort="plan_code"><span class="asc">План</span></th>
                <th class="main-table__th--sortable" data-sort="fact_code"><span class="asc">Факт</span></th>

                <th class="main-table__th--sortable" data-sort="plan_qty"><span class="asc">План</span></th>
                <th class="main-table__th--sortable" data-sort="fact_qty"><span class="asc">Факт</span></th>

                <th class="main-table__th--sortable" data-sort="plan_price"><span class="asc">План</span></th>
                <th class="main-table__th--sortable" data-sort="fact_price"><span class="asc">Факт</span></th>
            </tr>
        </thead>
        <tbody class="main-table__tbody">
        @foreach ($billed_meals_collection as $key => $billed_meal)
          @if ($billed_meal)
            <tr class="main-table__tr">
                <td class="main-table__td">  {{ $billed_meal['id']}} </td>
                <td class="main-table__td">  {{ $billed_meal['date'] }}</td>
                <td class="main-table__td">  {{ $billed_meal['class'] }}</td>
                <td class="main-table__td">  {{ $billed_meal['type'] }}</td>
                <td class="main-table__td">  {{ if_data(implode(", ",$billed_meal['plan_attributes']['codes']))}}</td>
                <td class="main-table__td">  {{ implode(", ",$billed_meal['fact_attributes']['codes'])}}</td>
                <td class="main-table__td">  {{ if_data($billed_meal['plan_attributes']['qty'], 0)  }}</td>
                <td class="main-table__td">  {{ $billed_meal['fact_attributes']['qty']  }}</td>
                <td class="main-table__td">  {{ round(if_data($billed_meal['plan_attributes']['price'], 0), 2) }}</td>
                <td class="main-table__td">  {{ round($billed_meal['fact_attributes']['price'], 2) }}</td>
                <td class="main-table__td">  {{ round($billed_meal['fact_attributes']['price'] - if_data($billed_meal['plan_attributes']['price'], 0), 2) }}</td>
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
