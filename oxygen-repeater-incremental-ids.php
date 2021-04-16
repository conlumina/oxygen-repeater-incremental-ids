<?php

add_filter( 'do_shortcode_tag', function ( $output, $tag, $attr ) { 
	
	if ( in_array($tag, ['oxy_dynamic_list_3']) ) {
		$query = '//*[@id]/*';
		$output = rename_element_id($query, $output);
	}
	
	return $output;
	
}, 10, 3);

function rename_element_id($query, $output) {
	
	$dom = new DOMDocument();
	$dom->loadHTML(mb_convert_encoding( $output, 'HTML-ENTITIES', 'UTF-8') );
	$xpath = new DOMXPath($dom);
	$elements = $xpath->query($query);
	
	$counter = 1;
	$attribute = 'id';
	$repeater_id = $elements[0]->parentNode->getAttribute($attribute);
	
	foreach( $elements as $element ) {
		
		$parent_id = $element->parentNode->getAttribute($attribute);
		
		if ( $element->hasAttribute($attribute) ) {
			$new_id = $element->getAttribute($attribute) . '-' . ( ($parent_id == $repeater_id) ? $counter : ($counter - 1) );
			$element->removeAttribute($attribute);
	    	$element->setAttribute($attribute, $new_id);
		}
		
		if ( $parent_id == $repeater_id ) $counter++;
	}
	
	return $dom->saveHTML();
}

?>