@if(session()->has('success'))
    <section class="success">
        <div class="success__container">
            <span class="success__text">{{ session()->get('success') }}</span>
        </div>
    </section>
@endif