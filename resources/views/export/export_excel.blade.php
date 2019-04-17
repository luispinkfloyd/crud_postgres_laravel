<style>
tr > td {
	border: 1px solid #000000;
}

.registros tr:first-child{
	background-color: #1D2839;
	color: #FFFFFF;
	border: 1px solid #000000;
}

.botones tr > td{
	border:#000000 thick 1px;
	color: #292929;
}
</style>
<table class="botones">
	<tr>
    	<td align="right"><p>Host:</p></td><td><p><h4><b>{{$db_host}}</b></h4></p></td>
    </tr>
    <tr>
        <td align="right"><p>Usuario:</p></td><td><p><h4><b>{{$db_usuario}}</b></h4></p></td>
    </tr>
    <tr>
        <td align="right"><p>Data Base:</p></td><td><p><h4><b>{{$database}}</b></h4></p></td>
    </tr>
    <tr>
        <td align="right"><p>Tabla:</p></td><td><p><h4><b>{{$tabla_selected}}</b></h4></p></td>
    </tr>
</table>
@if(isset($where1))
<table class="botones">
	<tr>
    	<td><p>Columna:</p></td><td><p><h4><b>{{$columna_selected1}}</b></h4></p></td>
    </tr>
    <tr>
        <td><p>Comparador:</p></td><td><p><h4><b>{{$comparador1}}</b></h4></p></td>
    </tr>
    <tr>
        <td><p>Parámetro:</p></td><td><p><h4><b>{{$where1}}</b></h4></p></td>
    </tr>
    
</table>
@endif
<table class="registros">
    <tbody>
        <tr>
        @foreach($columnas as $columna)
            <td>{{$columna->column_name}} ({{$columna->data_type}})</td>
        @endforeach
        </tr>
        @forelse($registros as $registro)
            <tr>
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
				$count_columnas = count($columnas);
			?>
                <td colspan="{{$count_columnas}}" align="center"><h3><b>Sin registros</b></h3></td>
        @endforelse      
    </tbody>
</table>