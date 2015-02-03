
(function($){

  $.fn.advancedStepper = function(){

    $(this).each(function(){
      var element = $(this);

      var reset = function(){
        console.log('resetting', element);
        $(element).find('.step-div').hide();
        $('.start-div').show();
        $(element).find('input[type="radio"]').prop('checked', false);
      };

      var submitStepper = function(){
        $(element).on('hidden.bs.modal', function(e){
          $(e.currentTarget).unbind('hidden.bs.modal'); // or $(this)
          //$('#submit-success-modal').modal('show');
          $(element).find('form').submit();
        });

        $(element).modal('hide');

      };

      $(element).on('show.bs.modal', reset);

      $(element).find('input[type="radio"]').change(function(){
        var val = $(this).val();

        var div = $(this).parents('.step-div');


        var nextSelector = '.step-div';
        if($(this).attr('data-next-div')){
          var nextDiv = $(this).attr('data-next-div');
          if(nextDiv == 'submit'){
            submitStepper();
            return;
          }else if(nextDiv == 'disabled'){
            return;
          }
          nextSelector = nextSelector + '.' + nextDiv;

          var next = div.siblings(nextSelector);

        }else{
          var next = $(div).next(nextSelector);

        }

        if(next.length == 0){
          submitStepper();
        }else{
          div.hide(200);
          next.show(200);
        }

      });

      $(element).find('form button[type="button"]').click(function(){
        var div = $(this).parents('.step-div');

        var nextSelector = '.step-div';
        if($(this).attr('data-next-div')){
          var nextDiv = $(this).attr('data-next-div');
          if(nextDiv == 'submit'){
            submitStepper();
            return;
          }
          nextSelector = nextSelector + '.' + nextDiv;

          var next = div.siblings(nextSelector);

        }else{
          var next = $(div).next(nextSelector);

        }


        if(next.length == 0){
          submitStepper();
        }else{
          div.hide(200);
          next.show(200);
        }
      });
    });


  }

}(jQuery));
