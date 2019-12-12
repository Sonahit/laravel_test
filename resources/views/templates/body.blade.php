@php  
    $stylesUrl = collect(scandir(public_path('css')))->map(function($file){
        if(!($file == '.' or $file == '..')){
            return url("/css/{$file}");
        }
        return null;
    });
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    @if(isset($stylesUrl))
        @foreach ($stylesUrl as $styleUrl)
            @if (!is_null($styleUrl))
                <link rel="stylesheet" type="text/css" href="{{ asset($styleUrl) }}">
            @endif 
        @endforeach
    @endif
    
    <title>{{ isset($title) ? $title : 'Booking' }}</title>
</head>
<body>
    @isset($slot)
        {{ $slot }}
    @endisset
</body>
</html>