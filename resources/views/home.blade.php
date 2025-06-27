@extends("layout.content")

@section("content")

  <div class="container">
    {{-- NOVA TAREFA --}}
    <div class="row">
        <div class="col s12 flex justify-center">
          <div class="w-192">
            <task-new></task-new>  
          </div>
			  </div>
    </div>

    {{-- CAMPO DE PESQUISA / FILTRO --}}
    <div class="row">
        <div class="col s12 flex justify-center">
			    <div class="w-160">
            <task-filter/>
          </div>
        </div>
    </div>

    {{-- LISTA DE TAREFAS --}}
    <div class="row">
        <div class="col s12 flex justify-center ">
          <div class="w-192">
            <task-list/>
          </div>
        </div>
    </div>
  </div>

  

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])