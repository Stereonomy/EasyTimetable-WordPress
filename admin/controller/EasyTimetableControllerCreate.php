<?php 
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.stereonomy.com
 * @since      1.0.0
 *
 * @package    easy-timetable
 * @subpackage easy-timetable/admin/controller
 */

if (!defined('WPINC')){die;}

class EasyTimetableControllerCreate {

	public function __construct() {
  	}

  	public static function syet_display() {
	    require_once SYET_PATH . "admin/models/EasyTimetableModelCreate.php";
	    $model = new EasyTimetableModelCreate();

	    require_once SYET_PATH . "admin/views/EasyTimetableViewCreate.php";
	    $view = new EasyTimetableViewCreate($model);
	    $view->syet_display($model);
  	}

  	public static function syet_saveTimetable($data) {
  		check_ajax_referer('nonce_easytimetable', 'saveSecurity');
	    require_once SYET_PATH . "admin/models/EasyTimetableModelCreate.php";
	    $model = EasyTimetableModelCreate::syet_saveTimetable($data);
	    //var_dump($model);
	    require_once SYET_PATH . "admin/views/EasyTimetableViewCreate.php";
	    $view = new EasyTimetableViewCreate();
	    $view->syet_afterSave($model[0]->id);
	    //var_dump("$view EasyTimetableViewCreate".$view);
  	}

  	public static function syet_editPlanning($data) {
  		check_ajax_referer('nonce_easytimetable', 'saveSecurity');
	    require_once SYET_PATH . "admin/models/EasyTimetableModelCreate.php";
	    $model = EasyTimetableModelCreate::syet_editPlanning($data['id']);

	    require_once SYET_PATH . "admin/views/EasyTimetableViewCreate.php";
	    $view = new EasyTimetableViewCreate($model);
	    $view->syet_editPlanning($model);
  	}
	
}