<section class="profile__stakeHolders">
    <section class="profile__content">
        <h3>Stakeholders</h3>
        {{-- <div class="profile__stakeHolders hidden">
            <span> No stakeholders </span>
        </div> --}}
        @isset($stakeHolders)
            @foreach ($stakeHolders as $stakeHolder)
                @php
                    $user = $stakeHolder->user;
                @endphp
                <div class="profile__stakeHolders_info">
                    <input type="email" value={{ $user->email }}>
                    <input type="hidden" value={{ $user->email }}>
                    <button class="profile__stakeHolders__delete button" type="button">Delete</button>
                    <button class="profile__stakeHolders__update button" type="button">Update</button>
                </div>
            @endforeach
        @endisset
        <template id="profile__stakeHolders_info">
            <div class="profile__stakeHolders_info">
                <input type="email" placeholder="Email...">
                <button class="profile__stakeHolders__delete button" type="button">Delete</button>
                <button class="profile__stakeHolders__update button" type="button">Update</button>
            </div>
        </template>
        @if (count($stakeHolders) < 1)
            <div class="profile__stakeHolders_info">
                <input type="email" placeholder="Email...">
                <button class="profile__stakeHolders__delete button" type="button">Delete</button>
                <button class="profile__stakeHolders__update button" type="button">Update</button>
            </div>
        @endif
    </section>
    <button class="profile__stakeHolders__add button" type="button">Add</button>
    <script>
        const container = document.querySelector('.profile__stakeHolders');
        if('content' in document.createElement('template')){
            container.querySelector('button.profile__stakeHolders__add').addEventListener('click', (e) => {
                const template = document.querySelector('#profile__stakeHolders_info');
                const element = template.content.cloneNode(true);
                appendListeners(element);
                container.querySelector('.profile__content').appendChild(element);
            })
        }
        function appendListeners (element) {
            element.querySelector('.profile__stakeHolders__delete').addEventListener('click', (e) => {
                deleteEl(e);
            });
            element.querySelector('.profile__stakeHolders__update').addEventListener('click', (e) => {
                updateEl(e)
            });
        }
        container.querySelectorAll('.profile__stakeHolders__delete').forEach(e => e.addEventListener('click', (e) => {
            deleteEl(e);
        }));
        container.querySelectorAll('.profile__stakeHolders__update').forEach(e => e.addEventListener('click', (e) => {
            updateEl(e);
        }));
        function updateEl(e){
            const els = e.target.parentNode.querySelectorAll('input');
            const formData = new FormData();
            const values = Array.from(els).forEach((el) =>{
                formData.append(el.type, el.value);
            });
            fetch('{{ url('/stakeHolders') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                },
                body: formData
            }).then((resp) => {
                if(resp.ok) return resp.text();
                throw new Error(resp.statusText);
            });
        }
        function deleteEl(e){
            const els = e.target.parentNode.querySelectorAll('input');
            const formData = new FormData();
            const values = Array.from(els).forEach((el) =>{
                formData.append(el.type, el.value);
            });
            formData.append('_method', 'DELETE');
            fetch('{{ url('/stakeHolders') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                },
                body: formData
            }).then((resp) => {
                if(resp.ok) {
                    return e.target.parentNode.remove();
                };
                throw new Error(resp.statusText);
            });
        }
    </script>
</section>
