  /*
  *Crea el span del inicio checa si estas en la primera pagina si es así no hace nada
  */
  function firstSpanCreate(object,orders_full){
    if(! (orders_full.current_page == 1)){
      var page = parseInt(orders_full.current_page) - 1;
      object.append('<li><a data-page="' +page+'" class="pagina"><span>&laquo;</span></a></li>')
    }
  }

  /*
  *Crea el span del final checa si estas en la ultima pagina si es así no hace nada
  */
  function lastSpanCreate(object,orders_full){
    if(! (orders_full.current_page == orders_full.last_page))
        object.append('<li><a data-page="' +(orders_full.current_page+1)+'" class="pagina"><span>&raquo;</span></a> </li>');
  }
  /*
  *Agrega al objeto el numero de links de listas señaladas en numero, marca la pagina activa como class="active"
  */
  function listsCreate(object,orders_full,from,until){
    for (var i = from; i < until; i++) {
      if(i == orders_full.current_page){
        object.append('<li class="active"><a data-page="' +i+'" role="button" class="pagina"><span>' +i+'</span></a></li>');  
      }else{
        object.append('<li><a role="button" data-page="' +i+'" class="pagina">'+i+'</a></li>'); 
      }
    };
  }

  /**
  *Crea un span con ...  al objecto para la mitad de la lista en caso de que esta sea muy larga
  */
  function spanPointsCreate(object){
    object.append('<li class="disabled"><span>...</span></li><li>');
  }


function  messages_update(type){

  $('#message-modal .modal-body table').empty();
  $('#message-modal .modal-body table').empty();

  $.get('/api/messages/',{active_tab_message: type},function(data){

    var messages = jQuery.parseJSON(data.messages);
    var datos = messages.data;

    if(datos.length == 0){
          $('#message-table tbody').append(
            $('<tr>').attr('class', 'warning').append(
              $('<td>').html('<strong>No hay registros que mostrar</strong>')
            )
          );
          $('.btn-submit').prop('disabled', true);
          $('#pagination').empty();
          return;
    }

    var table = $('#message-table').clone();


    table.removeAttr('id');
    $('#message-table').remove();
    table.attr('id','message-table');
    $('#pagination').empty();

    if(type == 'enviados'){
      $('#recibidos').parent().removeClass('active');
      $('#enviados').parent().addClass('active');
    }else{
      $('#enviados').parent().removeClass('active');
      $('#recibidos').parent().addClass('active');
    }
    
    table.append('<thead><th>CCOSTOS</th><th>MENSAJE</th></thead>');
    table.append('<tbody>');

    for (var i = datos.length - 1; i >= 0; i--) {
      
      table.append('<tr><td>'+datos[i].ccosto+'</td> <td>' + datos[i].body +'</td></tr>');
    
      $('#message-modal .modal-body').append(table);
      
    };
          firstSpanCreate($('#pagination'),orders_full);
          
          if(orders_full.total > 100){
            if(orders_full.current_page > 8 && orders_full.current_page < orders_full.last_page - 2){
                if(orders_full.current_page+1 == orders_full.last_page - 3){
                  spanPointsCreate($('#pagination'));
                  listsCreate($('#pagination'),orders_full,orders_full.current_page-7,orders_full.last_page+1);            
                }else{
                  listsCreate($('#pagination'),orders_full,orders_full.current_page-7,orders_full.current_page+1);            
                  spanPointsCreate($('#pagination'));
                  listsCreate($('#pagination'),orders_full,orders_full.last_page - 2,orders_full.last_page+1);      
                }
            }else{
              listsCreate($('#pagination'),orders_full,1,9);
              spanPointsCreate($('#pagination'));
              listsCreate($('#pagination'),orders_full,orders_full.last_page - 2,orders_full.last_page+1);  
            }
          }else{
              listsCreate($('#pagination'),orders_full,1,orders_full.last_page+1);      
          }
           lastSpanCreate($('#pagination'),orders_full);

    table.append('</tbody>');

  });
  var modal =  $('#message-modal').modal();
} 