<section class="options">
    <h3>Configurations</h3>
    @foreach ($configs as $config)
        <div class="options__form">
            <h4>{{ $config['name'] }}</h4>
            <form action="{{ url("/config/{$config['name']}") }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $config['name'] }}">
                <div class="options__inputs">
                    @isset($config['INT_VAL'])
                        <div class="options__inputs_el">
                            <label>Number value</label>
                            <input type="number" name="INT_VAL" value="{{ is_null($config['INT_VAL']) ? $config['INT_VAL'] : ''}}">
                        </div>
                    @endisset
                    @isset($config['BOOL_VAL'])
                        <div class="options__inputs_el">
                            <label>Boolean value</label>
                            <select class="options__inputs__bool-val">
                                <option {{ intval($config['BOOL_VAL']) ? 'selected' : '' }}>True</option>
                                <option {{ !intval($config['BOOL_VAL']) ? 'selected' : '' }}>False</option>
                            </select>
                            <input type="hidden" name="BOOL_VAL" value="{{ $config['BOOL_VAL'] ? 1 : 0 }}">
                        </div>
                    @endisset
                    @isset($config['DATE_VAL'])
                        <div class="options__inputs_el">
                            <label>Date value</label>
                            <input type="date" name="DATE_VAL" value="{{ is_null($config['DATE_VAL']) ? $config['DATE_VAL'] : '' }}">
                        </div>
                    @endisset
                    @isset($config['STRING_VAL'])
                        <div class="options__inputs_el">
                            <label>Text value</label>
                            <input type="text" name="STRING_VAL" value="{{ is_null($config['STRING_VAL']) ? $config['STRING_VAL'] : '' }}">
                        </div>
                    @endisset
                </div>
                <input type="submit" value="Change">
            </form>
        </div>
    @endforeach
    <script>
        document.querySelectorAll('.options__inputs__bool-val').forEach(el => {
            el.addEventListener('change', e => {
                const { target } = e;
                target.parentNode.children.BOOL_VAL.value = target.value === 'True' ? 1 : 0;
            })
        })
    </script>
</section>
