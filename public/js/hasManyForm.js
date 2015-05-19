(function($){

  $.widget('noir.hasManyForm', {
    options : {
      defaultForm : undefined,
      addButton : '.add-button',
      dismissButton : '.btn-remove',
      container : '.form-group',
      minLengthToShow : 1,
      names : {},
      defaults: {}
    },

    _create : function(){
      if(!this.options.defaultForm) throw 'Default form required';

      var mainContainer = this.element;
      mainContainer.hide();

      var settings = this.options;
      var object = this;
      $(this.options.addButton).on('click', function(){
        object.appendItem();
      });

      $(this.element).on('click', this.options.dismissButton, function(){
        console.log(mainContainer.find(settings.container + ':not(' + settings.defaultForm + ')'));
        $(this).parents(settings.container).hide(500, function(){
          if($(this).attr('id') == undefined)
            $(this).remove();
          if(mainContainer.find(settings.container + ':not(' + settings.defaultForm + ')').length < settings.minLengthToShow)
            mainContainer.hide(1000);

          });
        });
    },

    appendItem : function(object){
      console.log('appending item to ', this);
      var mainContainer = this.element;
      if(!mainContainer.is(':visible')){
        mainContainer.show(300);
        animateLength = 0;
      }else{
        animateLength = 600;
      }
      var subform = $(this.options.defaultForm).clone().removeAttr('id');
      console.log(subform);
      $.each(this.options.names, function(key, value){
        subform.find(key).attr('name', value);
      });
      mainContainer.append(subform);
      subform.show(animateLength);
      if(object){
        console.log(object);
        $.each(this.options.names, function(key, value){
          var input = subform.find(key)
          input.val(object[value]);
          console.log(object.key, key);
          if(object.key == value){

            input.prop( "disabled", true );
          }
        });
      }
    }
  });

}(jQuery));
