function urlFor(urlsTmpls,routeName,params){
    var url = null;
    if(typeof urlsTmpls[routeName] != "undefined"){
        url = urlsTmpls[routeName];
        if(typeof params != "undefined"){
            for (key in params) {
                url = url.replace(key,params[key]);
            }
        }
    }
    return url;
}
/*
var ulrTmpls = {
            entityUI: '/php/entity/entityName/pk',
            entityListUI: '/php/entity/entityName',
            entityResource: '/php/resources/entityName',
            
        };
        */