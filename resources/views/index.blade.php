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
<body>
    {{-- @if(method_exists($billed_meals_collection, 'links'))
      {!! $billed_meals_collection->links() !!}
    @endif --}}
    <main>
      <section class="options">
        <section class="options__download">
          <button class="options__download__pdf" onclick="Database.downloadPDF()">Download PDF</button>
          <button class="options__download__xml" onclick="Database.downloadXML()">Download XML</button>
          <button class="options__download__csv" onclick="Database.downloadCSV()">Download CSV</button>
        </section>
        <section class="options__filtering">
          {{-- REACT --}}
        </section>
        <section class="options__get-data">
          <label>Отобразить количество позиций на одну страницу</label>
          <input id="input_get-data" type="text" name="page" placeholder="Per page">  
          <button onclick="Database.getMoreData()">Отобразить</button>
        </section>
      </section>
      <table class="main-table">
        <thead class="main-table__thead">
            <tr class="main-table__tr">
                <th class="main-table__th--sortable" type="number" data-sort="flight_id" rowSpan="2"><span class="asc">Номер полёта</span></th>
                <th class="main-table__th--sortable" type="date" data-sort="flight_date" rowSpan="2"><span class="asc">Дата полёта</span></th>
                <th class="main-table__th" rowSpan="2"><span>Класс</span></th>
                <th class="main-table__th" rowSpan="2"><span>Тип номенклатуры</span></th>
                <th class="main-table__th" colSpan="2"><span>Код</span></th>
                <th class="main-table__th" colSpan="2"><span>Количество</span></th>
                <th class="main-table__th" colSpan="2"><span>Цена</span></th>
                <th class="main-table__th--sortable" type="number" data-sort="delta" rowSpan="2"><span class="asc">Дельта</span></th>
            </tr>
            <tr class="main-table__tr">
                <th class="main-table__th--sortable" type="string" data-sort="plan_code"><span class="asc">План</span></th>
                <th class="main-table__th--sortable" type="string" data-sort="fact_code"><span class="asc">Факт</span></th>

                <th class="main-table__th--sortable" type="number" data-sort="plan_qty"><span class="asc">План</span></th>
                <th class="main-table__th--sortable" type="number" data-sort="fact_qty"><span class="asc">Факт</span></th>

                <th class="main-table__th--sortable" type="number" data-sort="plan_price"><span class="asc">План</span></th>
                <th class="main-table__th--sortable" type="number" data-sort="fact_price"><span class="asc">Факт</span></th>
            </tr>
        </thead>
        <tbody class="main-table__tbody">
          {{-- REACT --}}
          {{-- TODO: Front side request api --}}
        {{-- @foreach ($billed_meals_collection as $key => $billed_meal)
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
        @endforeach --}}
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
