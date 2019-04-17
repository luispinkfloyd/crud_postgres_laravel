<?php
foreach($columnas as $columna){

	$primera_columna = $columna->column_name;
	
	break;

}
?>

@foreach($registros as $registro)
<!-- Delete Modal HTML -->
<div id="editModal<?php echo str_replace('.','_',$registro->$primera_columna); ?>" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('home.edit', $registro->$primera_columna)}}" method="get">
            	<input type="hidden" name="database" value="{{$database}}">
                <input type="hidden" name="schema" value="{{$schema}}">
                <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
                <input type="hidden" name="primera_columna" value="{{$primera_columna}}">
                <div class="modal-header">						
                    <h4 class="modal-title">Editar Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">					
                    @foreach($columnas as $columna)
                    	<?php
							$nombre_columna = $columna->column_name;
						?>
                        @if($columna->column_name === $primera_columna)
                        	<label for="{{$columna->column_name}}">{{$columna->column_name}}:</label>
                            <input type="text" class="form-control" name="{{$columna->column_name}}" value="{{$registro->$nombre_columna}}" readonly>
                        @elseif($columna->type === 'character' | $columna->type === 'character varying' | $columna->type === 'text' | $columna->type === 'char' | $columna->type === 'varchar')
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
							if($charset_def !== 'UTF8'){
								$value = utf8_encode($registro->$nombre_columna);
							}else{
								$value = $registro->$nombre_columna;
							}
							?>
                            value="{{$value}}">
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
							if($charset_def !== 'UTF8'){
								$value = utf8_encode($registro->$nombre_columna);
							}else{
								$value = $registro->$nombre_columna;
							}
							?>
                            value="{{$value}}">
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
							if($charset_def !== 'UTF8'){
								$value = utf8_encode($registro->$nombre_columna);
							}else{
								$value = $registro->$nombre_columna;
							}
							?>
                            value="{{$value}}">
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
                            
                            <input type="text" class="form-control" name="{{$columna->column_name}}"
                            <?php
							if($columna->required === 'NO') echo ' required ';
							if($charset_def !== 'UTF8'){
								$value = utf8_encode($registro->$nombre_columna);
							}else{
								$value = $registro->$nombre_columna;
							}
							?>
                            value="{{$value}}">
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button class="btn btn-success" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach