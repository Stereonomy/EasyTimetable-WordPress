<?php 
/**
 * Provide public display
 *
 *
 * @link       http://www.stereonomy.com
 * @since      1.0.0
 *
 * @package    easy-timetable
 * @subpackage easy-timetable/public/controller
 */

if (!defined('WPINC')){die;}

class EasyTimetableControllerPlanning {

	public function __construct() {
  	}

  	public static function syet_displayPlanning($id, $nonce, $content) {
	    if (wp_verify_nonce($nonce, 'displayPlanning'))
		{

			require_once SYET_PATH . "public/models/EasyTimetableModelPlanning.php";
		    $model = new EasyTimetableModelPlanning();
		    $model->syet_displayPlanning($id);
		    require_once SYET_PATH . "public/views/EasyTimetableViewPlanning.php";
		    $view = new EasyTimetableViewPlanning($model);
		    $view->syet_displayPlanning($model, $content);
		};
  	}
}

?>