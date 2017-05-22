<?php 

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.stereonomy.com
 * @since      1.0.0
 *
 * @package    Easyscroller
 * @subpackage Easyscroller/admin/models
 */

if (!defined('WPINC')){die;}


class EasyTimetableModelList
{
	
	function __construct()
	{

	}
	
	public static function syet_listItems() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'easytimetable_planning';
    	$results = $wpdb->get_results(
            "
            SELECT *
            FROM $table_name
            "
        );
        return $results;
	}
	
	/**
	* Recherche si la colonne days existe dans la table
	* @link       http://www.stereonomy.com
	* @since      1.3.0
	*
	* @package    EasyTimetable
	* @subpackage EasyTimetable/admin/models
	*/

	public static function syet_column_exist() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'easytimetable_planning';
		if (!$wpdb->get_col_length($table_name, 'days' ))
		{
			$wpdb->query(
				"
				ALTER TABLE $table_name
				ADD days text NOT NULL
				");
		}
	}
	
	public static function syet_deleteItem($data) {
		global $wpdb;
		//var_dump($data);
		$table_name = $wpdb->prefix . 'easytimetable_planning';

    	if(isset($data['id']))
		{
			$wpdb->delete( 
        	$table_name,
        	array( 
	            'id' => $data['id']
	        ));
			$results = $wpdb->get_results(
	            "	
	            SELECT *
	            FROM $table_name
	            "
        	);
		}
		else 
		{	
			echo "Error big";
		}
        
        //var_dump($results);
        return $data['id'];
	}

	public static function syet_duplicateItem($data) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'easytimetable_planning';
		$id = $data['id'];
		$date = date("Y-m-d H:i:s");
		$name = $data['p_name'].' (copy)';
		$activities = stripslashes($data['activities']);
		$activities = stripslashes($activities);
		$scheduledacts = stripslashes($data['scheduledact']);
		$scheduledacts = stripslashes($scheduledacts);
		$days = stripslashes($data['days']);
		$days = stripslashes($days);
		$rows_name = (!is_null($data['rows_name']) ? $data['rows_name'] : "");
		$access = (!is_null($data['access']) ? $data['access'] : "");
		
    	//var_dump($days);
    	//var_dump($scheduledacts);
		
		if (!$wpdb->insert( 
    	$table_name,
    	array( 
            'p_name' => $name, 
            'creation_date' => $date, 
            'type' => $data['type'],
            'display_title' => $data['display_title'],
            'display_filters' => $data['display_filters'],
            'time_mode' => $data['time_mode'],
            'start_h' => $data['start_h'],
            'rows' => $data['rows'],
            'rows_name' => $rows_name,
            'cells' => $data['cells'],
            'cell_color' => $data['cell_color'],
            'height' => $data['height'],
            'description' => $data['description'],
            'activities' => $activities,
            'scheduledact' => $scheduledacts,
            'days' => $days,
            'print' => $data['print'],
            'created_by' => $data['created_by'],
            'access' => $access
        ))==TRUE)
		{
			echo "Error grave";
			echo mysql_error();
		}
		
        return;
	}

	public static function syet_displayAbout()
	{
		return;
	}
	
}

