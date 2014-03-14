$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.ajaxOptions = {
    type: "PUT",
    dataType: 'json'
    };

        



        
var DefaultEntity = function () {
    return {
        //main function to initiate template pages
        init: function (initParams) {
            $('.' + initParams.editableCSSClass).editable({
                url: BASE_URL + '/resources/' + initParams.entityName, //this url will not be used for creating new user, it is only for update
                params: function(params) {
                    //originally params contain pk, name and value
                    var toDelete = false;
                    if(params.name != 'name') toDelete = true;
                    params[params.name] = params.value;
                    
                    if(toDelete) delete params.name;
                    delete params.value;
                    
                    params._METHOD = 'PUT';
                    
                    return params;
                },
                success: function(response, newValue) {
                    var s = $(this).siblings('.relatedShow');
                    if(s.length > 0){
                    var n = $(s[0]);
                        if(typeof n != "undefined"){
                            var u = n.data('entityurl');
                            n.attr('href',u.replace("[[ID]]",newValue)).removeClass('hide');
                        }
                    }
                }
            });
             
             
             // VALIDAZIONE
            // Esempio di partenza
            /*$('#' + initParams.entityName + '_name').editable('option', 'validate', function(v) {
                if(!v) return 'Required field!';
            });*/
            if(initParams.hasOwnProperty('fields')){ // http://jsfiddle.net/H5f3V/2/
                $.each( initParams.fields, function( fieldName, fieldConfig ) {
                        if(fieldConfig.hasOwnProperty('customConfig')){
                            if(fieldConfig.customConfig.hasOwnProperty('xeditable')){
                                
                                var fieldXEConfig = fieldConfig.customConfig.xeditable;
                                
                                if(fieldXEConfig.hasOwnProperty('validationFunctionNames')){
                                    $('#' + initParams.entityName + '_' + fieldName).editable('option', 'validate', function(v) {
                                        return eval('ValidationFunction.mixed("' + fieldXEConfig.validationFunctionNames + '",v);');
                                    });
                                    
                                }
                            }
                        }
                });
            }
             
             
            // ----------------------------------------------------------------------------------------
            //automatically show next editable
            /*$('.' + initParams.editableCSSClass).on('save.newEntity', function(){
                var that = this;
                setTimeout(function() {
                    $(that).closest('tr').next().find('.' + initParams.editableCSSClass).editable('show');
                }, 200);
                
            });*/
            
            // ----------------------------------------------------------------------------------------
            // enable / disable
            $('#DefaultEntityEnable').click(function() {
                $('.' + initParams.editableCSSClass).editable('toggleDisabled');
            }); 
               
                
            // ----------------------------------------------------------------------------------------
            // open next/editable
            $('.' + initParams.editableCSSClass).on('hidden', function(e, reason){
                if(reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable');
                    if($('#DefaultEntityAutoopen').is(':checked')) {
                        setTimeout(function() {
                            $next.editable('show');
                        }, 300); 
                    } else {
                        $next.focus();
                    } 
                }
            });
            
            $('#DefaultEntitySave-btn').click(function() {
               $('.' + initParams.editableCSSClass).editable('submit', { 
                   url: BASE_URL + '/resources/' + initParams.entityName,
                   ajaxOptions: {
                       type: "POST",
                       dataType: 'json' //assuming json response
                   },           
                   success: function(data, config) {
                       
                       if(data && data.id) {  //record created, response like {"id": 2}
                       
                           $('table#' + initParams.entityName + ' .notEditable').each(function( index ) {
                               that = $(this);
                               if(typeof data[that.data('name')] != "undefined"){
                                   that.html(data[that.data('name')]);
                               }
                            });
                            
                            $('#DefaultEntityDelete').removeClass('hide');
                            
                            
                    
                           //set pk
                           $(this).editable('option', 'pk', data.id);
                           //remove unsaved class
                           $(this).removeClass('editable-unsaved');
                           //show messages
                           var msg = 'Nuovo ' + initParams.entitySingularLabel + ' creato! Ora i campi sono editabili individualmente.';
                           $('#DefaultEntityMsg').addClass('alert-success').removeClass('alert-danger').removeClass('hide').html(msg).show();
                           $('#DefaultEntitySave-btn').hide(); 
                           $('#DefaultEntityDelete').data('pk',data.id);
                           $('#DefaultEntityDelete').show();
                           //$(this).off('save.newEntity');   
                           
                           
                       } else if(data && data.errors){ 
                           //server-side validation error, response like {"errors": {"username": "username already exist"} }
                           config.error.call(this, data.errors);
                       }
                   },
                   error: function(errors) {
                       var msg = '';
                       if(errors && errors.responseText) { //ajax error, errors = xhr object
                           msg = errors.responseText;
                       } else { //validation error (client-side or server-side)
                           $.each(errors, function(k, v) { msg += k+": "+v+"<br>"; });
                       } 
                       $('#DefaultEntityMsg').removeClass('alert-success').removeClass('hide').addClass('alert-danger').html(msg).show();
                   }
               });
            });
            
            $('#DefaultEntityDelete').click(function() {
                var r = confirm("Sei sicuro di voler rimuovere questa entità?");
                if (r == true)
                  {
                    var t = $(this);
                    var url = t.data('deleteurl');
                    if(url.indexOf("/new")){
                        url = url.replace("/new","/" + t.data('pk'));
                    }
                  $.ajax({
                      type: "POST",
                      url: url,
                      data: { _METHOD: "DELETE" },
                      success: function(data){
                          window.location.replace(initParams.entityListUIUrl);
                      }
                    });
                  }
            });

        }
    };
}();