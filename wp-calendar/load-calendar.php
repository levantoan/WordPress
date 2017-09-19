<?php
add_action( 'wp_ajax_LoadCalendar', 'LoadCalendar_init' );
add_action( 'wp_ajax_nopriv_LoadCalendar', 'LoadCalendar_init' );
function LoadCalendar_init() {	 
   	extract($_POST);
	$calendar = draw_calendar($data_month,$data_year);
	echo json_encode($calendar);
	exit;
}
/* draws a calendar */
function draw_calendar($month = 9,$year = 2015){
	switch ($month) {
	    case 1:
	        $text_m = 'January';
	        break;
	    case 2:
	        $text_m = 'February';
	        break;
	    case 3:
	        $text_m = 'March';
	        break;
	    case 4:
	        $text_m = 'April';
	        break;
	    case 5:
	        $text_m = 'May';
	        break;
	    case 6:
	        $text_m = 'June';
	        break;
	    case 7:
	        $text_m = 'July';
	        break;
	    case 8:
	        $text_m = 'August';
	        break;
	    case 9:
	        $text_m = 'September';
	        break;
	    case 10:
	        $text_m = 'October';
	        break;
	    case 11:
	        $text_m = 'November';
	        break;
	    case 12:
	        $text_m = 'December';
	        break;
	}
	
	$prev_month = ($month) ? ($month - 1) : '';
	if($prev_month <= 0 ){
		$prev_month = 12;
		$prev_year = ($year) ? ($year - 1) : '';
	}else{
		$prev_year = $year;
	}
	
	$next_month = ($month) ? ($month + 1) : '';
	if($next_month > 12 ){
		$next_month = 1;
		$next_year = ($year) ? ($year + 1) : '';
	}else{
		$next_year = $year;
	}
	
	$calendar = '<div class="calendar_wrap">';
	$old_query_or_uri = $_SERVER['REQUEST_URI'];
	$old_query_or_uri = esc_url( remove_query_arg( array('dateevent','monthevent','calendar_year'), $old_query_or_uri ) );
	
	$month_link = esc_url(home_url('/events/month/?tribe-bar-date='.$year.'-'.$month));
	
	$prev_link = esc_url(home_url('/events/'.$prev_year.'-'.sprintf('%02d',$prev_month)));
	$next_link = esc_url(home_url('/events/'.$next_year.'-'.sprintf('%02d',$next_month)));
	
	$noncePrevNext = wp_create_nonce("prev_next_month");
	$calendar .= '<div class="head">
			<a class="fa fa-chevron-circle-left prev-date" data-action="LoadCalendar" data-year="'.$prev_year.'" data-month="'.sprintf('%02d',$prev_month).'" href="'.$prev_link.'"></a>			
			<h2><a href="'.$month_link.'" title="All event on '.$text_m.' '.$year.'">'.$text_m.' '.$year.'</a></h2>
			<a class="fa  fa-chevron-circle-right next-date" data-action="LoadCalendar" data-year="'.$next_year.'" data-month="'.sprintf('%02d',$next_month).'" href="'.$next_link.'"></a>
		</div>';
	/* draw table */
	$calendar .= '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	$shows = '';
	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$active = '';
		/*for($i=0;$i<count($shows);$i++) {
			$date_s = date("j/n/Y", strtotime($shows[$i]->ActualEventDate));
			$date_c = $list_day.'/'.$month.'/'.$year;
			$date_link = $year.'-'.$month.'-'.$list_day;
			if($date_s==$date_c) $active = 'active';
		}*/
		//echo $year.'-'.$month.'-'.$list_day;
		$events = call_events_by_date($year.'-'.$month.'-'.$list_day);
		//echo $events;
		if($events) $active = 'active';
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			if($active) {
				$old_query_or_uri = $_SERVER['REQUEST_URI'];
				$day_link = esc_url(home_url('/events/'.$year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$list_day)));
				$calendar.= '<a target="_top" href="'.$day_link.'"><span>'.$list_day.'</span></a>';
				$calendar.= '<div class="cale_table_list"><ul>';
				foreach ($events as $event_list):
					$calendar.= '<li>'.$event_list.'</li>';
				endforeach;
				$calendar.= '</ul></div>';
			} else {
				$calendar.= ''.$list_day.'</div>';
			}

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table></div>';
	
	/* all done, return result */
	return $calendar;
}

//Call post on day
function call_events_by_date($date = ''){
	//ob_start();
	global $post;
	$events = tribe_get_events(
		array(
			'eventDisplay'=>'day',
			'eventDate'	=>	date($date)
		)
	);	
	if(is_array($events)){
		foreach ( $events as $post ) {
		    setup_postdata( $post );		 
		    $list_event[] = '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail($post->ID,'events-thumb').get_the_title().'</a>';
		}
	}
	return $list_event;
}
