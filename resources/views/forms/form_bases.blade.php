<div class="modal fade" id="modal-form-bases" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="{{route('create_base')}}" method="POST">
            @csrf
            <div class="modal-content color-modal-form-dark">
                <div class="modal-header">
                    <h4>Crear Servidor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body color-modal-form-pastel">
					<div class="row form-group">
                        <div class="col">
                            <label for="servidor_bases">Servidor <small class="small-color">(*)</small>:</label>
						    <input type="text" class="form-control" name="servidor_bases" id="servidor_bases" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label for="usuario_bases">Usuario <small class="small-color">(*)</small>:</label>
						    <input type="text" class="form-control" name="usuario_bases" id="usuario_bases" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label for="password_bases">Contraseña <small class="small-color">(*)</small>:</label>
						    <input type="text" class="form-control" name="password_bases" id="password_bases" required>
                        </div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
