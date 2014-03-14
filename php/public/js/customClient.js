var CustomClient = function () {
    
    var initPerformanceDataTable = function () {
        /* -----------------------------------------------  PERFORMANCE ----------------------------------------------- */
            
            var tableId = 'performanceEntitiesTable';
            
            var oTable = $('#' + tableId).dataTable({
                "aaSorting": [
                    [1, 'desc']
                ],
                "aoColumns": [
                    null,
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
                "iDisplayLength": 10,
                "fnDrawCallback": function( oSettings ) {
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
                
                $.ajax({
                      type: "POST",
                      url: urlFor(urlTmpls,'entityResource',{entityName : 'payment'}),
                      data: { paymentgroup_nominal_number_of_performance: that.data('numperformance'),amount: that.data('amount'),client_id: that.data('clientid'),name: that.data('name')},
                      success: function(data){
                          paymentInsert(data);
                          var paymentDetail = data.name + '  - Pagato:';
                          if(data.paymentstate_id == '1'){
                              paymentDetail += "Si";
                          }else{
                              paymentDetail += "No";
                          }
                        paymentDetail += " - Importo : " + data.amount + " € - ";
                        if(that.data('numperformance') > 1){
                              paymentDetail += "[NUMERO] di " + that.data('numperformance');
                          }
                          
                          var pd = data;
                          
                          for (var i=1;i<=that.data('numperformance');i++)
                            { 
                            $.ajax({
                                  type: "POST",
                                  url: urlFor(urlTmpls,'entityResource',{entityName : 'performance'}),
                                  data: { client_id: pd.client_id,payment_id: pd.id},
                                  success: function(data){
                                    performanceInsert(data,paymentDetail.replace("[NUMERO]",i));
                                    console.log(paymentDetail);
                                  }
                            });
                            }
                          
                      }
                });
            });
    };
    return {
        init: function () {
            initMemoModal();
            initGroupRateButtons();
            initPaymentDataTable();
            initPerformanceDataTable();
        }
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

function performanceInsert(data,paymentDetail){
    var dataTable = $('#performanceEntitiesTable');
       if(dataTable.length > 0){
            var tableFields = [];
            tableFields.push(data.id);
            tableFields.push('');
            tableFields.push('');
            tableFields.push('');
            tableFields.push('');
            tableFields.push('');
            if(data.executed == '1'){
                tableFields.push('Si');
		    }else{
                tableFields.push('No');
            }
            tableFields.push(paymentDetail);
            
            
       }
    tableFields.push('<a href="' + urlFor(urlTmpls,'entityUI',{entityName : 'performance',pk: data.id}) + '" class="btn btn-default"><i class="clip-pencil-3"></i></a>');
    dataTable.dataTable().fnAddData(tableFields);
}


function paymentInsert(data){
    var dataTable = $('#paymentEntitiesTable');
   if(dataTable.length > 0){
       var tableFields = [];
       var s = '';
       tableFields.push(data.id);
       tableFields.push(data.name);
       tableFields.push(data.amount);
       if(data.paymentstate_id){
            tableFields.push(paymentstates[data.paymentstate_id].name);
       }else{
            tableFields.push('');
       }
       if(data.collection_date){
            tableFields.push(data.collection_date);
       }else{
            tableFields.push('');
       }
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
       if(data.paymentformula_id){
            tableFields.push(paymentformulas[data.paymentformula_id].name);
       }else{
            tableFields.push('');
       }
       if(data.paymentform_id){
            tableFields.push(paymentforms[data.paymentform_id].name);
       }else{
            tableFields.push('');
       }
        tableFields.push('<a href="' + urlFor(urlTmpls,'entityUI',{entityName : 'payment',pk: data.id}) + '" class="btn btn-default"><i class="clip-pencil-3"></i></a>');
        dataTable.dataTable().fnAddData(tableFields);
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
};

$.fn.dataTableExt.oSort['date-performance-desc'] = function(x,y) {  
  var r = 0;
  if(x === ""){
    if(y === ""){
      r = 0;
    }else{
       r = -1;  
    }
  }else{
    if(y === ""){
      r = 1;
    }else{
       r = ((x < y) ?  1 : ((x > y) ? -1 : 0)); 
    }
  }
  return r;
};