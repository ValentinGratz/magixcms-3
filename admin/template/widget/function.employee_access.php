<?php
/**
 * Smarty {employee_access} function plugin
 *
 * Type:     function
 * Name:     employee_access
 * Purpose:  Retourne les accès de l'employée courant
 * Examples: {if employee_access type="view_access" class_name="" eq 1}{/if}
 * Output:   
 * @link 
 * @author   Gerits Aurelien
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_employee_access($params, $template){
    $class_name = $params['class_name'];
    $type = $params['type'];
    if (!isset($class_name)) {
        trigger_error("type: missing 'class_name' parameter");
        return;
    }
    if (!isset($type) || empty($type) || $type === '') {
        $type = 'view';
    }
    if(class_exists('backend_model_access')){
        $model_access = new backend_model_access();
    }
    $all_access = $model_access->allDataEmployee($model_access->dataSession());
    return $all_access[$class_name][$type];
}