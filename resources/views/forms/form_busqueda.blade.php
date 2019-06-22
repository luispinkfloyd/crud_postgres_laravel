<form class="form-general" action="{{ route('tabla') }}" method="get">
    <input type="hidden" name="database" value="{{$database}}">
    <input type="hidden" name="schema" value="{{$schema}}">
    <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
    @if(isset($sort))
        <input type="hidden" name="sort" value="{{$sort}}">
    @endif
    @if(isset($ordercol_def))
        <input type="hidden" name="ordercol" value="{{$ordercol_def}}">
    @endif
    <div class="row form-select-row">
        <div class="col-sm-3 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="columna_span">Columna</span>
                </div>
                <select class="custom-select" name="columna_selected1" required>
                    <option disabled selected value>--Seleccione--</option>
                    @foreach($columnas as $columna)
                        <option <?php if(isset($columna_selected1)) if($columna_selected1 === $columna->column_name) echo 'selected';?>>{{$columna->column_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-3 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="comparador_span">Comparador</span>
                </div>
                <select class="custom-select" name="comparador1" required>
                	<option value="ilike" <?php if(isset($comparador1)) if($comparador1 === 'ilike') echo 'selected';?>>Contiene</option>
                    <option value="=" <?php if(isset($comparador1)) if($comparador1 === '=') echo 'selected';?>>Igual</option>
                    <option value=">=" <?php if(isset($comparador1)) if($comparador1 === '>=') echo 'selected';?>>Mayor o Igual</option>
                    <option value="<=" <?php if(isset($comparador1)) if($comparador1 === '<=') echo 'selected';?>>Menor o Igual</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="where1_span">Parámetro</span>
                </div>
                <input type="text" name="where1" class="form-control" placeholder="Parámetro de búsqueda..." required <?php if(isset($where1)) echo 'value="'.$where1.'"';?>>
            </div>
        </div>
        <div class="col-sm-2 form-group">
        	<button type="submit" class="btn btn-primary">Buscar</button>&nbsp;
            <?php echo '<input type="button" class="btn btn-danger" value="Limpiar" onclick="javascript:location.href='."'tabla?ordercol=".$ordercol_def."&database=".$database."&schema=".$schema."&tabla_selected=".$tabla_selected."&sort=".$sort."';".'"/>';
			?> 
        </div>
    </div>
 </form>