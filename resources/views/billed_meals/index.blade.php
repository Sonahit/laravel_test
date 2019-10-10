<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  @foreach ($billed_meals as $billed_meal)
    @foreach ($billed_meal as $k => $v)
      @if ($v)
        {{$k}} :: {{$v}}
        <br>
      @endif
    @endforeach
  @endforeach
  
</body>
</html>