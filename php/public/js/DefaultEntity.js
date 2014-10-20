$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.ajaxOptions = {
    type: "PUT",
    dataType: 'json'
    };

var DefaultEntity = function () {
    return {
        //main function to initiate template pages
        init: function (EC) {
            
            /*
            
            select2: {
            placeholder: 'Select Country',
            minimumInputLength: 1
        }
            
            */
            var eo = {
                url: urlFor(urlTmpls,'entityResource',{entityName : EC.entityName}), //this url will not be used for creating new user, it is only for update
                params: function(params) {
                    //originally params contain pk, name and value
                    var toDelete = false;
                    if(params.name != 'name') toDelete = true;
                    params[params.name] = params.value;
                    
                    if(toDelete) delete params.name;
                    delete params.value;
                    
                    if(typeof params.pk  !=  "undefined"){
                        params._METHOD = 'PUT';
                    }
                    
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
                },
                error: function(response){
                    console.log(this);
                    console.log(response);
                },
                combodate: {
                    minYear: 1900,
                    maxYear: 2020,
                    minuteStep: 1
               }
            };
            
            
            $('.' + EC.entityName + 'Editable').each(function( index ) {
                    var eoCustom = eo;
                    var that = $(this);
                    if(typeof that.data('jsoptions') != "undefined") {
                        $.extend(eoCustom, EC.fields[that.data('name')].customConfig.xeditable.jsoptions);
                    }
                    that.editable(eoCustom);
            })
            
             
             // VALIDAZIONE
            // Esempio di partenza
            /*$('#' + EC.entityName + '_name').editable('option', 'validate', function(v) {
                if(!v) return 'Required field!';
            });*/
            if(EC.hasOwnProperty('fields')){ // http://jsfiddle.net/H5f3V/2/
                $.each( EC.fields, function( fieldName, fieldConfig ) {
                        if(fieldConfig.hasOwnProperty('customConfig')){
                            if(fieldConfig.customConfig.hasOwnProperty('xeditable')){
                                
                                var fieldXEConfig = fieldConfig.customConfig.xeditable;
                                
                                if(fieldXEConfig.hasOwnProperty('validationFunctionNames')){
                                    $('#' + EC.entityName + '_' + fieldName).editable('option', 'validate', function(v) {
                                        return eval('ValidationFunction.mixed("' + fieldXEConfig.validationFunctionNames + '",v);');
                                    });
                                    
                                }
                            }
                        }
                });
            }
             
             
            // ----------------------------------------------------------------------------------------
            //automatically show next editable
            /*$('.' + EC.entityName + 'Editable').on('save.newEntity', function(){
                var that = this;
                setTimeout(function() {
                    $(that).closest('tr').next().find('.' + EC.entityName + 'Editable').editable('show');
                }, 200);
                
            });*/
            
            // ----------------------------------------------------------------------------------------
            // enable / disable
            $('#' +  EC.entityName + 'EntityEnable').click(function() {
                $('.' + EC.entityName + 'Editable').editable('toggleDisabled');
            }); 
               
                
            // ----------------------------------------------------------------------------------------
            // open next/editable
            $('.' + EC.entityName + 'Editable').on('hidden', function(e, reason){
                if(reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable');
                    if($('#' +  EC.entityName + 'EntityAutoopen').is(':checked')) {
                        setTimeout(function() {
                            $next.editable('show');
                        }, 300); 
                    } else {
                        $next.focus();
                    } 
                }
            });
            
            $('#' +  EC.entityName + 'EntitySave-btn').click(function() {
               $('.' + EC.entityName + 'Editable').editable('submit', { 
                   url: urlFor(urlTmpls,'entityResource',{entityName : EC.entityName}),
                   ajaxOptions: {
                       type: "POST",
                       dataType: 'json' //assuming json response
                   },           
                   success: function(data, config) {
                       
                       if(data && data.id) {  //record created, response like {"id": 2}
                          //window.location.replace(urlFor(urlTmpls,'entityUI',{entityName : EC.entityName, pk: data.id}));
                            
                            
                           $('table#' + EC.entityName + ' .notEditable').each(function( index ) {
                               that = $(this);
                               if(typeof data[that.data('name')] != "undefined"){
                                   that.html(data[that.data('name')]);
                               }
                            });
                            
                           //set pk
                           $(this).editable('option', 'pk', data.id);
                           //remove unsaved class
                           $(this).removeClass('editable-unsaved');
                           //show messages
                           var msg = 'Nuovo ' + EC.singular_label + ' creato! Ora i campi sono editabili individualmente.';
                           if(EC.entityName == 'client'){
                               window.location = urlFor(urlTmpls,'clientCustom',{pk : data.id });
                                //msg += '<a href="' + urlFor(urlTmpls,'clientCustom',{pk : data.id }) + '" class="btn btn-default">Vai pagina ' + EC.singular_label + '</a>';
                           }else{
                            msg += '<a href="' + urlFor(urlTmpls,'entityUI',{entityName : EC.entityName, pk : data.id }) + '" class="btn btn-default">Vai pagina ' + EC.singular_label + '</a>';
                           }
                           $('#' +  EC.entityName + 'EntityMsg').addClass('alert-success').removeClass('alert-danger').removeClass('hide').html(msg).show();
                           $('#' +  EC.entityName + 'EntitySave-btn').hide(); 
                           $('#' +  EC.entityName + 'EntityDelete').data('pk',data.id);
                           $('#' +  EC.entityName + 'EntityDelete').show();
                            $('#' +  EC.entityName + 'EntityDelete').removeClass('hide');
                            $('#' +  EC.entityName + 'EntityEnable').removeClass('hide');
                           //$(this).off('save.newEntity');   
                           
                           $( "body" ).trigger( "entityInsert", [ EC.entityName,  data ] );
                           
                           var dataTable = $('#' +  EC.entityName + 'EntitiesTable');
                           if(dataTable.length > 0){
                               var tableFields = [];

                                $.each(EC.fields, function( fieldName, fieldConfig ) {
                                    if($('#' + EC.entityName + fieldName).length > 0) {
                                    
                                        if(!fieldConfig.hasOwnProperty('visible')){
                                            var ERepres = fieldName.replace('_id','');
                                            var t = '';
                                            var fn = fieldName;
                                            if(typeof data[ERepres] != "undefined"){
                                                fn = ERepres;
                                            }
                                            //urlFor(urlTmpls,'entityListUI',{entityName : EC.entityName})
                                            
                                            if(typeof data[fn] != "undefined"){
                                                
                                                t = data[fn];
                                                
                                                if(fn != fieldName){
                                                    //urlFor('entityUI',{"entityName": fieldDBName|replace({"_id":""}), "pk": fieldValue})
                                                    t = t + ' <a class="relatedShow btn btn-xs btn-purple" href="' + urlFor(urlTmpls,'entityUI',{entityName : fn, pk : data.fieldName }) + '" style="float:right">Apri <i class="fa fa-arrow-circle-right"></i></a>';
                                                }
                                                tableFields.push(t);
                                            }
                                        }
                                    }
                                });
                                
                                
                                tableFields.push('<div class="btn-group pull-right"><a href="' + urlFor('entityUI', {entityName: EC.entityName, pk: data.id  }) + '" class="btn btn-warning"><i class="clip-pencil-3"></i></a>			        <button  class="btn btn-bricky deleteEntityFromList" data-deleteurl=""><i class="fa fa-trash-o fa-white"></i></button>			    </div>');
                                
                                dataTable.dataTable().fnAddData(tableFields);
                           }
                           
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
                       $('#' +  EC.entityName + 'EntityMsg').removeClass('alert-success').removeClass('hide').addClass('alert-danger').html(msg).show();
                   }
               });
            });
            
            $('#' +  EC.entityName + 'EntityDelete').click(function() {
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
                          window.location.replace(urlFor(urlTmpls,'entityListUI',{entityName : EC.entityName}));
                      }
                    });
                  }
            });

        }
    };
}();