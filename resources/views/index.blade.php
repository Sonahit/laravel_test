<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>S7</title>
</head>
<body>
    <table>
      <thead>
        <tr>
          <th>Номер полёта</th>
          <th>Дата полёта</th>
          <th>Тип номенклатуры</th>
          <th>Класс</th>
          <th>Код Факт</th>
          <th>Количество Факт</th>
          <th>Цена Факт</th>
        </tr>
      </thead>
      <tbody> 
        @foreach ($billed_meals as $billed_meal)
          <tr>
            <td>{{ $billed_meal['flight_id']}}</td>
            <td>{{ $billed_meal['flight_date']}}</td>
            <td>{{ $billed_meal['type']}}</td>
            <td>{{ $billed_meal['class']}}</td> 
            <td>{{ $billed_meal['code.fact']}}</td> 
            <td>{{ $billed_meal['qty.fact']}}</td> 
            <td>{{ $billed_meal['price.fact']}}</td>
          </tr> 
        @endforeach
      </tbody>
    </table>
</body>
</html>