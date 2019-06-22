<div class="container" style="max-width:600px;margin:2px auto 0px auto">
    @if(session()->get('registro_agregado'))
        <div class="alert alert-success" style="text-align:center">
            <b>{{ session()->get('registro_agregado') }}</b>
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-success" value="x" onclick="javascript:window.location.reload();"/>
        </div>
    @elseif(session()->get('registro_actualizado'))
        <div class="alert alert-success" style="text-align:center">
            <b>{{ session()->get('registro_actualizado') }}</b>
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-success" value="x" onclick="javascript:window.location.reload();"/>
        </div>
    @elseif(session()->get('registro_eliminado'))
        <div class="alert alert-success" style="text-align:center">
            <b>{{ session()->get('registro_eliminado') }}</b>
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-success" value="x" onclick="javascript:window.location.reload();"/>
        </div>
    @elseif(session()->get('registro_no_modificado'))
        <div class="alert alert-warning" style="text-align:center">
            <b>{{ session()->get('registro_no_modificado') }}</b>
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-warning" value="x" onclick="javascript:window.location.reload();"/>
        </div>
    @endif
    @if(session()->get('mensaje_error'))
        <div class="alert alert-danger" style="text-align:center">
            <b>{{ session()->get('mensaje_error') }}</b>
            &nbsp;
            &nbsp;
            <input type="button" class="btn btn-sm btn-danger" value="x" onclick="javascript:window.location.reload();"/>  
        </div>
    @endif
</div>