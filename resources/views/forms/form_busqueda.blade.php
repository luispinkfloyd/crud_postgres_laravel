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
                <select class="custom-select" name="columna_selected1" id="columna_selected1" onChange="select_columna()" required>
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
        <div class="col-sm-3 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="where1_span">Parámetro</span>
                </div>
                <input type="text" name="where1" list="where1_datalist" autocomplete="off" class="form-control" placeholder="Parámetro de búsqueda..." required <?php if(isset($where1)) echo 'value="'.$where1.'"';?>>
				<datalist id="where1_datalist">
				</datalist>
            </div>
        </div>
        <div class="col-sm-1 form-group">
        	<div class="custom-control custom-checkbox" style="top:7px">
              <input type="checkbox" name="caracteres_raros" class="custom-control-input" id="customCheck1" <?php if($caracteres_raros === 'S') echo 'checked'; ?>>
              <label class="custom-control-label" for="customCheck1">Acentos</label>
            </div>
        </div>
        <div class="col-sm-2 form-group">
        	<button type="submit" class="btn btn-primary">Buscar</button>&nbsp;
            <?php echo '<input type="button" class="btn btn-danger" value="Limpiar" onclick="javascript:location.href='."'tabla?ordercol=".$ordercol_def."&limpiar=1&database=".$database."&schema=".$schema."&tabla_selected=".$tabla_selected."&sort=".$sort."';".'"/>';
			?> 
        </div>
    </div>
 </form>
 
 @section('script')
 
 <script type="text/javascript">
 
 	function select_columna(){
		
		$('#where1_datalist').empty();
		
		var consulta;

        //hacemos focus al campo de búsqueda
        $("#columna_selected1").focus();

        //obtenemos el texto introducido en el campo de búsqueda
        consulta = $("#columna_selected1").val();
		
		
		//alert(consulta)

        //hace la búsqueda

        $.ajax({
              type: "GET",
              url: "{{ route('ajax_columna')}}",
              data: "columna="+consulta,
              dataType: "json",
              error: function(){
                    alert("error petición ajax");
              },
              success: function(result){

                  $.each( result, function(k,v) {
					  
                        $('#where1_datalist').append($('<option>', {text:v}));
                  });

              }
        });
		
	}
 
 </script>
 
 @endsection