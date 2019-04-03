<form class="form-general" action="{{ route('database') }}" method="get">
    <div class="row form-select-row">
        <div class="col-sm-4 form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="database_span">Data Base</span>
                </div>
                <select class="custom-select" name="database" onChange="this.form.submit();" required>
                    <option disabled selected value>--Seleccione--</option>
                    @foreach($bases as $base)
                        <option>{{$base->datname}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>