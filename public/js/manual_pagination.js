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