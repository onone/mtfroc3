<?php

function entityRepresentation($entities, $entityConfiguration){
    
    if(is_object($entities)) $entities = array($entities);
    
    if(isset($entityConfiguration['representation'])){
        $representationsTmpl = $entityConfiguration['representation'];
    }else{
        $representationsTmpl = '<<id>>';
    }
    
    $representations = array();
    
    foreach ($entities as $entity) {
        $representation = $representationsTmpl;
        if(is_array($entityConfiguration['fields']) &&  !empty($entityConfiguration['fields'])){
            foreach ($entityConfiguration['fields'] as $fieldName => $fieldData) {
                if(isset($fieldData['visible']) && $fieldData['visible'] === false) $replaceValue = '';
                $replaceValue = $entity->$fieldName;
                $representation = str_replace("<<$fieldName>>",$replaceValue,$representation);
            }
            $representations[$entity->id] = array('representation' => $representation,'id' => $entity->id);
        }
    }
    return $representations;
}
?>