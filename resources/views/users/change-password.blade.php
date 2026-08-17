<h5 class="text-center">Mudar sua Senha</h5>

<form method="POST" action="{{ url('perfil/changePassword') }}">
    @csrf

<div class="row">
    <div class="col-md-6 offset-md-3">
        <x-form.password name="password" label="Entre com a Nova Senha:" />
        <x-form.password name="password_confirmation" label="Confirme a Nova Senha:" />
    </div>
</div>

<div class="text-center">
    <button type="submit" class="btn btn-primary"> <i class="fa fa-fw fa-lg fa-check-circle"></i> Mudar Senha
    </button>
</div>


</form>
<br>
