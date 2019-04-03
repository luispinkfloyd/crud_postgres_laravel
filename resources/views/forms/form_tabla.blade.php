<form class="form-general" action="{{ route('tabla') }}" method="get">
    <input type="hidden" name="database" value="{{$database}}">
    <input type="hidden" name="schema" value="{{$schema}}">
    <div class="row form-select-row">
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="database_span">Data Base</span>
                </div>
                <select class="custom-select" disabled>
                    <option selected>{{$database}}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="schemas_span">Schemas</span>
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
                <select class="custom-select" name="tabla_selected" onChange="this.form.submit();" required>
                    <option disabled selected value>--Seleccione--</option>
                    @foreach($tablas as $tabla)
                        <option <?php if(isset($tabla_selected)){ if($tabla->table_name === $tabla_selected){ echo 'selected'; } } ?>>{{$tabla->table_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>