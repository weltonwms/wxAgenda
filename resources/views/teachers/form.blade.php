

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Geral</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="disponibilidade_t-tab" data-toggle="tab" href="#disponibilidade_t" role="tab" aria-controls="disponibilidade_t" aria-selected="false">Disponibilidade</a>
  </li>
  
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  <div class="row">
    <div class="col-lg-12">
        {{ Form::bsText('nome',null,['label'=>"Nome *",'class'=>""]) }}
        {{ Form::bsText('email',null,['label'=>"Email ",'class'=>""]) }}
        {{ Form::bsText('telefone',null,['label'=>"Telefone ",'class'=>""]) }}  
    
    </div>

    
</div>
  </div>
  <div class="tab-pane fade" id="disponibilidade_t" role="tabpanel" aria-labelledby="disponibilidade_t-tab">
    @include('teachers.disponibilidade')
  </div>
 
</div>