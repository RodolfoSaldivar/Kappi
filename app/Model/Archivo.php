<?php 
class Archivo extends AppModel{
    
    public $validate = array(
        'nombre' => array(
            'rule1' => array(
                'rule'    => array(
                'extension',array('pdf')),
                'message' => 'Solo archivos PDF.'
             ),
            'rule2' => array(
                'rule'    => array('fileSize', '<=', '1MB'),
                'message' => 'Archivos de menos de 1 MB.'
            )
        )
    );

    public $belongsTo = array(
        'Comunicado' => array(
            'className' => 'Comunicado',
            'foreignKey' => 'comunicado_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
?>