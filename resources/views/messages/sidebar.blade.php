<a class="mb-2 btn btn-primary btn-block" href="{{route('messages.create',request()->query())}}">Escrever</a>
<div class="tile p-0">
    <h4 class="tile-title folder-head">Pastas</h4>
    <div class="tile-body">
        <ul class="nav nav-pills flex-column mail-nav mail_sidebar">
            <li @class(["nav-item",'active'=>!request('sent')])>
                <a class="nav-link" href="{{route('messages.index')}}">
                    <i class="fa fa-inbox fa-fw"></i> Entrada
                    <span
                        class="badge badge-pill badge-primary float-right">
                        {{session('messages_not_read')}}
                    </span>
                    </a>
            </li>
            <li @class(["nav-item",'active'=>request('sent')])>
                <a class="nav-link" href="{{route('messages.index',['sent'=>1])}}">
                    <i class="fa fa-send fa-fw"></i> Enviados
                </a>
            </li>
            

        </ul>
    </div>
</div>