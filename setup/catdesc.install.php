<?php
defined('COT_CODE') or die('Wrong URL');

global $db_structure;

if ($db->query("SHOW COLUMNS FROM $db_structure WHERE Field = 'structure_text'")->rowCount() == 0)
{
	$db->query("ALTER TABLE $db_structure ADD structure_text TEXT");
}
if ($db->query("SHOW COLUMNS FROM $db_structure WHERE Field = 'structure_keywords'")->rowCount() == 0)
{
	$db->query("ALTER TABLE $db_structure ADD structure_keywords TEXT");
}	
cot_extrafield_add($db_structure, 'avatar', 'input', $R['input_text'], '', '', false, 'HTML', 'Avatar');
cot_extrafield_add($db_structure, 'seealso', 'input', $R['input_text'], '', '', false, 'HTML', 'See also');

?>
