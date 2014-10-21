


var CustomClient = function () {
    
    var eoP = {
                url: urlFor(urlTmpls,'entityResource',{entityName : 'performance'}), //this url will not be used for creating new user, it is only for update
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
                    var t = $(this);
                    if(t.data('name') == 'payment_id'){
                        var oldValue = t.data('value');
                        t.data('value',newValue);
                        t.parent().addClass("PP" + newValue);
                        PerformanceCustom.refreshPaymentData("PP" + newValue);
                        
                        if(oldValue){
                            t.parent().removeClass("PP" + oldValue);
                            PerformanceCustom.refreshPaymentData("PP" + oldValue);
                        }
                    }
                },
                error: function(response){
                    console.log(this);
                    console.log(response);
                }
            };
            
    var eoPay = {
        url: urlFor(urlTmpls,'entityResource',{entityName : 'payment'}), //this url will not be used for creating new user, it is only for update
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
            var t = $(this);
            t.data('value',newValue);
            if(t.data('name') == 'amount'){
                t.html(newValue);
            }
            PerformanceCustom.refreshPaymentData("PP" + response[0].id);

        },
        error: function(response){
            console.log(this);
            console.log(response);
        }
    };
    
    var initPerformanceEditable = function(){
         
        $('.performanceEditable:not(.editable)').each(function( index ) {
                    var eoCustom = eoP;
                    var that = $(this);
                    if(typeof that.data('jsoptions') != "undefined") {
                        $.extend(eoCustom, EC.fields[that.data('name')].customConfig.xeditable.jsoptions);
                    }
                    that.editable(eoCustom);
            })
            
    }
    
    
    var initPaymentEditable = function(){
         
            
        $('.paymentEditable:not(.editable)').each(function( index ) {
                    var eoCustom = eoPay;
                    $(this).editable(eoCustom);
            })
    }
    
    var initPerformanceDataTable = function () {
        /* -----------------------------------------------  PERFORMANCE ----------------------------------------------- */
            
            var tableId = 'performanceEntitiesTable';
            
            var oTable = $('#' + tableId).dataTable({
                "aaSorting": [
                    
                    //
                    [1, 'desc'],
                    //[0, "asc" ]
                    
                ],
                "aoColumns": [
                    null,//{ "sType": 'date-id' },
			        { "sType": 'date-performance' },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                ],
                "aoColumnDefs": [{
                    "aTargets": [0]
                }],
                "oLanguage": {
                    "sLengthMenu": "Show _MENU_ Rows",
                    "sSearch": "",
                    "oPaginate": {
                        "sPrevious": "",
                        "sNext": ""
                    }
                },
                "aLengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 10,
                "fnDrawCallback": function( oSettings ) {
                    PerformanceCustom.justRefreshed = new Array();
                    //console.log('fnDrawCallback');
                    initPerformanceEditable();
                    PerformanceCustom.init();
                    /*
                    $('.performanceEditable:not(.editable)').each(function( index ) {
                        var eoCustom = eoP;
                        var that = $(this);
                        if(typeof that.data('jsoptions') != "undefined") {
                            $.extend(eoCustom, EC.fields[that.data('name')].customConfig.xeditable.jsoptions);
                        }
                        that.editable(eoCustom);
                    })
                    */
                 },
                 "fnRowCallback": function(nRow, aaData, iDisplayIndex) {
                    //console.log('fnRowCallback');
                    PerformanceCustom.initTD($('td:eq(3)', nRow),aaData);
                    initPaymentTD($('td:eq(7)', nRow),aaData);
                    //console.log($('td:eq(7)', nRow));
                    //console.log(aaData);
                    return nRow;
                }
            });
            $('#' + tableId + '_wrapper .dataTables_filter input').addClass("form-control input-sm").attr("placeholder", "Search");
            // modify table search input
            $('#' + tableId + '_wrapper .dataTables_length select').addClass("m-wrap small");
            // modify table per page dropdown
            $('#' + tableId + '_wrapper .dataTables_length select').select2();
            // initialzie select2 dropdown
            $('#' + tableId + '_column_toggler input[type="checkbox"]').change(function () {
                /* Get the DataTables object again - this is not a recreation, just a get of the object */
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
            });
    };
    
    var initPaymentTD = function(td,data){
        var td = $(td);
        var value = td.find('a').data('value');
        if(value){
            td.addClass("PP" + value);
            PerformanceCustom.refreshPaymentData("PP" + value);
        }
        
    }
    
    var initPaymentDataTable = function () {
        
            /* -----------------------------------------------  PAGAMENTI ----------------------------------------------- */
            var tableId = 'paymentEntitiesTable';
            
            var oTable = $('#' + tableId).dataTable({
                "aoColumnDefs": [{
                    "aTargets": [0]
                }],
                "oLanguage": {
                    "sLengthMenu": "Show _MENU_ Rows",
                    "sSearch": "",
                    "oPaginate": {
                        "sPrevious": "",
                        "sNext": ""
                    }
                },
                "aaSorting": [
                    [0, 'desc'] //https://datatables.net/release-datatables/examples/advanced_init/sorting_control.html
                ],
                "aLengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 20,
                "fnDrawCallback": function( oSettings ) {
                    initPaymentEditable();
                 },
                 "fnRowCallback": function(nRow, aaData, iDisplayIndex) {
                    
                    //PerformanceCustom.initTD($('td:eq(0)', nRow),aaData);
                    var td = $('td:eq(0)', nRow);
                    td.parent().attr('id',"PP" + td.html().trim());
                    return nRow;
                }
            });
            $('#' + tableId + '_wrapper .dataTables_filter input').addClass("form-control input-sm").attr("placeholder", "Search");
            // modify table search input
            $('#' + tableId + '_wrapper .dataTables_length select').addClass("m-wrap small");
            // modify table per page dropdown
            $('#' + tableId + '_wrapper .dataTables_length select').select2();
            // initialzie select2 dropdown
            $('#' + tableId + '_column_toggler input[type="checkbox"]').change(function () {
                /* Get the DataTables object again - this is not a recreation, just a get of the object */
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
            });
            
    };
    var initMemoModal = function () {
        $.fn.modalmanager.defaults.resize = true;
        $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
            '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
            '<div class="progress progress-striped active">' +
            '<div class="progress-bar" style="width: 100%;"></div>' +
            '</div>' +
            '</div>';
        var $modal = $('#ajax-modal');
        $('.ajaxMemoModal').on('click', function () {
            // create the backdrop and wait for next modal to be triggered
            var that = $(this);
            $('body').modalmanager('loading');
            setTimeout(function () {
                $modal.load(that.data('url'),'', function () {
                    $modal.modal();
                    DefaultEntity.init(entitiesConfiguration[that.data('entityname')]);
                });
            }, 1000);
        });
    };
    var initGroupRateButtons = function () {
        $( ".group-rates" ).on('click', function() {
            var that = $(this);
                
            data =  { paymentgroup_nominal_number_of_performance: that.data('numperformance'),amount: that.data('amount'),client_id: that.data('clientid'),name: that.data('name')};
            insertPayment(data);

        });
    };
    
    var insertPerformance = function(performanceData,paymentString){
        $.ajax({
              type: "POST",
              url: urlFor(urlTmpls,'entityResource',{entityName : 'performance'}),
              data: performanceData,
              success: function(data){
                performanceInsertInTable(data,paymentString);
              }
        });
    };
    
    var insertPayment = function(paymentData){
        
        paymentData.paymentNoAutoGeneratePerformance = 1;
        
        
        
        $.ajax({
              type: "POST",
              url: urlFor(urlTmpls,'entityResource',{entityName : 'payment'}),
              data: paymentData,
              success: function(data){
                paymentInsertInTable(data);
                
                var paymentDetail = data.name + '  - Pagato:';
                  if(data.paymentstate_id == '1'){
                      paymentDetail += "Si";
                  }else{
                      paymentDetail += "No";
                  }
                paymentDetail += " - Importo : " + data.amount + " \u20AC - ";
                
                
                var np = parseInt(data.paymentgroup_nominal_number_of_performance);
                
                
                    if(np > 1) paymentDetail += "[NUMERO] di " + np;
                    
                if(np >= 1){
                  
                      var pd = data;
                      var paymentString = '';
                      var performanceData = { client_id: pd.client_id,payment_id: pd.id};
                      for (var i=1;i<=np;i++)
                        { 
                            paymentString = paymentDetail.replace("[NUMERO]",i);
                            insertPerformance(performanceData,paymentString);
                        }
                }
                
                
              }
        });
    };
    
    return {
        init: function () {
            initMemoModal();
            initGroupRateButtons();
            initPaymentDataTable();
            initPerformanceDataTable();
            initPerformanceEditable();
            initPaymentEditable();
        },
        insertPerformance:insertPerformance,
        insertPayment:insertPayment,
        initPerformanceEditable:initPerformanceEditable,
        initPaymentEditable:initPaymentEditable
        
    }
}();

$( "body" ).on( "entityInsert", function( event, entityName,  data ) {
  switch(entityName)
    {
    case 'memo':
        memoInsert(data);
        break;
    case 'anamnesis':
        anamnesisInsert (data);
        break;
    }

});

$('#addPerformance').on('click',function(){
    CustomClient.insertPerformance({ client_id: $('#client_id').data('pk')},'');
});
$('#addPayment').on('click',function(){
    var r = prompt("Quanti trattamenti ?", 1);
    r = parseInt(r);
    if (r==null || r == '') {
        r = 1;
    }
    var data = { client_id: $('#client_id').data('pk'), paymentgroup_nominal_number_of_performance: r};
    
    if(r > 1){
        data.name = 'Multi prestazione';
    }
    
    
    
    CustomClient.insertPayment(data);
});

function performanceInsertInTable(data,paymentDetail){
    var dataTable = $('#performanceEntitiesTable');
       if(dataTable.length > 0){
            var tableFields = [];
            tableFields.push(data.id);
            tableFields.push('<a href="#" data-emptytext="Vuoto" class="performanceEditable"   date-format="DD/MM/YYYY" data-template="DD/MM/YYYY" data-viewformat="DD/MM/YYYY" data-smartDays="true" id="performance_datetime" data-type="combodate" data-pk="' + data.id + '" data-name="datetime" data-original-title="Inserisci Data"></a>');
            tableFields.push('<a href="#" class="performanceEditable" data-emptytext="Vuoto" data-type="textarea" data-pk="' + data.id + '" data-name="pre_note">' + (data.pre_note?data.pre_note:'') + '</a>');
            tableFields.push('');
            tableFields.push('<a href="#" class="performanceEditable" data-emptytext="Vuoto" data-type="textarea" data-pk="' + data.id + '" data-name="post_note">' + (data.post_note?data.post_note:'') + '</a>');
            
            tableFields.push('<a href="#" class="performanceEditable" data-emptytext="Vuoto" data-type="number" data-pk="' + data.id + '" data-name="duration" data-original-title="Inserisci durata">' + (data.duration?data.duration:'') + '</a>');
            tableFields.push('<a href="#" data-emptytext="Vuoto" data-value="' + (typeof data.executed != "object"?data.executed:0) + '" data-source="' + "[{'text':'Si','value':1},{'text':'No','value':0}]"  + '" class="performanceEditable" id="performance_executed" data-type="select" data-pk="' + data.id + '" data-name="executed" data-original-title="Inserisci Eseguita">' + 
            '</a>');
            tableFields.push(' <a href="#" data-emptytext="Vuoto" data-value="' + (data.payment_id?data.payment_id:'') + '" data-source="' + BASE_URL  + '/resources/performancepaymentlist/' + (data.client_id?data.client_id:'')  + (data.payment_id?'/' + data.payment_id:'') + '" class="performanceEditable" id="performance_payment_id" data-type="select" data-pk="' + data.id + '" data-name="payment_id"></a>'); //paymentDetail

            tableFields.push('<a href="' + urlFor(urlTmpls,'entityUI',{entityName : 'performance',pk: data.id}) + '" class="btn btn-default"><i class="clip-pencil-3"></i></a>' + 
    '<button  class="btn btn-bricky deleteEntityFromList" data-deleteurl="' + urlFor(urlTmpls,'entityResource',{entityName : 'performance',pk: data.id}) + '"><i class="fa fa-trash-o fa-white"></i></button>'
    			);
    			
            dataTable.dataTable().fnAddData(tableFields);
            
            CustomClient.initPerformanceEditable();
       }
    
}


function paymentInsertInTable(data){
    var dataTable = $('#paymentEntitiesTable');
   if(dataTable.length > 0){
       var tableFields = [];
       var s = '';
       tableFields.push(data.id);
       tableFields.push('<a href="#" class="paymentEditable" data-emptytext="Vuoto"  data-type="text" data-pk="' + data.id + '" data-name="name">' + (data.name?data.name:'') + '</a>');
       //tableFields.push(data.name);
       tableFields.push('<a href="#" class="paymentEditable" data-emptytext="Vuoto"  data-type="number" data-pk="' + data.id + '" data-name="amount">' + (data.amount?data.amount:'') + '</a>');
       //tableFields.push(data.amount);
       tableFields.push('<a href="#" data-emptytext="Vuoto" data-value="' + data.paymentstate_id + '" data-source="' + "[{'text':'Si','value':1},{'text':'No','value':2}]"  + '" class="paymentEditable" data-type="select" data-pk="' + data.id + '" data-name="paymentstate_id">' + 
            '</a>');
       /*if(data.paymentstate_id){
            tableFields.push(paymentstates[data.paymentstate_id].name);
       }else{
            tableFields.push('');
       }*/
       tableFields.push('<a href="#" data-emptytext="Vuoto" class="paymentEditable" data-value="' +  moment().format("MM/DD/YYYY") + '" date-format="DD/MM/YYYY" data-template="DD/MM/YYYY" data-viewformat="DD/MM/YYYY" data-smartDays="true" data-type="combodate" data-pk="' + data.id + '" data-name="collection_date"></a>');
            
       /*if(data.collection_date){
            tableFields.push(data.collection_date);
       }else{
            tableFields.push('');
       }*/
       if(data.paymentgroup_nominal_number_of_performance > 1){
           s = 'Gruppo di ' + data.paymentgroup_nominal_number_of_performance + ' prestazioni.';
           if(data.paymentgroup_nominal_start_datetime){
               s += 'Valido dal ' + moment(data.paymentgroup_nominal_start_datetime).format('D/M/YYYY');
           }
           if(data.paymentgroup_nominal_end_datetime){
               s += ' al ' + moment(data.paymentgroup_nominal_end_datetime).format('D/M/YYYY');
           }
            tableFields.push(s);
       }else{
            tableFields.push('');
       }
        tableFields.push('<a href="#" data-emptytext="Vuoto" data-value="' + data.paymentformula_id + '" data-source="' + BASE_URL  + '/resources/xeditable/select/paymentformula" class="paymentEditable"  data-type="select" data-pk="' + data.id + '" data-name="paymentformula_id"></a>');
       
       /*if(data.paymentformula_id){
            tableFields.push(paymentformulas[data.paymentformula_id].name);
       }else{
            tableFields.push('');
       }*/
        tableFields.push('<a href="#" data-emptytext="Vuoto" data-value="' + data.paymentform_id + '" data-source="' + BASE_URL  + '/resources/xeditable/select/paymentform" class="paymentEditable"  data-type="select" data-pk="' + data.id + '" data-name="paymentform_id"></a>');
       
       /*
       if(data.paymentform_id){
            tableFields.push(paymentforms[data.paymentform_id].name);
       }else{
            tableFields.push('');
       }*/
        tableFields.push('<a href="' + urlFor(urlTmpls,'entityUI',{entityName : 'payment',pk: data.id}) + '" class="btn btn-default"><i class="clip-pencil-3"></i></a>' + 
            '<button  class="btn btn-bricky deleteEntityFromList" data-deleteurl="' + urlFor(urlTmpls,'entityResource',{entityName : 'payment',pk: data.id}) + '"><i class="fa fa-trash-o fa-white"></i></button>'
    		);
        dataTable.dataTable().fnAddData(tableFields);
        
        
        CustomClient.initPaymentEditable();
   }
}

function memoInsert(data){
    var m = $('#memoList');
   if(m.length > 0){
       var h = '<li><a class="todo-actions" href="javascript:void(0)"><i class="fa fa-';
    switch(data.type)
    {
        case '2':
          h += 'warning" style="color:#a94442';
          break;
        case '1':
          h += 'bullhorn" style="color:#8a6d3b';
          break;
          default:
          h += 'info-circle" style="color:#31708f';
          break;
    }
    h += '"></i><span class="desc" style="opacity: 1; text-decoration: none;">';
    h += data.value;
    h += '</span><span class="pull-right" style="opacity: 1;color:#888;">';
    h += moment(data.creation_datetime).format('D/M/YYYY');
    h += '</span></li>';
    m.prepend(h);
   }
}

function anamnesisInsert(data){
    var m = $('#anamnesisList');
    if(m.length > 0){
        var h = '<li><a class="activity" href="javascript:void(0)"><span class="desc" style="color:#333">';
        h += data.value;
        h += '</span><div class="time" style="top:10px;color:#888;"><i class="fa fa-time bigger-110"></i>';
        h += moment(data.creation_datetime).format('D/M/YYYY');
        h += '</div></a></li>';
        m.prepend(h);
   }
}

$.fn.dataTableExt.oSort['date-performance-asc']  = function(x,y) {
  /*
  
  var r = 0;
  if(x === ""){
    if(y === ""){
      r = 0;
    }else{
       r = 1;  
    }
  }else{
    if(y === ""){
      r = -1;
    }else{
       r = ((x < y) ? -1 : ((x > y) ?  1 : 0)); 
    }
  }
  return r;
  */
  var xt = $(x).html().trim();
    if(xt !== ''){
        xm = moment(xt,'DD/MM/YYYY');
    }
    var yt = $(y).html().trim();
    if(yt !== ''){
        ym = moment(yt,'DD/MM/YYYY');
    }
    
    
  var r = 0;
  if(xt === ""){
    if(yt === ""){
      r = 0;
    }else{
       r = 1;  
    }
  }else{
    if(yt === ""){
      r = -1;
    }else{
        if(xm.isSame(ym)){
            return 0;
        }
        if(xm.isAfter(ym)){
            return 1;
        }else{
            return -1;
        }
    }
  }
  return r;
};

$.fn.dataTableExt.oSort['date-performance-desc'] = function(x,y) {  
    var xt = $(x).html().trim();
    if(xt !== ''){
        xm = moment(xt,'DD/MM/YYYY');
    }
    var yt = $(y).html().trim();
    if(yt !== ''){
        ym = moment(yt,'DD/MM/YYYY');
    }
    
  var r = 0;
  if(xt === ""){
    if(yt === ""){
      r = 0;
    }else{
       r = -1;  
    }
  }else{
    if(yt === ""){
      r = 1;
    }else{
        if(xm.isSame(ym)){
            return 0;
        }
        if(xm.isAfter(ym)){
            return -1;
        }else{
            return 1;
        }
       //r = ((x < y) ?  1 : ((x > y) ? -1 : 0)); 
    }
  }
  return r;
};


$.fn.dataTableExt.oSort['date-id-asc']  = function(x,y) {
  var xi = parseInt(x);
  var yi = parseInt(y);
  return ((xi < yi) ? -1 : ((xi > yi) ?  1 : 0));
};

$.fn.dataTableExt.oSort['date-id-desc']  = function(x,y) {
  var xi = parseInt(x);
  var yi = parseInt(y);
  return ((xi > yi) ? -1 : ((xi < yi) ?  1 : 0));
};