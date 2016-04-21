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

  function changeTab(type){
    if(type == 'enviados'){
        $('#recibidos').parent().removeClass('active');
        $('#enviados').parent().addClass('active');
      }else{
        $('#enviados').parent().removeClass('active');
        $('#recibidos').parent().addClass('active');
      }
  }

  /**
    *Actualiza los mensajes dentro de la venta modal
  */
  function  messages_update(type){

    $('#message-modal .modal-body table').empty();
    $('#message-modal .modal-body table').empty();

    $.get('/api/messages/',{active_tab_message:type},function(data){
      $('#message-modal .modal-body .alert-info').remove();
      var messages = jQuery.parseJSON(data.messages);
      var datos = messages.data;
      
      
      if(datos.length == 0){
            
            $('#message-modal .modal-body').append(
              '<div  class="alert alert-info">Aun no hay mensajes disponibles.</div>'
            );

        return;
      }

      var table = $('#message-table').clone();

      table.removeAttr('id');
      $('#message-table').remove();
      table.attr('id','message-table');
      $('#pagination_pagination_message').empty();

    
      table.append('<thead>' +
                      '<th style="border-right: 0px;border-left:0px">GERENCIA</th>' +
                      '<th style="border-right: 0px;border-left:0px">CCOSTO</th>' +
                      '<th style="border-right: 0px;border-left:0px">MENSAJE</th>' +
                      '<th style="border-right: 0px;border-left:0px">FECHA</th>' +
                      '<th style="border-left: 0px;"></th>' +
                   '</thead>');
      table.append('<tbody>');
     
      for (var i = datos.length - 1; i >= 0; i--) {

        if(datos[i].ccosto == 1){

          table.append('<tr class="message_row"> '+
                            '<td style="border-right:0px;">'+
                              datos[i].gerencia+
                            '</td>' +
                            '<td style="border-right: 0px;border-left:0px;">'+
                              'Administrador'+
                           '</td>' +
                           '<td style="border-right: 0px;border-left:0px;"><h5>' +
                            datos[i].body+
                           '</h5></td>' +
                          '<td style="border-right:0px;border-left:0px;">'+
                            datos[i].created_at+
                          '</td>' +
                          '<td width="20" style="border-left: 0px;border-left:0px;">'+
                          '<span data-id="' + datos[i].id +'" data-ccosto="' + datos[i].ccosto +'"  style="visibility:hidden" class="glyphicon glyphicon-share-alt fast-actions ">'+
                              '<div class="arrow"></div>'+
                              '<span>Responder</span>' +
                          '</span>' +
                          '<span style="visibility:hidden" class="glyphicon glyphicon-record fast-actions ">'+
                            '<div class="arrow"></div>'+
                            '<span>Marcar como no leido</span>'+
                          '</span>' +
                        '</tr>');
        }else{

          table.append('<tr class="message_row"> '+
                          '<td style="border-right:0px;">'+
                            datos[i].gerencia+
                          '</td>' +
                          '<td style="border-right: 0px;border-left:0px;">'+
                            datos[i].ccosto+
                         '</td>' +
                         '<td style="border-right: 0px;border-left:0px;">' +
                          datos[i].body+
                         '</td>' +
                        '<td style="border-right:0px;border-left:0px;">'+
                          datos[i].created_at+
                        '</td>' +
                        '<td width="20" style="border-left: 0px;border-left:0px;">'+ 
                        '<span data-id="' + datos[i].id +'" data-ccosto="' + datos[i].ccosto +'"  style="visibility:hidden" class="glyphicon glyphicon-share-alt fast-actions ">'+
                            '<div class="arrow"></div>'+
                            '<span>Responder</span>' +
                        '</span>' +
                        '<span style="visibility:hidden" class="glyphicon glyphicon-record fast-actions ">'+
                          '<div class="arrow"></div>'+
                          '<span>Marcar como no leido</span>'+
                        '</span>' +
                      '</tr>');
        }
        
      
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

    $(document).on('click','.fast-actions',function(){
      $('#message-modal').modal('toggle');
      $('#select_users_modal').modal();
      var ccostos = $(this).attr('data-ccosto');
      var id = $(this).attr('data-id');

      $('#search-ccostos').val(id).select2();

    });

    $(document).on('mouseover','.message_row',function(){
        $(this).addClass('active');
        $(this).find('.fast-actions').css('visibility','visible');

    });
    $(document).on('mouseleave','.message_row',function(){
        $(this).removeClass('active');
        $(this).find('.fast-actions').css('visibility','hidden');
    });

    $(document).on('click','#post-message-modal-button',function(){
      $('#post-message-modal-form').submit();
    });
  }

  


  