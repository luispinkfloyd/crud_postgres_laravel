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
                                    </small>:
                                </label>
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
                                </small>:
                            </label>
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
                                </small>:
                            </label>
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
                                </small>:
                            </label>
                            <input type="datetime-local" class="form-control" name="{{$columna->column_name}}"
								<?php
                                if($columna->required === 'NO') echo ' required ';
                                ?>
                            >
                        </div>
                        @elseif($columna->type === 'boolean')
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
                                </small>:
                            </label>
                            <select class="form-control" name="{{$columna->column_name}}"
								<?php
                                if($columna->required === 'NO') echo ' required ';
                                ?>
                            >
                            <option disabled selected value>--Seleccione--</option>
                            <option>true</option>
                            <option>false</option>
                            </select>
                        </div>
                        @elseif($columna->type === 'time without time zone')
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
                                </small>:
                            </label>
                            <input type="time" class="form-control" name="{{$columna->column_name}}"
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