<?php

/**
 * Provide public display of a planning
 *
 *
 * @link       http://www.stereonomy.com
 * @since      1.0.0
 *
 * @package    easy-timetable
 * @subpackage easy-timetable/public/views
 */

if (!defined('WPINC')){die;}

require_once SYET_PATH . 'public/models/EasyTimetableModelPlanning.php';
$model = EasyTimetableModelPlanning::syet_displayPlanning($id);

class EasyTimetableViewPlanning {
	
	//private $model;
	
	public function __construct($model) {
		
	}

	public static function syet_displayPlanning($model, $content) {
		global $wp_locale;
		
		$wkTtStart = (int)get_option("start_of_week"); 
		// On récuère les activités
		//var_dump($model);
		//$activities is a JSON string, each data of this string is escape individually later
		//datas are not usable if they are not JSON formatted
		$activities = stripslashes($model[0]->activities);
		// Array des jours de la semaine
		$jsondaysArray = json_decode(stripslashes($model[0]->days), true);

		// On récupère les activités planifiées
		//$scheduledacts is a JSON string, easch data of this string is escape individually later
		//datas are not usable if they are not JSON formatted
		$scheduledacts = stripslashes($model[0]->scheduledact);
		// On récupère les activités planifiées
		$jsonTimetable = json_decode($scheduledacts, true);
		
		// On transforme la chaîne des activités en tableau
		$arrayAct = explode(",{", $activities);
		$arrayAct_length = count($arrayAct);

		echo $content;
		?>

		<input class="at" type="hidden" value="<?php echo $model[0]->time_mode ?>" >
		<input class="ttm" type="hidden" value="<?php echo $model[0]->type ?>" >
		<?php if($model[0]->print == 1): ?>
			<div class="sy-print sy-button button" data-tooltip-content=".sy-tooltip-print-content"><?php _e( 'Print', 'easytimetable-responsive-schedule-management-system' ); ?></div>
		<?php endif ?>
			<?php if ($model[0]->display_title == 1): ?>
		    	<h2 class="timetable-title"><?php echo esc_html($model[0]->p_name) ?></h2>
		    <?php endif ?>
		    <?php if($model[0]->description): ?>
		    	<div class="sy-description-front">
		    		<?php echo esc_textarea($model[0]->description); ?>
		    	</div>
		    <?php endif ?>
		    <?php if ($model[0]->display_filters == 1): ?>
		    	<div class="sy-filters">
		    		<div id="sy-all" class="sy-actsingle allfilter sy-button button" style="background-color: #999;"><?php echo "x" ?></div>
		    	</div>
		    <?php endif ?>
		<?php $variationCount = 0; ?>
		<?php if ($model[0]->type == 0):
			$thisdate = new DateTime();
			//var_dump(implode(",", $weeks));
			$year = $thisdate->format('Y');
			$thisweek = (int)$thisdate->format('W');
		 ?>
		 <?php if(empty($model[0]->rows_name)): ?>
			<div class="timetable-nav">
				<div class="prev-variation sy-button button" data-variation="0" data-thisweek="<?php echo $thisweek ?>">Prev</div>
				<div class="next-variation sy-button button" data-variation="0" data-thisweek="<?php echo $thisweek ?>">Next</div>
			</div>
		<?php endif ?>
		<div class="timetable-container">
			<?php foreach ($jsonTimetable['timetables'] as $variation): ?>		
			<?php $arrayplanAct = $variation["scheduledacts"]; ?>
			<?php $weeks = $variation["weeks"];
			
			//var_dump($thisweek); ?>
			<?php if (in_array($thisweek, $weeks)):  ?>
				<?php $isVisible = "" ?>
			<?php else: ?>
				<?php $isVisible = "invisible" ?>
			<?php endif ?>
			<?php $arrayplanAct_length = count($arrayplanAct); ?>
			<div id="easytimetable-<?php echo $variationCount ?>"  data-weeks="<?php echo implode(",", $weeks); ?>" data-variation="<?php echo $variationCount ?>" class="fullplanning easytimetable-<?php echo esc_attr($model[0]->id) ?> <?php echo $isVisible ?> variation-<?php echo $variationCount ?>" data-id="<?php echo esc_attr($model[0]->id) ?>" data-type="<?php echo esc_attr($model[0]->type) ?>" data-time-mode="<?php echo esc_attr($model[0]->time_mode) ?>" data-display-title="<?php echo esc_attr($model[0]->display_title) ?>" data-display-filters="<?php echo esc_attr($model[0]->display_filters) ?>" data-start-h="<?php echo esc_attr($model[0]->start_h) ?>" data-color="<?php echo esc_attr($model[0]->cell_color) ?>" data-rows="<?php echo esc_attr($model[0]->rows) ?>" data-cells="<?php echo esc_attr($model[0]->cells) ?>">
				<!-- Display title -->
				
			    <?php $rows = esc_attr($model[0]->rows);
	   				$rwidth= 95/$rows;
	   				if(!empty($model[0]->rows_name)) 
	   				{
	   					$name = explode(",", esc_html($model[0]->rows_name));
	   				}
	   				else
	   				{
	   					// sinon, les jours de la semaine traduits
	   					$name = [];
	   					foreach ($jsondaysArray['days'] as $key => $day) {
	   						$day4date = (int)$day;

	   						if ($key >= $day4date && $day == 0)
	   						{
	   							//var_dump($day4date);
	   							$day4date .= 7;
	   						}
	   						else if ($key >= $day4date) {
	   							$day4date = (int)$day + 7;
	   						}
	   						//var_dump(($key + 1)) - $day4date);
	   						/*if (($key + 1) - $day4date > 0)
	   						{
	   							$day4date .= 7;
	   						}*/
	   						$date = new DateTime();
	   						
							$year = $date->format('Y');
							$week = $date->format('W');
							$date->setISODate($year,$week, $day4date);
	   						//array_push($name, $wp_locale->get_weekday($days));
	   						array_push($name, "<div class='sy-dayname' data-day=".$day.">".$wp_locale->get_weekday($day)."</div><div class='sy-thedate'>".$date->format('d.m.Y')."</div>");
	   					}
	   					
	   				}

	   				for ($number=1;$number<=$rows;$number++) { ?>
	   				<div class="sy-row-container" style="width:<?php echo $rwidth ?>%" >
					   	<div id="<?php echo $number ?>" class="row-<?php echo $number ?> rows" >
					   		<div class="col-title"><?php  if(isset($name[$number-1]) && !empty($name)) {echo $name[$number-1]; } else {echo "";}?></div>
					   		<?php	
						   		$cells = $model[0]->cells;
						   		$rheight = 65*$cells;
						   		for ($cell=1;$cell<=$cells;$cell++) {
						   			$idcell = $cell;
						   			if ($idcell < 10) {
			                            $zero = "00";
			                        }
			                        else {
			                            $zero = "0";
			                        } 
						   			global $empty;
						   			$empty;
						   			$idul = $number.$zero.$idcell;
						   			//Tableau pour définir le nombre d'activité dans la cellule	
						   			$tabAct = [];	
						   			?>
						   			<ul id="cellule_<?php echo $idul ?>" class="creneau creneau<?php echo $idcell ?>" style="background-color:#<?php echo $model[0]->cell_color ?>;height:<?php echo $model[0]->height ?>px;">
						   			<?php if ($model[0]->scheduledact) { ?> 
											<?php for ($i = 0 ; $i < $arrayplanAct_length ; $i++) {
												
												$encodedplanAct = json_encode($arrayplanAct[$i]);
												$decodedplanAct = json_decode($encodedplanAct);
												
												if ((int)$decodedplanAct->{'id_cell'} == (int)$idul) { 
													$actId = (int)$decodedplanAct->{'id_activity'};
													// On ajoute le nom de l'activité dans le tableau
													array_push($tabAct, $decodedplanAct->{'title'});
													//On compte le nombre d'élément du tableau pour l'ajouter à l'id de l'activité
													$actCount = count($tabAct);
													//var_dump($actCount);

													if ($actId >= 1 && preg_match('/^"a/', $arrayAct[$actId])) {
														// on ajoute le délimiteur de json enlevé lors de la conversion en tableau ci-dessus
														$arrayAct[$actId] = '{'.$arrayAct[$actId];
														// On décode la string en objet json
														$decodedAct = json_decode($arrayAct[$actId]);
														//var_dump($decodedAct);
													} // Fin if $i >= 1
													else {
														$decodedAct = json_decode($arrayAct[$actId]);
														//var_dump($decodedAct);
													} // fin else

													$acttitle = rawurldecode(urldecode($decodedAct->{'actname'}));
													
											    	$acttitleid = preg_replace("/\s+/", "", preg_replace("/[#$,;:!€£%§?&.()'-\*]/", "", $acttitle));
											        $acttitleid = preg_replace("/[\"’\/~\\<|>@]+/", "", $acttitleid);
											        $acttitleid = preg_replace("/[áàâÂäÄæÆãÃåÅÀÁ]+/", "a", $acttitleid);
											        $acttitleid = preg_replace("/[éèêëÉÈÊË]+/", "e", $acttitleid);
											        $acttitleid = preg_replace("/[ïîìíÍÌÎÏ]+/", "i", $acttitleid);
											        $acttitleid = preg_replace("/[ûüùúÚÙÛÜ]+/", "u", $acttitleid);
											        $acttitleid = preg_replace("/[ñÑ]+/", "n", $acttitleid);
											        $acttitleid = preg_replace("/[çÇ]+/", "c", $acttitleid);
											        $acttitleid = preg_replace("/[šŠß]+/", "s", $acttitleid);
											        $acttitleid = preg_replace("/[žŽ]+/", "z", $acttitleid);
											        $acttitleid = preg_replace("/[ÝýÿŸ]+/", "y", $acttitleid);
											        $acttitleid = preg_replace("/[ôöòÓóÒØøõÕœŒÔ]+/", "o", $acttitleid);
											        $acttitleid = preg_replace("/^1/", "one", $acttitleid);
											        $acttitleid = preg_replace("/^2/", "two", $acttitleid);
											        $acttitleid = preg_replace("/^3/", "three", $acttitleid);
											        $acttitleid = preg_replace("/^4/", "four", $acttitleid);
											        $acttitleid = preg_replace("/^5/", "five", $acttitleid);
											        $acttitleid = preg_replace("/^6/", "six", $acttitleid);
											        $acttitleid = preg_replace("/^7/", "seven", $acttitleid);
											        $acttitleid = preg_replace("/^8/", "eight", $acttitleid);
											        $acttitleid = preg_replace("/^9/", "nine", $acttitleid);
											        $acttitleid = preg_replace("/^0/", "zero", $acttitleid);
											        
											        if ($model[0]->type == 1)
											        {
											            $start = esc_html($decodedplanAct->{'start_t'});
											            $end = esc_html($decodedplanAct->{'end_t'});
											        }
											        else {
											            $start = substr($decodedplanAct->{'start_t'}, -8, 5);
											            $end = substr($decodedplanAct->{'end_t'}, -8, 5);
											        } 
											        if (!empty($decodedAct->{'actovercolor'}))
										            {
										            	$overColor = "#".$decodedAct->{'actovercolor'};
										            }
										            else 
										            {
										            	$overColor = "#".$decodedAct->{'actcolor'};
										            }

										            if (!empty($decodedAct->{'fontcolor'}))
										            {
										            	//var_dump($decodedAct->{'fontcolor'});
										            	$fontColor = "#".$decodedAct->{'fontcolor'};
										            }
										            else 
										            {
										            	$fontColor = "#ffffff";
										            }

										            if (!empty($decodedAct->{'fontovercolor'}))
										            {
										            	$fontOverColor = "#".$decodedAct->{'fontovercolor'};
										            }
										            else 
										            {
										            	$fontOverColor = "#ffffff";
										            }

											        ?> 
											        <li id="act-<?php echo esc_attr($decodedplanAct->{'id_cell'}) ?>-<?php echo $actCount ?>-<?php echo $variationCount ?>" data-merge="<?php if(!is_null($decodedplanAct->{'merge'})) {echo esc_attr($decodedplanAct->{'merge'});} ?>" data-tooltiptext="<?php if(!is_null($decodedAct->{'acttooltiptext'})) {echo esc_attr($decodedAct->{'acttooltiptext'});} ?>" data-imgtooltip="<?php if(!is_null($decodedAct->{'imgtooltip'})) {echo esc_attr($decodedAct->{'imgtooltip'});} ?>" data-showdesc="<?php if(!is_null($decodedAct->{'showdesc'})) {echo esc_attr($decodedAct->{'showdesc'});} ?>" data-li-id="act-<?php echo esc_attr($decodedplanAct->{'id_cell'}) ?>-<?php echo $actCount ?>-<?php echo $variationCount ?>" data-tooltip-content=".sy-infofields-<?php echo esc_attr($decodedplanAct->{'id_cell'}) ?>-<?php echo $actCount ?>-<?php echo $variationCount ?>" data-id-activity="<?php echo esc_attr($decodedplanAct->{'id_activity'}) ?>" data-color="<?php echo esc_attr($decodedAct->{'actcolor'}) ?>" data-overcolor="<?php echo esc_attr($decodedAct->{'actovercolor'}) ?>" data-fontcolor="<?php echo esc_attr($decodedAct->{'fontcolor'}) ?>" data-image="<?php echo esc_attr($decodedAct->{'actimage'}) ?>" data-fontovercolor="<?php echo esc_attr($decodedAct->{'fontovercolor'}) ?>" data-idcell="<?php echo esc_attr($decodedplanAct->{'id_cell'}) ?>" data-idacti="<?php echo esc_attr($decodedplanAct->{'idacti'}) ?>" data-title="<?php echo $decodedAct->{'actname'} ?>" data-duree="<?php echo esc_attr($decodedplanAct->{'duree'}) ?>" class="<?php echo $acttitleid ?> activite" style="background-color:#<?php echo esc_attr($decodedAct->{'actcolor'}) ?>;color:#<?php echo  esc_attr($decodedAct->{'fontcolor'}) ?>;">
												        <div class="time">
										   					<span class="sy-start"><?php echo $start ?></span>
										   					<span class="sy-separator">></span>
										   					<span class="sy-end"><?php echo $end ?></span>
									   					</div>
												        <div id="nom" class="acttitle" title="<?php echo $acttitle ?>"><?php echo $acttitle ?></div>
												        <?php if ($decodedAct->{'actincelltext'} != ""):?>
													        <div class="sy-actincelltext"><?php echo $decodedAct->{'actincelltext'} ?></div>
													    <?php endif ?>
												        <div class="sy-description-container">
												        	<?php if ($decodedAct->{'showdesc'} == 1 && ($decodedAct->{'actimage'} != "" || $decodedAct->{'actdescription'} != "")): ?>
													        	<div class="sy-description-<?php echo esc_attr($decodedplanAct->{'id_cell'}) ?> sy-description-style">
													        		<h3><?php echo $acttitle ?></h3>
													        		<?php if ($decodedAct->{'actimage'} != ""): ?>
													        			<div class="sy-image-desc"><img src="<?php echo esc_attr($decodedAct->{'actimage'}) ?>"></div>
													        		<?php endif ?>
													        		<?php if ($decodedAct->{'actdescription'} != ""): ?>
													        			<div class="sy-text-desc"><?php echo $decodedAct->{'actdescription'} ?></div>
													        		<?php endif ?>
													        	</div>
												        	<?php endif ?>
												        </div>
												    </li>
										    		<div class="sy-infofields-container">
												    	<div class="sy-infofields-<?php echo $decodedplanAct->{'id_cell'} ?>-<?php echo $actCount ?>-<?php echo $variationCount ?> sy-infofield">
												    		<?php if ($decodedAct->{'actimage'} && $decodedAct->{'imgtooltip'}):?>
												    		<div class="sy-imagefield">
												    			<img src="<?php echo esc_attr($decodedAct->{'actimage'}) ?>">
												    		</div>
												    		<div class="sy-actTitle">
													    		<?php echo $decodedAct->{'actname'} ?>
													    	</div>
												    		<?php endif ?>
												    		<?php if ($decodedAct->{'acttooltiptext'} != ""):?>
												    		<div class="sy-tooltipfield">
												    			<?php echo $decodedAct->{'acttooltiptext'} ?>
												    		</div>
												    		<?php endif ?>
												    	</div>
												    </div>
											<?php	}// fin if id_cell == idul
											}// fin for arrayplanAct
										}// fin if $data[0]->scheduledact ?>
						   			</ul>	
						   		<?php }// fin for $cell
						   	?>
					   	</div>
			   		</div>  					
	   				<?php } // fin for $number ?>
			</div> <!-- fin fullplanning -->
			<?php $variationCount++; ?>
			<?php endforeach ?>
		</div>
		<div class="sy-tooltip-print-container" style="display:none;">
			<div class="sy-tooltip-print-content"></div>
		</div>
		
		<?php endif ?> <!-- fin if ($model[0]->type == 0) -->
		<script type="text/javascript">
		//Créé une class avec les styles de l'activité pour le tooltip de des activités
			function createClass(name,rules, data){
			    var style = document.createElement('style');
			    style.type = 'text/css';
			    document.getElementsByTagName('head')[0].appendChild(style);
			    if(!(style.sheet||{}).insertRule) 
			        (style.styleSheet || style.sheet).addRule(name, rules, data);
			    else
			        style.sheet.insertRule(name+"{"+rules+":"+data+"}",0);
			}

			var easyId = jQuery('.fullplanning').attr('data-id');

			var descOpt = {
	            closeText: "x",
	            autoOpen: false,
	            width: 500,
	            modal: true,
	            show: {
	            	effect: "drop",
	            	direction: "up",
	            	duration: 200
	            },
	            hide: {
	            	effect: "drop",
	            	direction: "up",
	            	duration: 200
	            },
	            open:function () {
	            	jQuery(".ui-dialog ").addClass("syet-description");
		        }
	            
	        };
	        jQuery('.easytimetable-'+easyId).find('ul').each(function(){
	        	if (jQuery(this).find('li').length == 0)
	        	{
	        		//On signale que la ul est vide
	        		jQuery(this).addClass('empty');
	        	}
	        	else {
	        		//On signale que la ul est pleine
	        		jQuery(this).addClass('full');
	        		var timeH = jQuery(this).find('.time').height();
	        		var nameH = jQuery(this).find('.acttitle').height();
	        		var infoH = jQuery(this).find('.sy-actincelltext').height();
	        		var thisH = jQuery(this).height();

	        		//ON calcule la hauteur du contenu
	        		var contentH = timeH + nameH + infoH;
	        		//SI la hauteur du contenu est plus gd que la hauteur de la ul on n'affiche pas le contenu
	        		if(contentH > thisH) {
	        			jQuery(this).find('.sy-actincelltext').css('display', 'none');
	        		}
	        	}
	        })

	        //debut merge
			jQuery('.creneau').find('li').each(function(){
				// si cellule double
				if (jQuery(this).attr('data-merge')== 1)
				{
					// on ajoute le style double à la 'li'
					jQuery(this).addClass('sy-double');

					// on ajoute float left à la première
					if (jQuery(this).index() == 0) {
						jQuery(this).css('float', 'left');
					}
					// et float right à la deuxième
					else 
					{
						jQuery(this).css('float', 'right');
					}	
					if (jQuery(this).find('.acttitle').text().length > 11) {
						var longname = jQuery(this).find('.acttitle').text();
						var smallName = jQuery(this).find('.acttitle').text().substr(0,10) + "...";
						jQuery(this).find('.acttitle').text(smallName);
					}
				}
			})// fin merge


			jQuery('.easytimetable-'+easyId).find('li').each(function(){
				var thisWidth = jQuery(this).css("width")+ " !important";
				jQuery('.sy-infofields-'+thisIdCell).css('width',thisWidth);
				var thisId = "#" + jQuery(this).attr('data-li-id');
				var thisIdCell = jQuery(this).attr('data-idcell');
				var thisClass = "." + jQuery(this).attr('data-li-id');
				var thisColor = "#" +  jQuery(this).attr('data-color')+ " !important";
				var thisFontColor = "#" +  jQuery(this).attr('data-fontcolor')+ " !important";
				var thisFontOColor = "#" +  jQuery(this).attr('data-fontovercolor')+ " !important";
				var bgover = "#" + jQuery(this).attr('data-overcolor') + " !important";
				var showdesc = jQuery(this).attr('data-showdesc');
				var imgtooltip = jQuery(this).attr('data-imgtooltip');
				
				// lance la dialog de description si showdesc == 1
				if (showdesc == 1)
				{
					jQuery(this).addClass('sy-displaydesc');
					var thisDesc = jQuery(this).find('.sy-description-'+thisIdCell).dialog(descOpt);
					jQuery(this).on('click', function(){
						thisDesc.dialog('open');
						jQuery('body').addClass('nooverflow'); 
					})
				}

				// Lance le tooltip
				if ((jQuery(this).attr('data-image') != "" && imgtooltip == 1) || jQuery(this).attr('data-tooltiptext') != "")
				{
					jQuery(thisId).tooltipster({
						trigger: "hover",
						delay:0,
						theme: 'tooltipster-borderless'
					});
				}
				
				jQuery(this).on('mouseover', function(){
					createClass(thisClass,"background",bgover);
					createClass(".arrowborder","border-top-color",bgover);
					jQuery('.tooltipster-base').find('.tooltipster-box').addClass(jQuery(this).attr('data-li-id'));
					jQuery('.tooltipster-arrow').find('.tooltipster-arrow-background').addClass("arrowborder");
					jQuery('.tooltipster-arrow').find('.tooltipster-arrow-border').addClass("arrowborder");
				})

				jQuery(this).on('mouseenter', function(){
        			jQuery(this).css('background-color', bgover);
        			jQuery(this).css('color', thisFontOColor);
        		});
        		jQuery(this).on('mouseleave', function(){
        			jQuery(this).css('background-color', thisColor);
        			jQuery(this).css('color', thisFontColor);
        		});
			});

			// On duplique et on prépare les plannings à imprimer 
			function syprint(variation)
			{	
				var scaledElement = jQuery("#easytimetable-"+ variation).clone().css({
					'transform': 'scale(3,3)',
					'-ms-transform': 'scale(3,3)',
					'-webkit-transform': 'scale(3,3)'
				}).addClass("scaled-element");
				
				jQuery("body").append("<div style='display:none;width:200%' data-to-print='"+ variation +"' class='sy-variation-to-print sy-print-container-"+ variation +"'><div class='sy-thisprint'>"+scaledElement.html()+"</div></div>");
				var timetableTitle = jQuery('.timetable-title').text();
				jQuery('.sy-print-container-'+ variation).find('.sy-thisprint').prepend("<h1>"+timetableTitle+"</h1>");
				
				var ulHeight = jQuery('.sy-print-container-'+ variation).find('.sy-thisprint').find('ul').height() * 3;
				//console.log("ulHeight " + ulHeight);
				jQuery('.sy-print-container-'+ variation).find('.sy-thisprint').find('ul').each(function(){
					jQuery(this).height(ulHeight);
					jQuery(this).find('.acttitle').css("font-size", "38px");
					jQuery(this).find('.time').css("font-size", "38px");
					jQuery(this).css("margin", "5px !important");
					jQuery(this).find('.sy-actincelltext').css("padding", "20px");
					jQuery(this).find('.sy-actincelltext').css("font-size", "30px");
					jQuery(this).find('.sy-actincelltext').css("line-height", "42px");
				}); 
				jQuery('.sy-print-container-'+ variation).find('.sy-thisprint').find('.col-title').each(function(){
					jQuery(this).css("font-size", "38px");
					jQuery(this).css("text-align", "center");
				}); 

				
			}
			// On récupère les infos des planning à imprimer
			function getvariation()
			{
				jQuery(".timetable-container").find(".fullplanning").each(function()
				{
					if (!jQuery(this).hasClass("invisible"))
					{
						//console.log("l'id de la variation : " + jQuery(this).attr("id"));
						var variation = parseInt(jQuery(this).attr('data-variation'));
						syprint(variation);
					}
				})
			}
			getvariation();

			// Le tooltip du bouton "print"
			jQuery(".sy-print.sy-button").tooltipster({
				interactive:true,
				trigger: 'custom',
				triggerOpen: {
			        mouseenter: true
			    },
			    triggerClose: {
			        click: true
			    },
				contentAsHTML: true,
				theme: "tooltipster-light"
			});
			
			// l'action print
			function printNow(schedule) {
				jQuery('.sy-print-container-' + schedule).css('display', 'block');
					html2canvas(jQuery('.sy-print-container-' + schedule).find('.sy-thisprint'), {
				    	useCORS: true,
						allowTaint: true,
						letterRendering: true,
	  					onrendered: function(canvas) {
	  						var w = window.open();
	    					//w.document.write("<h3 style='text-align:center;'>Titre</h3>");
					      	w.document.write("<div style='width:100%;'><img style='margin: 0 auto;' width='100%' src='"+canvas.toDataURL()+"' /></div>");
					      	w.print();
				      	}
				    });
				    jQuery('.sy-print-container-' + schedule).css('display', 'none');
			}
			
			// La mise à jour du contenu du tooltip du bouton "print"
			function tooltipPrint()
			{
				var thecount = 1;
				
				var chooseItem = "";
				jQuery('body').find('.sy-variation-to-print').each(function(){
					console.log(countPrint);
					var countPrint = jQuery(this).attr("data-to-print");
					chooseItem += "<div class='sy-choose-print sy-button button' data-to-print='" + countPrint + "'>Timetable " + thecount + "</div>";
					console.log(chooseItem);
					thecount++;
				})
				chooseItem += "<script>jQuery('.sy-choose-print').click(function(){var theItem = parseInt(jQuery(this).attr('data-to-print')); printNow(theItem);})<\/script>"
				console.log(chooseItem);
				jQuery('.sy-print.sy-button').tooltipster('content', chooseItem);				
			}
			tooltipPrint();

			//Schedule Navigation
			jQuery(".next-variation").click(function()
			{
				var varNumber = parseInt(jQuery(this).attr("data-variation"));
				var thisweek = parseInt(jQuery(this).attr("data-thisweek"));
				var nextWeek = thisweek + 1;
				// Fonction pour récupérer le jour correspondant à la semaine et à la colonne
				function getDateOfWeek(day, w, y) {
				    var d = (1 + (w - 1) * 7); // 1st of January + 7 days for each week
				    d += day;
			    	return new Date(y, 0, d);
				}
				jQuery(".timetable-container").find(".fullplanning").each(function()
				{
					var arrayWeeks = jQuery(this).attr("data-weeks").split(",");

					if (arrayWeeks.indexOf(nextWeek.toString()) != -1)
					{
						
						jQuery(this).removeClass("invisible");
					}
					else
					{
						jQuery(this).addClass("invisible");
						
					}
					if (!jQuery(this).hasClass("invisible"))
					{
						jQuery(this).find(".col-title").each(function()
						{
							var colNumber = jQuery(this).parent(".rows").attr("id");

							var thizDay = parseInt(jQuery(this).find(".sy-dayname").attr("data-day"));

							var thisYear = new Date();
							thisYear = thisYear.getFullYear();
							var difference = parseInt(colNumber) - thizDay;

							if (difference > 0)
							{
								thizDay += 7;
							}
							var myDay = getDateOfWeek(thizDay, nextWeek, thisYear);
							var syDay, syMonth, syYear;
							if (myDay.getDate() < 10)
							{
								syDay = "0" + myDay.getDate();
							}
							else 
							{
								syDay = myDay.getDate();
							}
							if ((myDay.getMonth() +1) < 10)
							{
								syMonth = "0" + (myDay.getMonth() + 1);
							}
							else 
							{
								syMonth = myDay.getMonth() + 1;
							}
							var finalDay = syDay + "." + syMonth + "." + myDay.getFullYear();
							jQuery(this).find(".sy-thedate").html(finalDay);
						})
					}
				})
				jQuery(this).attr("data-thisweek", nextWeek);
				jQuery(".prev-variation").attr("data-thisweek", nextWeek);
				jQuery(".sy-variation-to-print").remove();
				getvariation();
				tooltipPrint();
			})

			jQuery(".prev-variation").click(function()
			{
				var varNumber = parseInt(jQuery(this).attr("data-variation"));
				var thisweek = parseInt(jQuery(this).attr("data-thisweek"));
				var prevWeek = thisweek - 1;
				// Fonction pour récupérer le jour correspondant à la semaine et à la colonne
				function getDateOfWeek(day, w, y) {
				    var d = (1 + (w - 1) * 7); // 
				    d += day;
			    	return new Date(y, 0, d);
				}
				jQuery(".timetable-container").find(".fullplanning").each(function()
				{
					var arrayWeeks = jQuery(this).attr("data-weeks").split(",");

					if (arrayWeeks.indexOf(prevWeek.toString()) != -1)
					{
						
						jQuery(this).removeClass("invisible");
					}
					else
					{
						jQuery(this).addClass("invisible");
						
					}
					if (!jQuery(this).hasClass("invisible"))
					{
						jQuery(this).find(".col-title").each(function()
						{
							var colNumber = jQuery(this).parent(".rows").attr("id");
							var thizDay = parseInt(jQuery(this).find(".sy-dayname").attr("data-day"));

							var thisYear = new Date();
							thisYear = thisYear.getFullYear();
							var difference = parseInt(colNumber) - thizDay;

							if (difference > 0)
							{
								thizDay += 7;
							}
							var myDay = getDateOfWeek(thizDay, prevWeek, thisYear);
							var syDay, syMonth, syYear;
							if (myDay.getDate() < 10)
							{
								syDay = "0" + myDay.getDate();
							}
							else 
							{
								syDay = myDay.getDate();
							}
							if ((myDay.getMonth() +1) < 10)
							{
								syMonth = "0" + (myDay.getMonth() + 1);
							}
							else 
							{
								syMonth = myDay.getMonth() + 1;
							}
							var finalDay = syDay + "." + syMonth + "." + myDay.getFullYear();
							jQuery(this).find(".sy-thedate").html(finalDay);
						})
					}
				})

				jQuery(this).attr("data-thisweek", prevWeek);
				jQuery(".next-variation").attr("data-thisweek", prevWeek);
				jQuery(".sy-variation-to-print").remove();
				getvariation();
				tooltipPrint();
			})
			
		</script>
	<?php }
}