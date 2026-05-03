@if ($errors->any())
    <div class="cadastro-validation-summary mb-4" role="alert">
        <strong class="d-block mb-2">Verifique os dados abaixo:</strong>
        <ul class="mb-0 ps-3 small">
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif
