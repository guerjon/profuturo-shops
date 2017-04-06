<div class="modal fade" id="pending-general-requests">
    <div class="modal-dialog modal-lg">
        
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Solicitudes pendientes próximas a vencer
                </h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped" id="pending-requests-table">
                    <thead>
                        <tr>
                            <th>
                                ID de Solicitud
                            </th>
                            <th>
                                Título proyecto
                            </th>
                            <th>
                                Estatus
                            </th>
                            <th>
                                Presupuesto
                            </th>
                            <th>
                                Fecha de solicitud
                            </th>
                            <th>
                                Fecha de entrega
                            </th>
                            <th>
                                Línea de negocio
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    Cerrar
                </button>
                @if(Auth::user()->role == 'admin')
                    <a href="{{action('AdminGeneralRequestsController@index')}}" class="btn btn-primary">
                        Ir a solicitudes generales
                    </a>
                @else
                    <a href="{{action('UserRequestsController@index')}}" class="btn btn-primary">
                        Ir a solicitudes generales
                    </a>
                @endif
                
            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        $(function() {
            var action = '/api/expiring-general-requests';
            $.get(action, function(data){
                if(Array.isArray(data) && data.length > 0) {
                    var keys = [
                        'id',
                        'project_title',
                        'status_str',
                        'total',
                        'created_at',
                        'deliver_date',
                        'linea_negocio'
                    ];
                    for(var i=0; i<data.length; i++) {
                        var request = data[i];
                        var tr = $('<tr>');
                        for(var j=0; j<keys.length; j++) {
                            var key = keys[j];
                            var td;
                            switch(key) {
                                case 'created_at':
                                case 'deliver_date':
                                    td = $('<td>').text(request[key].substr(0, 10));
                                    break;
                                default :
                                    td = $('<td>').text(request[key]);
                            }
                            tr.append(td);
                        }
                        $('#pending-requests-table tbody').append(tr);
                    }
                    $('#pending-general-requests').modal('show');
                }
            }) ;
        });
    </script>
@endsection