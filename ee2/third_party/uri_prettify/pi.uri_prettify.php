<?php

  /*
   ========================================================
   URI Prettify
   --------------------------------------------------------
   Author: Jack McDade
   http://jackmcdade.com
   --------------------------------------------------------
   Do whatever you want with this plugin. It's probably
   one of the smallest in history.
   ========================================================
   File: pi.uri_prettify.php
   --------------------------------------------------------
   Purpose: Rewrites URI segments as Pretty Titles. E.g.:
   "street_photography" becomes "Street Photography". 
   Useful for displaying current category without tons of
   logic, or other similar situations. Won't work in all
   situations of course. Good luck.
   ========================================================
   
   */


$plugin_info = array(
	'pi_name'			=> 'URI Prettify',
	'pi_version'		=> '1.2',
	'pi_author'			=> 'Jack McDade',
	'pi_author_url'		=> 'http://jackmcdade.com/',
	'pi_description'	=> 'Turns URI segments or similarly delimited strings into Pretty Titles',
	'pi_usage'			=> Uri_prettify::usage()
);

Class Uri_prettify
{	
	var $return_data = '';

	function Uri_prettify($str = '')
	{	
		
		$this->EE =& get_instance();
		
		$default_keywords = 'a|an|the|to|and|as|but|for|if|nor|once|or|so|than|that|till|when|yet|at|by|down|for|from|in|into|like|near|of|off|on|onto|over|past|to|upon|with|is';
		$keywords = explode("|", $this->EE->TMPL->fetch_param('keywords', $default_keywords));
		$case = $this->EE->TMPL->fetch_param('case', 'title');

		$lowered = array();
		foreach ($keywords as $keyword)
			$lowered[] = ucwords(strtolower($keyword));
		
		$keywords = $lowered;

		$str = $this->EE->TMPL->tagdata;

		$str = str_replace('_', ' ', $str);
		$str = str_replace('-', ' ', $str);
		$str = trim($str);
		
		if ($case == 'sentence')
		{
			$str = ucfirst(strtolower($str));
		}
		else
		{
			$str = ucwords(strtolower($str));
			// Don't capitalize articles, prepositions and 
			// coordinate conjunctions unless they're the first word 	
			if ($this->EE->TMPL->fetch_param('uncap_keywords', FALSE) !== FALSE)
			{
				foreach ($keywords as $keyword)
				{
					$str = str_replace(' '.$keyword, ' '.strtolower($keyword), $str);
				}
			}
		}
		
		$this->return_data = $str;

	}

	
	// ----------------------------------------
	//  Plugin Usage
	// ----------------------------------------

	function usage()
	{
	ob_start(); 
	?>
	Wrap anything you want to be processed between the tag pairs. Useful for {segment} variables, filenames, and other "_" and "-" separated strings.

	{exp:uri_prettify}

	text you want processed

	{/exp:uri_prettify}

	Example: today_i_win_the_game
	Results: Today I Win the Game


	Parameters: 

	===============================================================
	
	uncap_keywords="yes" (defaults to "no")
	Uncapitalized certain words for proper grammar's sake. E.g. articles, prepositions and coordinate conjunctions. Will keep them capitalized if they're at the start of a sentence (not prefaced with a space). Default keywords are: "and", "to", "with", "for", "the" and "or".
	
	case="title|sentence" (defaults to "title")
	Would you like Title case (all words) or Sentence case (only first word)? Your choice.

	===============================================================
	
	keywords="word1|word2|word3"
	
	Override the default list of words with your own. Words must be separated by pipe ("|") delimiters.

	<?php
	$buffer = ob_get_contents();

	ob_end_clean(); 

	return $buffer;
	}

}
?>
