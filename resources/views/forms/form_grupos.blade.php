<div class="modal fade" id="modal-form-grupos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="{{route('create_grupo')}}" method="POST">
            @csrf
            <div class="modal-content color-modal-form-dark">
                <div class="modal-header">
                    <h4>Crear Grupo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body color-modal-form-pastel">
					<div class="row form-group">
                        <div class="col">
                            <label for="nombre_grupo">Nombre <small class="small-color">(*)</small>:</label>
						    <input type="text" class="form-control" name="nombre_grupo" id="nombre_grupo" required>
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
