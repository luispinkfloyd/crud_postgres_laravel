@section('style')

<style type="text/css">
/* Modal styles */
	.modal .modal-dialog {
		max-width: 400px;
	}
	.modal .modal-header, .modal .modal-body, .modal .modal-footer {
		padding: 20px 30px;
	}
	.modal .modal-content {
		border-radius: 3px;
	}
	.modal .modal-footer {
		background: #ecf0f1;
		border-radius: 0 0 3px 3px;
	}
    .modal .modal-title {
        display: inline-block;
    }
	.modal .form-control {
		border-radius: 2px;
		box-shadow: none;
		border-color: #dddddd;
	}
	.modal textarea.form-control {
		resize: vertical;
	}
	.modal .btn {
		border-radius: 2px;
		min-width: 100px;
	}
	.modal form label {
		font-weight: normal;
	}
	.autocomplete-items {
	  position: absolute;
	  border: 1px solid #d4d4d4;
	  border-bottom: none;
	  border-top: none;
	  z-index: 99;
	  /*position the autocomplete items to be the same width as the container:*/
	  top: 100%;
	  left: 0;
	  right: 0;
	}
</style>

@endsection

@if(isset($tabla_selected))
@section('titulo', $tabla_selected.' - ')
@endif

@include('forms.form_busqueda')
@include('tablas.alerts_table')
<div class="table-responsive tabla-resultados borde">
    <div class="row">
    	<div class="col" align="right">
        	<a href="#addModal" class="btn btn-primary" data-toggle="modal" style="margin:2px;"><i class="material-icons" style="float:left;padding-right:5px;">add</i><span>Añadir Registro</span></a>
        </div>
        <div class="col">
            @if($count_registros > 0)
            <form method="get" action="{{ route('export_excel') }}" style="display:inline-block">
                <input type="hidden" name="database" value="{{$database}}">
                <input type="hidden" name="schema" value="{{$schema}}">
                <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
                @if(isset($where1))
                    <input type="hidden" name="columna_selected1" value="{{$columna_selected1}}">
                    <input type="hidden" name="comparador1" value="{{$comparador1}}">
                    <input type="hidden" name="where1" value="{{$where1}}">
                @endif
				@if(isset($where2))
                    <input type="hidden" name="columna_selected2" value="{{$columna_selected2}}">
                    <input type="hidden" name="comparador2" value="{{$comparador2}}">
                    <input type="hidden" name="where2" value="{{$where2}}">
                @endif
                @if(isset($sort))
                    <input type="hidden" name="sort" value="{{$sort}}">
                @endif
                @if(isset($ordercol_def))
                	<input type="hidden" name="ordercol" value="{{$ordercol_def}}">
                @endif
                <button type="submit" class="btn btn-success" style="margin:2px;"><img src="{{asset('img/excel.png')}}" height="20" style="float:left;padding-right:5px"><span>Exportar a  Excel</span></button>
            </form>
            @endif
        </div>
        <div class="col-8">
        	<h3 style="margin:2px;">Nombre de tabla: <b>{{$tabla_selected}}</b> | <small>Total registros = <b>{{$count_registros}}</b></small></h3>
        </div>
    </div>
    <?php $ordercol = 1; ?>
    <table class="table table-sm table-bordered table-striped table-hover">
        <thead>
            <tr>
            <th scope="col"></th>
            @foreach($columnas as $columna)
                <th scope="col">{{$columna->column_name}}
                    <form method="get" action="{{route('tabla')}}" style="display:inline-block;">
                        <input type="hidden" name="ordercol" value="{{$ordercol}}">
                        <input type="hidden" name="database" value="{{$database}}">
                        <input type="hidden" name="schema" value="{{$schema}}">
                        <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
                        @if(isset($where1))
                            <input type="hidden" name="columna_selected1" value="{{$columna_selected1}}">
                            <input type="hidden" name="comparador1" value="{{$comparador1}}">
                            <input type="hidden" name="where1" value="{{$where1}}">
                        @endif
                        <input type="hidden" name="sort" value="asc">
                        <button type="submit" class="btn btn-sm btn-link" style="padding-top:0; padding-bottom:0; padding-right:0; padding-left:0;">
													  <img src="{{ asset('img/arriba.png')}}" height="14">
                            {{-- <i class="material-icons" style="font-size:9px;padding:-2px"><b>import_export</b></i>
                            <span><small>Asc</small></span> --}}
                        </button>
                    </form>
                    <form method="get" action="{{route('tabla')}}" style="display:inline-block;">
                        <input type="hidden" name="ordercol" value="{{$ordercol}}">
                        <input type="hidden" name="database" value="{{$database}}">
                        <input type="hidden" name="schema" value="{{$schema}}">
                        <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
                        @if(isset($where1))
                            <input type="hidden" name="columna_selected1" value="{{$columna_selected1}}">
                            <input type="hidden" name="comparador1" value="{{$comparador1}}">
                            <input type="hidden" name="where1" value="{{$where1}}">
                        @endif
                        <input type="hidden" name="sort" value="desc">
                        <button type="submit" class="btn btn-sm btn-link" style="padding-top:0; padding-bottom:0; padding-right:0; padding-left:0;">
													  <img src="{{ asset('img/abajo.png')}}" height="14">
                            {{-- <i class="material-icons" style="font-size:9px;padding:-2px"><b>import_export</b></i>
                            <span><small>Desc</small></span> --}}
                        </button>
                    </form>
                    <br>
                    <small>{{$columna->data_type}}</small>
                    <?php $ordercol++ ?>
                </th>
            @endforeach
            </tr>
        </thead>
        <tbody>
        	@forelse($registros as $registro)
                <tr>
                <?php
				foreach($columnas as $columna){

					$primera_columna = $columna->column_name;

					break;

				}
				?>
                <td>
                	<a href="#deleteModal<?php echo str_replace('.','_',$registro->$primera_columna); ?>" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Borrar">delete</i></a>
                    <a href="#editModal<?php echo str_replace('.','_',$registro->$primera_columna); ?>" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Editar">edit</i></a>
                </td>
                      @foreach($columnas as $columna)
                          <?php
                              $columna_registro = $columna->column_name;
                          ?>

                          	  @if($charset_def !== 'UTF8')

                              	<td>{{utf8_encode($registro->$columna_registro)}}</td>

                              @else

                              	<td>{{$registro->$columna_registro}}</td>

                              @endif

                      @endforeach
                  </tr>
            @empty
				<?php
                    $count_columnas = count($columnas)+1;
                ?>
                	<td colspan="{{$count_columnas}}" class="alert-danger" align="left" style="padding-left:50px"><h3><b>Sin registros encontrados</b></h3></td>
            @endforelse
       </tbody>
    </table>
</div>
<div class="container div-paginacion">
    {{$registros->appends(Illuminate\Support\Facades\Input::except('page'))->links()}}
</div>