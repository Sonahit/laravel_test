<section class="error">
    @foreach ($errors->all() as $error)
        <div class="error__container">
            <span class="error__text">{{ $error }}</span>
        </div>
    @endforeach
</section>