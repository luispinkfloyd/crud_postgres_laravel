<form class="form-general" action="{{ route('where1') }}" method="get">
    <input type="hidden" name="database" value="{{$database}}">
    <input type="hidden" name="schema" value="{{$schema}}">
    <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
    <div class="row form-select-row">
        <div class="col-sm-4 form-group">
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
                	<option disabled selected value>--Seleccione--</option>
                    <option value="=" <?php if(isset($comparador1)) if($comparador1 === '=') echo 'selected';?>>Igual</option>
                    <option value="ilike" <?php if(isset($comparador1)) if($comparador1 === 'ilike') echo 'selected';?>>Contiene</option>
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
        <div class="col-sm-1 form-group">
        	<button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </div>
 </form>
 <div class="container" style="max-width:400px;margin:5px auto 0px auto;">
    @if(session()->get('registro_agregado'))
        <div class="alert alert-success" style="text-align:center">
            {{ session()->get('registro_agregado') }}
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-success" value="x" onclick="javascript:window.location.reload();"/>
        </div><br />
    @elseif(session()->get('registro_actualizado'))
        <div class="alert alert-success" style="text-align:center">
            {{ session()->get('registro_actualizado') }}
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-success" value="x" onclick="javascript:window.location.reload();"/>
        </div><br />
    @elseif(session()->get('registro_eliminado'))
        <div class="alert alert-success" style="text-align:center">
            {{ session()->get('registro_eliminado') }}
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-success" value="x" onclick="javascript:window.location.reload();"/>
        </div><br />
    @endif
</div>
<div class="table-responsive tabla-resultados borde">
    <a href="#addModal" class="btn btn-primary" data-toggle="modal" style="margin:2px;"><span>Añadir Registro</span></a>
    <form method="get" action="{{ route('export_excel') }}" style="display:inline-block">
        <input type="hidden" name="database" value="{{$database}}">
        <input type="hidden" name="schema" value="{{$schema}}">
        <input type="hidden" name="tabla_selected" value="{{$tabla_selected}}">
        @if(isset($where1))
            <input type="hidden" name="columna_selected1" value="{{$columna_selected1}}">
            <input type="hidden" name="comparador1" value="{{$comparador1}}">
            <input type="hidden" name="where1" value="{{$where1}}">
        @endif
        <button type="submit" class="btn btn-success"><img src="{{asset('img/excel.png')}}" height="20" style="float:left;padding-right:2px"><span>Exportar a  Excel</span></button>
	</form>
    <table class="table table-sm table-bordered table-striped table-hover">
        <thead>
            <tr>
            <th scope="col"></th>
            @foreach($columnas as $columna)
                <th scope="col">{{$columna->column_name}}<br><small>{{$columna->data_type}}</small></th>
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
                <td><a href="#deleteModal{{$registro->$primera_columna}}" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a></td>
                      @foreach($columnas as $columna)
                          <?php 
                              $columna_registro = $columna->column_name;
                          ?>
                          
                          
                          
                              <td>{{$registro->$columna_registro}}</td>
                              
                               
                      @endforeach
                  </tr>
            @empty
				<?php
                    $count_columnas = count($columnas);
                ?>
                	<td colspan="{{$count_columnas}}" class="alert-danger" align="center"><h3><b>Sin registros</b></h3></td>
            @endforelse
       </tbody>
    </table>
</div>
<div class="container div-paginacion">
    {{$registros->appends(Illuminate\Support\Facades\Input::except('page'))->links()}}
</div>