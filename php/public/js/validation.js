var ValidationFunction = function () {
    return {
        //main function to initiate template pages
        required: function (v) {
            if(!v) return 'Required field!';
        },
        mixed: function (functionNames,v) {
            var that = this;
            var msg = '';
            $.each(functionNames.split(','), function( index, fname ) {
                fname = fname.trim();
                if(typeof that[fname] === 'function'){
                    var r =  that[fname](v);
                    if(r){ msg += ' - ' + r; }
                }
            });
            if(msg !== ''){ return msg; }
            
        }
    }
 }();