var PerformanceCustom = function () {
    
    var PTCopied = new Array();
    var PTCopiedData = new Array();
    var clickBinded = false;
    var justRefreshed = new Array();
    
    var eo = {
             //https://mftr3-c9-langeli.c9.io/php/resources/performance_performancetype FUNZIONA
                url: urlFor(urlTmpls,'entityResource',{entityName : 'performance_performancetype'}), //this url will not be used for creating new user, it is only for update
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
                    /*
                    var s = $(this).siblings('.relatedShow');
                    if(s.length > 0){
                    var n = $(s[0]);
                        if(typeof n != "undefined"){
                            var u = n.data('entityurl');
                            n.attr('href',u.replace("[[ID]]",newValue)).removeClass('hide');
                        }
                    }
                    */
                    
                    var t = $(this);
                    if(t.data('name') == 'payment_id'){
                        var oldValue = t.data('value');
                        t.data('value',newValue);
                        t.parent().addClass("PP" + newValue);
                        PerformanceCustom.refreshPaymentData("PP" + newValue,"PP" + oldValue);
                    }
                },
                error: function(response){
                    console.log(this);
                    console.log(response);
                }
            };
    
    var initPerformancePTEditable = function(){
         
            
        $('.performancePTEditable:not(.editable)').each(function( index ) {
                    var that = $(this);
                    if(typeof that.data('jsoptions') != "undefined") {
                        $.extend(eo, EC.fields[that.data('name')].customConfig.xeditable.jsoptions);
                    }
                    that.editable(eo);
            })
    }
    
    var generatePaymentHtml = function(tr){
        var h = '';
        var importo = ''
            , pagato = ''
            , descGruppo = '';
            
        //h += tr.find('td:eq(1) a').html().trim(); //nome
        
        descGruppo = tr.find('td:eq(5)').html().trim();
        if(descGruppo != ''){
            h += descGruppo + '<br>';
        }
        
        //h += ' - ';
        h += 'Pagato: ';
        //pagato = tr.find('td:eq(3) a').html().trim();
        pagato = tr.find('td:eq(3) a').data('value');
        if(pagato == '1'){
            pagato = "Si";
        }else{
            pagato = "No";
        }
        h += pagato + '<br>';
        
        importo = tr.find('td:eq(2) a').html().trim();
        
        //h += ' - ';
        h += 'Importo: ';
        if(importo == ''){
            importo = "Non definito"
        }else{
            h += ' &euro; ';
        }
        h += importo;
        
        return h;
    }
    
    var refreshPaymentData = function(idNew){
        if(justRefreshed.indexOf(idNew) == -1){
            var h = generatePaymentHtml($('#' + idNew));
            var els = $('#performanceEntitiesTable').find('.' + idNew);
            var elsLenght = els.length;
            if(elsLenght){
                els.each(function(index){
                    var i = (parseInt(index) + 1);
                    var t = $(this);
                    var e = t.find('.lbl');
                    if(e.length == 0){
                        t.append('<div class="lbl"></div>');
                        e = t.find('.lbl');
                    }
                    e.html((elsLenght > 1?i + ' di ' + elsLenght + '<br>':'') + h);
                    
                });
            }
            
            justRefreshed.push(idNew);
        }
        justRefreshed = new Array();
    }
    
    var initButtons = function(){
    }
        
    var initTD = function(td,data){
        if(td.hasClass('PT')){
            
        }else{
            td.addClass('PT');
            td.data('performance-id',data[0]);
            initCntButtonsHTML(td);
        
            var li = td.find('.sortablePT li');
            if(li.length){
                li.each(function(){
                    drawButtons($(this));
                });
            }
        }
            
    }
    
    var initCntButtonsHTML = function(td){
        var empty = false;
        if(td.html().trim() == ''){
            empty = true;
        }
        td.prepend('<div class="PTCnt">' +
				    '<a href="javascript:void(0)" title="Inserisci nuovo dopo" class="PTBtn PTInsertCnt"><i class="fa fa-plus"></i></a>' +
				    '<a href="javascript:void(0)" title="Copia" class="PTBtn PTCopy PTCopyCnt" ' + (empty?'style="display:none"':'') + '><i class="fa fa-copy"></i></a>' +
				    '<a href="javascript:void(0)" title="Incolla in fondo" class="PTBtn PTPaste PTPasteCnt" ' + (PTCopied.length?'':'style="display:none"') + '><i class="fa fa-paste"></i></a>' +
    					    '<span class="spinner"></span>' + 
    		    '</div>');
    }
    
    var drawButtons = function(li){
        if(!li.find('.PTButtons').length){
            li.prepend('<div class="pull-right text-right PTButtons">' +
    					    '<a href="javascript:void(0)" title="Sposta su" class="PTBtn PTmoveUp"><i class="fa fa-chevron-up"></i></a>' +
    					    '<a href="javascript:void(0)"  title="Sposta giu" class="PTBtn PTmoveDown"><i class="fa fa-chevron-down"></i></a>' +
    					    '<a href="javascript:void(0)" title="Copia" class="PTBtn PTCopy PTCopySingle"><i class="fa fa-copy"></i></a>' +
    					    '<a href="javascript:void(0)" title="Rimuovi" class="PTBtn PTRemove"><i class="fa fa-trash-o"></i></a>' +
    					    '<a href="javascript:void(0)" title="Inerisci nuovo dopo" class="PTBtn PTInsert"><i class="fa fa-plus"></i></a>' +
    					    '<a href="javascript:void(0)" title="Incolla dopo" class="PTBtn PTPaste" ' + (PTCopied.length?'':'style="display:none"') + '><i class="fa fa-paste"></i></a>' +
    					    '<span class="spinner"></span>' + 
    				    '</div>');
        }
    }
    
    var movePT = function(li,dir){
        if(typeof dir == "undefined"){
            dir = 1;
        }
        if(dir==1){
            var s = li.next();
            if(s.length){
                li.insertAfter(s);
                updatePosition(li.parent());
            }
        }else{
            var s = li.prev();
            if(s.length){
                li.insertBefore(s);
                updatePosition(li.parent());
            }
        }
        return li;
        
    };
    
    var updatePosition = function(ul){
        
        var ajaxCalls = new Array();
        var td = ul.parent();
        
        hideShowPTBtn(td,false);
        
        ul.find('li').each(function(index){
            var li = $(this);
            
            if(li.data('position') != index){
                var data = {
                    position:index,
                    pk:li.data('id')
                };
                
                ajaxCalls.push(udpatePTAjax(data,li));
            }
        });
        if(ajaxCalls.length){
            $.when.apply(null, ajaxCalls).then( 
                function() {
                  hideShowPTBtn(td);
                },
                function() {
                    alert('Errore in fase di aggiornamento! Aggiorna la pagina!');
                    /*if(typeof li != "undefined" && typeof dir != "undefined"){
                        if(dir==1){
                            var s = li.next();
                            li.insertAfter(s);
                        }else{
                            
                        }
                    }*/
                    hideShowPTBtn(td);
                }
            );
        }
        
    }
    
    var udpatePTAjax = function(data,li){
        data = $.extend(data,{_METHOD:'PUT'});
        return $.ajax({
              type: "POST",
              url: urlFor(urlTmpls,'entityResource',{entityName : 'performance_performancetype'}),
              data: data,
              success:function(){
                  li.data('position',data.position);
              }
        });
    };
    
    var hideShowPTBtn =  function(td,show){
        if(typeof show == "undefined"){
            show = true;
        }
        
        if(show){
            td.removeClass('loading');
        }else{
            td.addClass('loading');
        }
    
    }
    
    var insertToCopied = function(id,data){
        if(typeof id != "undefined" && id != "undefined"){
            PTCopied.push(id);
            if(typeof data != "undefined"){
                PTCopiedData['ID' + id] = data;
            }
            drawCopiedCnt();
        }
    }
    
    var drawCopiedCnt = function(){
        var cc = $('#PTCopiedCnt');
        var c = '<a href="javascript:void(0)" title="Rimuovi" id="PTCopyRemoveAll"><i class="fa fa-trash-o"></i></a>';
        var st = $('.style-toggle');
        if(PTCopied.length){
            $.each(PTCopied, function( index, value ) {
                var p = PTCopiedData['ID' + value];
                c += '<div data-id="' + p.pt_id + '" data-index="' + index + '"><a href="javascript:void(0)" title="Rimuovi" class="PTCopyRemoveSingle pull-right"><i class="fa fa-trash-o"></i></a>'
                + '<strong>' + p.type + '</strong>: ' 
                + p.note.substring(0,Math.min(20,p.note.length)) 
                + '</div>';
            });
            
            
            st.removeClass('close').addClass('open');
            $('#style_selector_container').show();
        }else{
            
            st.removeClass('open').addClass('close');
            $('#style_selector_container').hide();
        }
        cc.html(c);
        refreshPasteBtn();
    }
    
    var refreshPasteBtn = function(td){
        var s = $('#performanceEntitiesTable');
        if(PTCopied.length){
            s.find('a.PTPaste').show();
            
        }else{
            s.find('a.PTPaste').hide();
        }
    }
    
    var removeAllCopied = function(){
        PTCopied = new Array();
        //PTCopiedData = {};
        drawCopiedCnt();
    }
    
    var removeSingleCopied = function(id){
        //var i = PTCopied.indexOf(id);
        //PTCopied.splice(i, 1);
        //delete PTCopiedData['ID' + id];
        
        PTCopied.splice(id, 1);
        
        drawCopiedCnt();
    }
    
    var initPTButtonsClickBinding = function(){
        if(clickBinded === true) return;
        clickBinded = true;
        
        var ss = $('#PTCopiedCnt');
        
        ss.on('click', '.PTCopyRemoveSingle', function (event) {
            removeSingleCopied($(this).parent().data('index'));
        });
        ss.on('click', '#PTCopyRemoveAll', function (event) {
            removeAllCopied();
        });
        
        var t = $('#performanceEntitiesTable');
        
        t.on('click', '.PTmoveUp', function (event) {
            movePT($(this).parent().parent(),-1);
        });
        t.on('click', '.PTmoveDown', function (event) {
            movePT($(this).parent().parent());
        });
        t.on('click', '.PTRemove', function (event) {
            
            var li = $(this).parent().parent();
            hideShowPTBtn(li.parent().parent(),false);
            removePTAjax(li.data('id'),li);
        });
        
        t.on('click', '.PTPaste', function (event) {
            
            var t = $(this);
            if(!t.hasClass('PTPasteCnt')){
                var li = t.parent().parent();
                var td = li.parent().parent();
            }else{
                var td = t.parent().parent();
                var li = null;
            }
            
            hideShowPTBtn(td,false);
            
            var ajaxCalls = new Array();
            
            if(PTCopied.length){
                $.each(PTCopied, function( index, value ) {
                    var p = PTCopiedData['ID' + value];
                    var data = {
                        performancetype_id:p.type_id,
                        
                        performance_id:td.data('id')
                    };
                    
                    if(p.note != '' && p.note != 'Vuoto' && p.note != null){
                        $.extend( data, {note:p.note} );
                    }
                    ajaxCalls.push(insertPTAjax(data,td,li));
                });
                
                if(ajaxCalls.length){
                    $.when.apply(null, ajaxCalls).then( 
                        function() {
                          hideShowPTBtn(td);
                          updatePosition(td.find('ul'));
                        },
                        function() {
                            alert('Errore in fase di aggiornamento! Aggiorna la pagina!');
                            hideShowPTBtn(td);
                        }
                    );
                }
            }else{
                hideShowPTBtn(td);
            }
            
        });
        
        
        t.on('click', '.PTInsert,.PTInsertCnt', function (event) {
            var t = $(this);
            if(t.hasClass('PTInsert')){
                var li = t.parent().parent();
                var td = li.parent().parent();
            }else{
                var td = t.parent().parent();
                var li = null;
            }
            hideShowPTBtn(td,false);
            
            var data = {
                performance_id: td.data('performance-id')
                };
            insertPTAjax(data,td,li);
        });

        
        t.on('click', '.PTCopySingle', function (event) {
            var li = $(this).parent().parent();
            var ptid = li.find('a.ptTitle');
            var data = {
                type: ptid.html(),
                note: li.find('a.ptNote').html(),
                type_id: ptid.data('value'),
                pt_id:li.data('id')
            };
            insertToCopied(li.data('id'),data);
        });
        
        t.on('click', '.PTCopyCnt', function (event) {
            var ul = $(this).parent().siblings('.sortablePT');
            if(ul){
                ul.find('li').each(function(){
                    var li = $(this);
                    var ptid = li.find('a.ptTitle');
                    var data = {
                        type: ptid.html(),
                        note: li.find('a.ptNote').html(),
                        type_id: ptid.data('value'),
                        pt_id:li.data('id')
                    };
                    insertToCopied(li.data('id'),data);
                });
            }
        });
        
        
    };
    
    var insertPTAjax = function(data,td,li){
        data = $.extend(data,{query:1});
        $.ajax({
              type: "POST",
              url: urlFor(urlTmpls,'entityResource',{entityName : 'performance_performancetype'}),
              data: data,
              success: function(data){
                PTInsertInTable(data,td,li);
              }
        });
    };
    
    var PTInsertInTable = function(data,td,li){
        
        var ul = td.find('.sortablePT');
        if(ul.length == 0){
            td.append('<ul class="sortable sortablePT"></ul>');
            ul = td.find('.sortablePT');
        }
        
        var el = '<li ' +  (data.positon == null?'':'data-position="' + data.positon + '"') + '  data-id="' + data.id + '" id="PT' + data.id + '">' +
                    // <a href="#" data-emptytext="Vuoto" {% if pt.performancetype_id is defined %}data-value="{{ pt.performancetype_id }}"{% endif %} data-source="' + BASE_URL  + '/resources/xeditable/select/performancetype" class="performancePTEditable ptTitle" id="performance_performancelocation_id" data-type="select" data-pk="{{ pt.id  }}"  data-name="performancetype_id">{% if pt.performancetype_id is defined %}{{ performancetypes[pt.performancetype_id] }}{% endif %}</a>
    				'<a href="#" data-emptytext="Vuoto" class="performancePTEditable ptTitle" data-type="select" data-pk="' +  data.id + '" data-source="' + BASE_URL  + '/resources/xeditable/select/performancetype" ' +  (data.performancetype_id == null?'':' data-value="' + data.performancetype_id + '"') + ' data-name="performancetype_id"></a>' +
    			    //'<a href="#" data-emptytext="Vuoto" class="performancePTEditable ptTitle" data-type="text" data-pk="' +  data.id + '" data-name="performancetype_id">' +  (data.performancetype_id == null?'':data.performancetype_id) + '</a>' +
    			    '<a href="#" data-emptytext="Vuoto" class="performancePTEditable ptNote" data-type="textarea" data-pk="' +  data.id + '" data-name="note">' +  (data.note == null?'':data.note) + '</a>' +
		          '</li>';
        
        if(typeof li != "undefined" && li != null){
            li.after(el);
        }else{
            ul.append(el);
        }
        var eljQ = td.find('#PT' + data.id);
        
        eljQ.find('.performancePTEditable:not(.editable)').each(function( index ) {
                    $(this).editable(eo);
            })
        drawButtons(eljQ);
        updatePosition(ul);
        hideShowPTBtn(td);
        
    }
    
    var removePTAjax = function(id,li){
        var r = confirm("Sei sicuro di voler rimuovere?");
        if (r == true)
          {
          $.ajax({
              type: "POST",
              url: BASE_URL  + '/resources/performance_performancetype/' + id,
              data: { _METHOD: "DELETE" },
              success: function(data){
                  if(typeof li != "undefined"){
                      hideShowPTBtn(li.parent().parent());
                      li.remove();
                  }
              },
              error:function(data){
                    hideShowPTBtn(li.parent().parent());
              }
            });
          }else{
              hideShowPTBtn(li.parent().parent());
          }
    }
    
    return {
        init: function () {
            initPerformancePTEditable();
            initPTButtonsClickBinding();
            //initButtons();
        },
        initTD:initTD,
        PTCopied:PTCopied,
        PTCopiedData:PTCopiedData,
        refreshPaymentData:refreshPaymentData,
        justRefreshed:justRefreshed
    }
    
}();