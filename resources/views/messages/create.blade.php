<div class="tile">
    <form method="POST" action="{{ route('messages.store', request()->query()) }}" id="adminForm">
    @csrf
    <div class="row">
        <div class="col-lg-12">
        <x-form.select name="recipient_id" :list="$usersList" label="Para *" placeholder="--Selecione--" class="select2" />
        <x-form.text name="subject" label="Assunto *" />

            <div class="form-group">
                <label for="body">Texto</label>
                <x-form.textarea name="body" rows="3" id="body" />
            </div>

           
        </div>



    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button class="btn btn-primary btn-sm mr-2" type="submit">
                    <i class="fa fa-share"></i> Enviar
                </button>
                <a class="btn btn-outline-secondary btn-sm" href="{{route('messages.index',request()->query())}}">
                    <i class="fa fa-close"></i> Cancelar
                </a>
                
            </div>
        </div>
    </div>


    </form>
</div>