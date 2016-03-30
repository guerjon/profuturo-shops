  /*
  *Crea el span del inicio checa si estas en la primera pagina si es así no hace nada
  */
  function firstSpanCreate(object,messages){
    if(! (messages.current_page == 1)){
      var page = parseInt(messages.current_page) - 1;
      object.append('<li><a data-page="' +page+'" class="pagina"><span>&laquo;</span></a></li>')
    }
  }

  /*
  *Crea el span del final checa si estas en la ultima pagina si es así no hace nada
  */
  function lastSpanCreate(object,messages){
    if(! (messages.current_page == messages.last_page))
        object.append('<li><a data-page="' +(messages.current_page+1)+'" class="pagina"><span>&raquo;</span></a> </li>');
  }
  /*
  *Agrega al objeto el numero de links de listas señaladas en numero, marca la pagina activa como class="active"
  */
  function listsCreate(object,messages,from,until){
    for (var i = from; i < until; i++) {
      if(i == messages.current_page){
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


function  messages_update(type,page){

  $('#message-modal .modal-body table').empty();
  $('#message-modal .modal-body table').empty();

  $.get('/api/messages/',{active_tab_message:type,page:page},function(data){

    var messages = jQuery.parseJSON(data.messages);
    var datos = messages.data;  


    if(datos.length == 0){
      console.log(datos.length)
          $('#message-modal modal-body').append(
            '<div  class="alert alert-info">Aun no hay mensajes disponibles.</div>'
          );
          $('.btn-submit').prop('disabled', true);
          $('#pagination_pagination_message').empty();
          return;
    }

    var table = $('#message-table').clone();


    table.removeAttr('id');
    $('#message-table').remove();
    table.attr('id','message-table');
    $('#pagination_pagination_message').empty();

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
          firstSpanCreate($('#pagination_pagination_message'),messages);
          
          if(messages.total > 100){
            if(messages.current_page > 8 && messages.current_page < messages.last_page - 2){
                if(messages.current_page+1 == messages.last_page - 3){
                  spanPointsCreate($('#pagination_pagination_message'));
                  listsCreate($('#pagination_pagination_message'),messages,messages.current_page-7,messages.last_page+1);            
                }else{
                  listsCreate($('#pagination_pagination_message'),messages,messages.current_page-7,messages.current_page+1);            
                  spanPointsCreate($('#pagination_pagination_message'));
                  listsCreate($('#pagination_pagination_message'),messages,messages.last_page - 2,messages.last_page+1);      
                }
            }else{
              listsCreate($('#pagination_pagination_message'),messages,1,9);
              spanPointsCreate($('#pagination_pagination_message'));
              listsCreate($('#pagination_pagination_message'),messages,messages.last_page - 2,messages.last_page+1);  
            }
          }else{
              listsCreate($('#pagination_pagination_message'),messages,1,messages.last_page+1);      
          }
           lastSpanCreate($('#pagination_pagination_message'),messages);

    table.append('</tbody>');

  });
  var modal =  $('#message-modal').modal();
} 