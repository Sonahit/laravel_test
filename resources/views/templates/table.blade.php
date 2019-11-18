<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Table</title>
</head>
<body>
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
    <tbody>
      @foreach ($table_data as $key => $billed_meal)
          <tr>
            <td>  {{ $billed_meal->id }} </td>	            
            <td>  {{ $billed_meal->date }}</td>	            
            <td>  {{ $billed_meal->class }}</td>	          
            <td>  {{ $billed_meal->type }}</td>	           
            <td>  {{ implode(", ",$billed_meal->plan_attributes->codes)}}</td>	            
            <td>  {{ implode(", ",$billed_meal->fact_attributes->codes)}}</td>
            <td>  {{ $billed_meal->plan_attributes->qty }}</td>	            
            <td>  {{ $billed_meal->fact_attributes->qty }}</td>
            <td>  {{ $billed_meal->plan_attributes->price}}</td>
            <td>  {{ $billed_meal->fact_attributes->price }}</td>	   
            <td>  {{ $billed_meal->plan_attributes->price - $billed_meal->fact_attributes->price }}</td>	          
          </tr>
        @endforeach
    </tbody>
  </table>
</body>
</html>