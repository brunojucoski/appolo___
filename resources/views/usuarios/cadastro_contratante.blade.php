<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro solicitante — MeuPortfólio</title>
    <link href="{{ asset('css/cadastro.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
</head>
<body>

@include('Components.navbarbootstrap')

<main class="cadastro-container">
    <div class="left-illustration">
        <img src="{{ asset('imgs/sobre.png') }}" alt="Ilustração">
    </div>

    <div class="form-section" id="formulario-login">
        <h1 class="form-title">Cadastrar-se como solicitante</h1>

        @include('Components.form-validation-summary')

        <form id="form-cadastro" action="{{ route('usuarios.storeContratante') }}" method="POST" data-address-form>
            @csrf

            <label class="form-label small mb-1">Nome</label>
            <input type="text" name="nome" placeholder="Nome completo" required
                   class="form-control @error('nome') is-invalid @enderror"
                   value="{{ old('nome') }}">

            <div class="gender-options mt-2">
                <label><input type="radio" name="sexo_usuario" value="1" @checked(old('sexo_usuario') == '1') required> Masculino</label>
                <label><input type="radio" name="sexo_usuario" value="2" @checked(old('sexo_usuario') == '2')> Feminino</label>
                <label><input type="radio" name="sexo_usuario" value="3" @checked(old('sexo_usuario') == '3')> Não informar</label>
            </div>

            <label class="form-label small mb-1">E-mail</label>
            <input type="email" name="email" placeholder="E-mail" required
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">

            <label class="form-label small mb-1">Telefone</label>
            <input type="text" id="telefone" name="telefone" placeholder="Telefone" maxlength="15" inputmode="numeric"
                   class="form-control @error('telefone') is-invalid @enderror"
                   value="{{ old('telefone') }}">

            <label class="form-label small mb-1">CPF ou CNPJ</label>
            <input type="text" id="documento" name="documento" placeholder="CPF/CNPJ" maxlength="18"
                   class="form-control @error('documento') is-invalid @enderror"
                   value="{{ old('documento') }}">

            <label class="form-label small mb-1">Data de nascimento</label>
            <input type="date" name="data_nasc" required
                   class="form-control @error('data_nasc') is-invalid @enderror"
                   value="{{ old('data_nasc') }}">

            <label class="form-label small mb-1">CEP</label>
            <input type="text" name="cep" placeholder="00000-000" maxlength="9" inputmode="numeric"
                   class="form-control @error('cep') is-invalid @enderror"
                   value="{{ old('cep') }}">

            <label class="form-label small mb-1">Cidade</label>
            <input type="text" name="cidade" placeholder="Cidade"
                   class="form-control @error('cidade') is-invalid @enderror"
                   value="{{ old('cidade') }}">

            <label class="form-label small mb-1">Bairro</label>
            <input type="text" name="bairro" placeholder="Bairro"
                   class="form-control @error('bairro') is-invalid @enderror"
                   value="{{ old('bairro') }}">

            <label class="form-label small mb-1">Endereço</label>
            <input type="text" name="endereco" placeholder="Rua, avenida ou local de referência"
                   class="form-control @error('endereco') is-invalid @enderror"
                   value="{{ old('endereco') }}">

            <input type="hidden" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" value="{{ old('longitude') }}">

            <div class="mb-3 mt-2">
                <label class="form-label small mb-1">Localização no mapa</label>
                <div class="address-map-card">
                    <div class="address-map" data-address-map></div>
                    <p class="address-map-help">Clique no mapa ou arraste o ponto para ajustar sua localização.</p>
                </div>
                <div class="address-map-status mt-1" data-address-status></div>
            </div>

            <label class="form-label small mb-1">Senha</label>
            <input type="password" name="senha" placeholder="Senha (mín. 6 caracteres)" required
                   class="form-control @error('senha') is-invalid @enderror"
                   autocomplete="new-password">

            <label class="form-label small mb-1">Confirmar senha</label>
            <input type="password" name="senha_confirmation" placeholder="Repita a senha" required
                   class="form-control"
                   autocomplete="new-password">

            <button type="submit" class="submit-btn">Criar conta</button>

            <p class="login-link">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('form-cadastro');
        const documentoInput = document.getElementById('documento');
        const telefoneInput = document.getElementById('telefone');

        const telefoneMask = IMask(telefoneInput, {
            mask: '(00) 00000-0000'
        });

        documentoInput.addEventListener('input', function () {
            let value = documentoInput.value.replace(/\D/g, '');

            if (value.length <= 11) {
                documentoInput.value = value.replace(/(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})/, function (_, p1, p2, p3, p4) {
                    let result = '';
                    if (p1) result += p1;
                    if (p2) result += '.' + p2;
                    if (p3) result += '.' + p3;
                    if (p4) result += '-' + p4;
                    return result;
                });
            } else {
                value = value.slice(0, 14);
                documentoInput.value = value.replace(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/, function (_, p1, p2, p3, p4, p5) {
                    let result = '';
                    if (p1) result += p1;
                    if (p2) result += '.' + p2;
                    if (p3) result += '.' + p3;
                    if (p4) result += '/' + p4;
                    if (p5) result += '-' + p5;
                    return result;
                });
            }
        });

        form.addEventListener('submit', function () {
            documentoInput.value = documentoInput.value.replace(/\D/g, '');
            telefoneInput.value = telefoneMask.unmaskedValue;
        });
    </script>
</main>

@include('Components.footer')

</body>
</html>
