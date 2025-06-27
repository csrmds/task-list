@extends("layout.content")

<style>
.centralizada {
  display: flex;
  justify-content: center;
  background-color: darkgray;
}

.w-100 {
  background-color:darkolivegreen;
  width: 100%;
}
</style>

@section("content")

  <div class="container">
    {{-- NOVA TAREFA --}}
    <div class="row">
        <div class="col s12 centralizada">
          <div class="w-192">
            <task-new></task-new>  
          </div>
			  </div>
    </div>

    {{-- CAMPO DE PESQUISA / FILTRO --}}
    <div class="row">
        <div class="col s12 centralizada">
			    <p class="w-128">aqui será o filtro</p>
        </div>
    </div>

    {{-- LISTA DE TAREFAS --}}
    <div class="row">
        <div class="col s12 centralizada ">
			<p class="">aqui será a lista de tarefas</p>
        </div>
    </div>
  </div>

  

@endsection

@vite(['resources/js/app.js', 'resources/css/app.css'])