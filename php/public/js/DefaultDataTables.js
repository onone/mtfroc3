var DefaultTableData = function () {
        var runDataTable = function (tableId) {
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
                    [1, 'asc']
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
            /*
            $('#' + tableId + '_wrapper .dataTables_filter input').addClass("form-control input-sm").attr("placeholder", "Search");
            // modify table search input
            $('#' + tableId + '_wrapper .dataTables_length select').addClass("m-wrap small");
            // modify table per page dropdown
            $('#' + tableId + '_wrapper .dataTables_length select').select2();
            // initialzie select2 dropdown
            $('#' + tableId + '_column_toggler input[type="checkbox"]').change(function () {
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));
            });
            */
        };
        return {
            //main function to initiate template pages
            init: function (tableId) {
                runDataTable(tableId);
            }
        };
    }();