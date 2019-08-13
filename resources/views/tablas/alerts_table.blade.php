<div class="container" style="max-width:600px;margin:2px auto 0px auto">
    @if(session()->get('registro_agregado'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width:600px;margin:5px auto 10px auto" align="center">
          <strong>{{ session()->get('registro_agregado') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    @elseif(session()->get('registro_actualizado'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width:600px;margin:5px auto 10px auto" align="center">
          <strong>{{ session()->get('registro_actualizado') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    @elseif(session()->get('registro_eliminado'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width:600px;margin:5px auto 10px auto" align="center">
          <strong>{{ session()->get('registro_eliminado') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    @elseif(session()->get('registro_no_modificado'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width:600px;margin:5px auto 10px auto" align="center">
          <strong>{{ session()->get('registro_no_modificado') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    @endif
</div>