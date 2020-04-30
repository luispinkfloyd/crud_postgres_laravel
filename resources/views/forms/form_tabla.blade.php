<form id="database" action="{{ route('host') }}" method="post"> @csrf </form>
<form id="schema" action="{{ route('database') }}" method="get"> <input type="hidden" name="database" value="{{$database}}"> </form>
<form class="form-general" action="{{ route('tabla') }}" method="get" style="margin-bottom:2px">
    <input type="hidden" name="database" value="{{$database}}">
    <input type="hidden" name="schema" value="{{$schema}}">
    <div class="row form-select-row">
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="database_span">Data Base<button class="btn btn-sm btn-link ml-2" style="padding: 0px" type="submit" form="database"><img src="{{ asset('img/recargar.png')}}" height="15"></button></span>
                </div>
                <select class="custom-select" disabled>
                    <option selected>{{$database}}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="schemas_span">Schemas<button class="btn btn-sm btn-link ml-2" style="padding: 0px" type="submit" form="schema"><img src="{{ asset('img/recargar.png')}}" height="15"></button></span>
                </div>
                <select class="custom-select" disabled>
                    <option selected>{{$schema}}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="tablas_span">Tablas</span>
                </div>
                	<input type="text" autocomplete="off" name="tabla_selected" list="list_tablas" class="form-control" onChange="this.form.submit();" required <?php if(isset($tabla_selected)) echo 'value="'.$tabla_selected.'"'; ?>>
                <datalist id="list_tablas">
                    @foreach($tablas as $tabla)
                        <option>{{$tabla->table_name}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
</form>