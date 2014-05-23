<?php
$entities['group'] =  array(
    'entityName' => 'group',
    'singular_label' => 'gruppo',
    'plural_label' => 'gruppi',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-group'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'active' => array(
            'label' => 'attivo',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'select',
                    'otherAttribute' => array(
                        'data-source' => array(array('text'=>'Si','value'=>1),array('text'=>'No','value'=>0)),
                    ),
                    'otherAttributeParam' => array(
                        'data-source' => array(
                            'toJson' => TRUE
                            ),
                    )
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'client' => array(
            'type' => 'many-to-one', // ex. MANY client belongs TO one group. Oppure group has many client
        ),
        'group_rate' => array(
            'type' => 'many-to-one', // ex. MANY group_rate belongs TO one group. Oppure group has many group_rate
        ),
        'rate' => array(
            'type' => 'many-to-many', // ex. MANY group_rate belongs TO one group. Oppure group has many group_rate
            'via' => 'group_rate'
        )
    )
);