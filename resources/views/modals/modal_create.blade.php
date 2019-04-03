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


<!-- create Modal HTML -->
<div id="addModal" class="modal fade">
	<div class="modal-dialog" style="max-width:600px">
		<div class="modal-content">
			<form method="get" action="{{ route('home.store') }}">
            	<input type="hidden" name="database" value="{{$database}}">
                <input type="hidden" name="schema" value="{{$schema}}">
                <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
                <div class="modal-header">						
					<h4 class="modal-title">Agregar Registro</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
                	@foreach($columnas as $columna)
                        @if($columna->type === 'character' | $columna->type === 'character varying' | $columna->type === 'text' | $columna->type === 'char' | $columna->type === 'varchar')
                        <div class="form-group">
                            <label for="{{$columna->column_name}}">{{$columna->column_name}} 
                            <small>
                            <?php 
							if($columna->required === 'NO'){
								echo '(Obligatorio)';
							}else{
								echo '(No obligatorio)';
							}
							?>
                            </small>:</label>
                            <input type="text" class="form-control" name="{{$columna->column_name}}"
                            <?php
							if($columna->required === 'NO') echo ' required ';
							if(isset($columna->max_char)) echo ' maxlength="'.$columna->max_char.'" ';
							?>
                            placeholder="texto...">
                        </div>
                        @elseif($columna->type === 'int' | $columna->type === 'integer' | $columna->type === 'smallint' or $columna->type === 'bigint' | $columna->type === 'numeric')
                        <div class="form-group">
                            <label for="{{$columna->column_name}}">{{$columna->column_name}} 
                            <small>
                            <?php 
							if($columna->required === 'NO'){
								echo '(Obligatorio)';
							}else{
								echo '(No obligatorio)';
							}
							?>
                            </small>:</label>
                            <input type="number" class="form-control" name="{{$columna->column_name}}"
                            <?php
							if($columna->required === 'NO') echo ' required ';
							?>
                            placeholder="número...">
                        </div>
                        @elseif($columna->type === 'date')
                        <div class="form-group">
                            <label for="{{$columna->column_name}}">{{$columna->column_name}} 
                            <small>
                            <?php 
							if($columna->required === 'NO'){
								echo '(Obligatorio)';
							}else{
								echo '(No obligatorio)';
							}
							?>
                            </small>:</label>
                            <input type="date" class="form-control" name="{{$columna->column_name}}"
                            <?php
							if($columna->required === 'NO') echo ' required ';
							?>
                            >
                        </div>
                        @elseif($columna->type === 'timestamp without time zone' or $columna->type === 'timestamp with time zone')
                        <div class="form-group">
                            <label for="{{$columna->column_name}}">{{$columna->column_name}} 
                            <small>
                            <?php 
							if($columna->required === 'NO'){
								echo '(Obligatorio)';
							}else{
								echo '(No obligatorio)';
							}
							?>
                            </small>:</label>
                            <input type="datetime-local" class="form-control" name="{{$columna->column_name}}"
                            <?php
							if($columna->required === 'NO') echo ' required ';
							?>
                            >
                        </div>
                        @endif
                    @endforeach
                </div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
					<input type="submit" class="btn btn-success" value="Agregar">
				</div>
			</form>
		</div>
	</div>
</div>