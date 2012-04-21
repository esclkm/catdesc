<?php

/*
 * [BEGIN_COT_EXT]
 * Hooks=structure.delete
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');
if ($module == 'page')
{
	require_once cot_incfile('extrafields');
	require_once cot_incfile('catdesc', 'plug');
	global $catd_set, $structure;

	$filename = $catd_set['path'].$structure['page'][$code]['avatar'];
	if (file_exists($filename))
	{
		@unlink($filename);
	}
	foreach ($catd_set['thumbs'] as $key => $val)
	{
		$newfilename = $catd_set['path'].$key.$structure['page'][$code]['avatar'];
		if (file_exists($newfilename))
		{
			@unlink($newfilename);
		}
	}
}

?>