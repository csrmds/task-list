@extends("layout.content")


@section("content")

  <div class="container">
    {{-- NOVA TAREFA --}}
    <div class="row">
        <div class="col s12">
            <task-new></task-new>
        </div>
    </div>

    {{-- CAMPO DE PESQUISA / FILTRO --}}
    <div class="row">
        <div class="col s12">
			<p>aqui será o filtro</p>
        </div>
    </div>

    {{-- LISTA DE TAREFAS --}}
    <div class="row">
        <div class="col s12">
			<p>aqui será a lista de tarefas</p>
        </div>
    </div>
  </div>

@endsection

@vite(['resources/js/app.js'])