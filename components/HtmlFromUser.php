<?php

namespace app\components;

class HtmlFromUser extends \yii\helpers\Html {
	public static function replace_urls($args){
		
		$is_url = preg_match('/^(http:\/\/)|(https:\/\/)|(vk\.com)/', $args[0]);
		
		if($is_url){
			$url = $args[0];
			if(!preg_match('%^http(s?):\/\/%', $url))
				$url = 'http://' . $url;
				
			return '<noindex><a target="_blank" href="'.$url.'">'.$url.'</a></noindex>';
		}
		
		return $args[0];
	}

	public static function metatags($in){
		
		$enabled_tags = ['p', 'em', 'u', 'strong', 's'];

		$out = $in;
		$out = preg_replace_callback('/[a-zA-Zа-яА-Я0-9:\/\._-]*/', ['\app\components\HtmlFromUser', 'replace_urls'], $out);
		
		foreach($enabled_tags as $tag){
			$out = preg_replace('/&lt;'.$tag.'&gt;/', '<'.$tag.'>', $out);
			$out = preg_replace('/&lt;\/'.$tag.'&gt;/', '</'.$tag.'>', $out);
		}
		
		$out = preg_replace('/&lt;hr \/&gt;/', '<hr />', $out);
		$out = preg_replace('/&amp;quot;/', '&quot;', $out);
		$out = preg_replace('/&amp;nbsp;/', '&nbsp;', $out);

		return $out;
		
	}
	
	public static function encode($in){
		return self::metatags(parent::encode($in));
	}
}