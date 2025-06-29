<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/cadastro.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
</head>

@include('Components.navbarbootstrap')




@if(session('success'))
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="text-nome" id="successModalLabel"> APPOLO </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  {{ session('success') }}
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal"> Fechar </button>
              </div>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener("DOMContentLoaded", function() {
          var successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
      });
  </script>
@endif

@php
    $portfolio = $usuario->portfolioArtista;
@endphp

 
@if ($errors->any())
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="text-nome" id="successModalLabel"> APPOLO </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                     @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal"> Fechar </button>
              </div>
          </div>
      </div>
  </div>
        <script>
      document.addEventListener("DOMContentLoaded", function() {
          var successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
      });
  </script>
       
@endif
        










<main class="cadastro-container">
    <div class="left-illustration">
        <img src="{{ asset('imgs/artistacadastro.jpg') }}" alt="Ilustração Artista">
    </div>

    <div class="form-section" id="formulario-login">
        <h1 class="form-title">Cadastre-se Artista !</h1>
        <form id="form-cadastro" action="{{ route('usuarios.storeArtista') }}" method="POST">
            @csrf

            <input type="text" name="nome" placeholder="Nome" required>

            <div class="gender-options">
                <label><input type="radio" name="sexo_usuario" value="1" required> Masculino</label>
                <label><input type="radio" name="sexo_usuario" value="2"> Feminino</label>
                <label><input type="radio" name="sexo_usuario" value="3"> Não informar</label>
            </div>

            <input type="email" name="email" placeholder="Email" required>

            <input type="text" id="telefone" name="telefone" placeholder="Telefone" maxlength="15" inputmode="numeric">
           
            <input type="text" id="documento" name="documento" placeholder="CPF/CNPJ" maxlength="18">

            <input type="date" name="data_nasc" placeholder="Data de Nascimento" required>

            <input type="password" name="senha" placeholder="Senha" required>
            <input type="password" name="senha_confirmation" placeholder="Confirmar senha" required>

            <button type="submit" class="submit-btn">Criar conta</button>

            <p class="login-link">Já tem conta? <a href="{{ route('login') }}">Conecte-se</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('form-cadastro');
        const documentoInput = document.getElementById('documento');
        const telefoneInput = document.getElementById('telefone');

     //imask telefone
        const telefoneMask = IMask(telefoneInput, {
            mask: '(00) 00000-0000'
        });

        // Máscara CPF/CNPJ
        documentoInput.addEventListener('input', function () {
            let value = documentoInput.value.replace(/\D/g, '');

            if (value.length <= 11) {
                documentoInput.value = value.replace(/(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})/, function (_, p1, p2, p3, p4) {
                    let result = '';
                    if (p1) result += p1;
                    if (p2) result += `.${p2}`;
                    if (p3) result += `.${p3}`;
                    if (p4) result += `-${p4}`;
                    return result;
                });
            } else {
                value = value.slice(0, 14);
                documentoInput.value = value.replace(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/, function (_, p1, p2, p3, p4, p5) {
                    let result = '';
                    if (p1) result += p1;
                    if (p2) result += `.${p2}`;
                    if (p3) result += `.${p3}`;
                    if (p4) result += `/${p4}`;
                    if (p5) result += `-${p5}`;
                    return result;
                });
            }
        });

        // Limpa as máscaras antes de enviar o formulário
        form.addEventListener('submit', function () {
            documentoInput.value = documentoInput.value.replace(/\D/g, '');
            telefoneInput.value = telefoneMask.unmaskedValue; // <-- valor limpo do IMask
        });
    </script>
</main>

@include('Components.footer')

</html>
