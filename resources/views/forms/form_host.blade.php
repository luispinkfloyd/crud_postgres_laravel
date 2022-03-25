<form class="form-general" action="{{ route('host') }}" method="post">
    @csrf
    <div class="row form-host-row">
        <h3 class="pl-3 pt-1">Buscador por tablas</h3>
    </div>
    <div class="row form-host-row">
        <div class="col-sm form-group">
            <label for="db_grupo">Grupo</label>
            {{-- <input type="text" class="form-control" value="{{old('db_grupo')}}" id="db_grupo" name="db_grupo" placeholder="Ingresar grupo..." required> --}}
            <select class="form-control" name="db_grupo" id="db_grupo" required>
                <option value selected disabled>--Seleccione--</option>
                @foreach ($grupos as $grupo)
                    <option value="{{$grupo->id}}">{{$grupo->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm form-group">
            <label for="db_host">Host</label>
            {{-- <input type="text" class="form-control" value="{{old('db_host')}}" id="db_host" name="db_host" placeholder="Ingresar host..." required> --}}
            <select class="form-control" id="db_host" name="db_host" required disabled>
                <option value selected disabled>--Seleccione--</option>
            </select>
        </div>
        {{-- <div class="col-sm form-group">
            <label for="db_usuario">Usuario</label>
            <input type="text" class="form-control" value="{{old('db_usuario')}}" id="db_usuario" name="db_usuario" placeholder="Ingresar usuario..." autocomplete="off" required>
        </div>
        <div class="col-sm form-group">
            <label for="db_contrasenia">Contraseña</label>
            <input type="password" class="form-control" value="{{old('db_contrasenia')}}" id="db_contrasenia" name="db_contrasenia" placeholder="Contraseña" autocomplete="new-password" required>
        </div> --}}
        <div class="col-sm-2 form-group form-host-col-submit">
            <button type="submit" class="btn btn-success">Seleccionar</button>
            <a href="{{route('home')}}" class="btn btn-danger">Limpiar</a>
        </div>
    </div>
</form>
{{-- <form class="form-general mt-1" action="{{ route('buscador_string') }}" method="get">
    {{-- @csrf --}}
    {{-- <div class="row form-host-row">
        <h3 class="pl-3 pt-1">Buscador de strings</h3>
    </div>
    <div class="row form-host-row">
        <div class="col-sm form-group">
            <label for="db_host_string">Host</label>
            <input type="text" class="form-control" value="{{old('db_host_string')}}" id="db_host_string" name="db_host_string" placeholder="Ingresar host..." required>
        </div>
        <div class="col-sm form-group">
            <label for="db_usuario_string">Usuario</label>
            <input type="text" class="form-control" value="{{old('db_usuario_string')}}" id="db_usuario_string" name="db_usuario_string" placeholder="Ingresar usuario..." autocomplete="off" required>
        </div>
        <div class="col-sm form-group">
            <label for="db_contrasenia_string">Contraseña</label>
            <input type="password" class="form-control" value="{{old('db_contrasenia_string')}}" id="db_contrasenia_string" name="db_contrasenia_string" placeholder="Contraseña" autocomplete="new-password" required>
        </div>
        <div class="col-sm form-group">
            <label for="db_database_string">Base</label>
            <select class="custom-select" name="db_database_string" id="db_database_string" disabled required>
            </select>
        </div>
        <div class="col-sm-2 form-group form-host-col-submit">
            <button type="submit" class="btn btn-success">Seleccionar</button>
        </div>
    </div>
</form> --}}

<div class="container" style="margin-top:20px" align="center">
    <div class="form-group row">
        <div class="col">
            <a href="#modal-form-grupos" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modal-form-grupos">Crear nuevo grupo</a>
        </div>
        <div class="col">
            <a href="#modal-form-bases" class="btn btn-outline-primary @if(count($grupos) < 1) {{'disabled'}} @endif" data-toggle="modal" data-target="#modal-form-bases" @if(count($grupos) < 1) {{'aria-disabled="true"'}} @endif>Crear nuevo host</a>
            <br>
            <small class="small-color">(*) Solo se activa si hay creado al menos un grupo.</small>
        </div>
    </div>  
</div>

@include('forms.form_grupos')

@if(count($grupos) > 0)
    @include('forms.form_bases')
@endif

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        
        $('#db_grupo').on('change', function (){
            //alert( this.value );
            var db_grupo = $(this).val();
            $('#db_host').empty().prop("disabled", true);
            $('#db_host').append($('<option>',{value:"",text:"--Seleccione--"}).prop("disabled", true).prop("selected", true));
            $('#db_usuario').val('');
            $('#db_contrasenia').val('');
            $.ajax({
                type:"GET",
                url:"{{url('verificar_grupo')}}?grupo_selected="+db_grupo,
                success:function(new_res){
                    if(new_res){
                        $('#db_host').empty().removeAttr("disabled");
                        $('#db_host').append($('<option>',{value:"",text:"--Seleccione--"}).prop("disabled", true).prop("selected", true));
                        $.each(new_res, function(k,v) {
                            $('#db_host').append($('<option>',{
                                value: v['id'],
                                text: v['servidor']
                            }));
                        });
                    }
                }
            });
        });
        
        
        /*$('#db_host').on('change', function (){
            var db_host = $(this).val();
            $.ajax({
                type:"GET",
                url:"{{url('verificar_host')}}?host_selected="+db_host,
                success:function(res){
                    if(res){
                        $('#db_usuario').val(res[0]['usuario']);
                        $('#db_contrasenia').val(res[0]['password']);
                    }
                }
            });
        });*/

        /*$('#db_host_string').on('input', function (){
            var db_host_string = $(this).val();
            $.ajax({
                type:"GET",
                url:"{{url('verificar_host')}}?servidor_bases_selected="+db_host_string,
                success:function(res){
                    if(res){
                        $('#db_usuario_string').val(res[0]['usuario']);
                        $('#db_contrasenia_string').val(res[0]['password']);
                        var db_usuario_string = $('#db_usuario_string').val();
                        var db_contrasenia_string = $('#db_contrasenia_string').val();
                        $.ajax({
                            type:"GET",
                            url:"{{url('get_bases_string')}}?db_host_string="+db_host_string+"&db_contrasenia_string="+db_contrasenia_string+"&db_usuario_string="+db_usuario_string,
                            success:function(new_res){
                                if(new_res){
                                    $('#db_database_string').empty().removeAttr("disabled");
                                    $.each( new_res, function(k,v) {
                                        $('#db_database_string').append($('<option>', {text:v}));
                                    });
                                }
                            }
                        });
                    }
                }
            });
        });*/
    });

</script>

 @endsection
