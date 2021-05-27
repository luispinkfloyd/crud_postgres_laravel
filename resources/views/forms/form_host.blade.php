<form class="form-general" action="{{ route('host') }}" method="post">
@csrf
    <div class="row form-host-row">
        <div class="col-sm form-group">
            <label for="db_host">Host</label>
            <input type="text" class="form-control" value="{{old('db_host')}}" id="db_host" name="db_host" placeholder="Ingresar host..." required>
        </div>
        <div class="col-sm form-group">
            <label for="db_usuario">Usuario</label>
            <input type="text" class="form-control" value="{{old('db_usuario')}}" id="db_usuario" name="db_usuario" placeholder="Ingresar usuario..." autocomplete="off" required>
        </div>
        <div class="col-sm form-group">
            <label for="db_contrasenia">Contraseña</label>
            <input type="password" class="form-control" value="{{old('db_contrasenia')}}" id="db_contrasenia" name="db_contrasenia" placeholder="Contraseña" autocomplete="new-password" required>
        </div>
        <div class="col-sm-2 form-group form-host-col-submit">
            <button type="submit" class="btn btn-success">Seleccionar</button>
        </div>
    </div>
</form>

<div class="container" style="margin-top:20px" align="center">
    <a href="#modal-form-bases" class="btn btn-primary" data-toggle="modal" data-target="#modal-form-bases">Crear nuevo servidor</a>
</div>

@include('forms.form_bases')

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('#db_host').on('input', function (){
            var db_host = $(this).val();
            $.ajax({
                type:"GET",
                url:"{{url('verificar_host')}}?servidor_bases_selected="+db_host,
                success:function(res){
                    if(res){
                        $('#db_usuario').val(res[0]['usuario']);
                        $('#db_contrasenia').val(res[0]['password']);
                    }
                }
            });
        });
    });

</script>

 @endsection
