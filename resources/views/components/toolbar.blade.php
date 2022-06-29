{{-- resources/views/components/toolbar.blade.php --}}


<div class="row" >
    <div class="col-md-12">
       
            <div class="ferramentas ">
                <nav class="navbar  navbar-expand-lg navbar-light bg-light">
                    <span class="navbar-brand d-block d-md-none mb-0 h1">Barra de Ferramentas</span>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse show navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            {{$slot}}
                            
                        </div>
                    </div>
                </nav>
                

            </div>
       
    </div>
</div>
