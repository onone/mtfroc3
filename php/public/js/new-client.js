$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.ajaxOptions = {
    type: "PUT",
    dataType: 'json'
    };
$.fn.editable.defaults.params = function(params) {
    //originally params contain pk, name and value
    params._METHOD = 'PUT';
    return params;
};
        
var NewEntity = function () {
    return {
        //main function to initiate template pages
        init: function () {
            $('.myeditable').editable({
                url: BASE_URL + '/resources/client' //this url will not be used for creating new user, it is only for update
            });
             
            //make username required
            /*$('#new_username').editable('option', 'validate', function(v) {
                if(!v) return 'Required field!';
            });*/
             
            //automatically show next editable
            $('.myeditable').on('save.newuser', function(){
                var that = this;
                setTimeout(function() {
                    $(that).closest('tr').next().find('.myeditable').editable('show');
                }, 200);
            });
            
            
            $('#save-btn').click(function() {
               $('.myeditable').editable('submit', { 
                   url: BASE_URL + '/resources/client',
                   ajaxOptions: {
                       type: "POST",
                       dataType: 'json' //assuming json response
                   },           
                   success: function(data, config) {
                       if(data && data.id) {  //record created, response like {"id": 2}
                           //set pk
                           $(this).editable('option', 'pk', data.id);
                           //remove unsaved class
                           $(this).removeClass('editable-unsaved');
                           //show messages
                           var msg = 'New user created! Now editables submit individually.';
                           $('#msg').addClass('alert-success').removeClass('alert-error').removeClass('hide').html(msg).show();
                           $('#save-btn').hide(); 
                           $(this).off('save.newuser');                     
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
                       $('#msg').removeClass('alert-success').addClass('alert-error').html(msg).show();
                   }
               });
            });
            
            $('#reset-btn').click(function() {
                $('.myeditable').editable('setValue', null)  //clear values
                    .editable('option', 'pk', null)          //clear pk
                    .removeClass('editable-unsaved');        //remove bold css
                               
                $('#save-btn').show();
                $('#msg').hide();                
            });
        }
    };
}();