@if(isset($models) && count($models) > 0)
    @php
        $headers = array_keys($models[0]);
    @endphp
    
    <section class="grid-wrapper">
        <div class='grid_title'>{{ $title }}</div>
        <section style="display: grid; grid-template-columns: repeat({{ count($headers) }}, 1fr)" class="grid__content"> 
            @foreach ($headers as $header)
                <div class="grid__rows">
                    <div class="grid__header">
                        {{ $header }}
                    </div>
                    <div>
                        @foreach ($models as $model)
                            <div class="grid__row">
                                {{ $model[$header] }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    </section>
@endif