<?php

/*
 * [BEGIN_COT_EXT]
 * Hooks=standalone
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');

$c = cot_import('c', 'G', 'ALP');

require_once cot_incfile('extrafields');
require_once cot_incfile('catdesc', 'plug');

cot_block(cot_auth('page', $c, 'A'));
$res = $db->query("SELECT * FROM $db_structure WHERE structure_code = '$c' AND structure_area='page'");
$catt = $res->fetch();
$out['subtitle'] = $L['Edit'].'-'.htmlspecialchars($catt["structure_title"]);
if ($a == 'update')
{
	global $catd_set, $structureavatar;

	$structureavatar = (isset($_FILES['structureavatar']) && ($_FILES['structureavatar']['error'] == UPLOAD_ERR_OK) ) ? $_FILES['structureavatar'] : null;

	if (!empty($structureavatar["name"]))
	{
		$file_ext = mb_strtolower(end(explode(".", $structureavatar["name"])));
		if ($structureavatar['error'] == UPLOAD_ERR_OK)
		{
			if (!in_array($file_ext, $catd_set['ext']))
			{
				$structureavatar = null;
			}
			if ($catd_set['max'] > 0 && $structureavatar['size'] * 1024 * 1024 > $catd_set['max'])
			{
				$structureavatar = null;
			}
		}
		else
		{
			$structureavatar = null;
		}
	}

	$rstructure['structure_text'] = cot_import('rstructuretext', 'P', 'HTM');
	$rstructure['structure_title'] = cot_import('rstructuretitle', 'P', 'TXT');
	$rstructure['structure_desc'] = cot_import('rstructuredesc', 'P', 'TXT');
	$rstructure['structure_icon'] = cot_import('rstructureicon', 'P', 'TXT');

	$rstructure['structure_locked'] = (cot_import('rstructurelocked', 'P', 'BOL')) ? 1 : 0;


	foreach ($cot_extrafields[$db_structure] as $row)
	{
		$rstructure['structure_'.$row['field_name']] = cot_import_extrafields('rstructure'.$row['field_name'], $row, 'P', $catt['structure_'.$row['field_name']]);
	}

	if (empty($rstructure['structure_title']))
	{
		$rstructure['structure_title'] = $catt["structure_title"];
	}

	if (cot_import('rstructureavatardelete', 'P', 'BOL'))
	{
		$rstructureavatar = cot_import('rstructureavatar', 'P', 'TXT');
		$filename = $catd_set['path'].$rstructureavatar;
		if (file_exists($filename))
		{
			@unlink($filename);
		}
		foreach ($catd_set['thumbs'] as $key => $val)
		{
			$newfilename = $catd_set['path'].$key.$rstructureavatar;
			if (file_exists($newfilename))
			{
				@unlink($newfilename);
			}
		}
		$rstructure['structure_avatar'] = '';
	}

	if (!empty($structureavatar["name"]))
	{
		$filename = $catd_set['path']."structure_".$c.".".$file_ext;
		if (file_exists($filename))
		{
			@unlink($filename);
		}
		move_uploaded_file($structureavatar["tmp_name"], $filename);

		if (file_exists($filename) && in_array($file_ext, array('jpg', 'jpeg', 'png', 'gif')))
		{
			foreach ($catd_set['thumbs'] as $key => $val)
			{
				$newfilename = $catd_set['path'].$key."structure_".$c.".".$file_ext;
				if (file_exists($newfilename))
				{
					@unlink($newfilename);
				}
				cot_thumb($filename, $newfilename, $val['x'], $val['y'], $val['set']);
			}
		}
		$rstructure['structure_avatar'] = 'structure_'.$c.'.'.$file_ext;
	}


	$db->update($db_structure, $rstructure, "structure_code = '$c' AND structure_area='page'");

	cot_extrafield_movefiles();
	if ($cache)
	{
		$cache->clear();
	}

	cot_redirect(cot_url('index', "e=page&c=".$c, '', true));
	exit;
}


require_once cot_incfile('forms');

foreach ($cot_extrafields[$db_structure] as $i => $catt2)
{
	if ($catt2['field_name'] != 'avatar')
	{
		$t->assign('CATDESC_EDIT_'.strtoupper($catt2['field_name']).'_TITLE', isset($L['structure_'.$catt2['field_name'].'_title']) ? $L['structure_'.$catt2['field_name'].'_title'] : $catt2['field_description']);
		$t->assign('CATDESC_EDIT_'.strtoupper($catt2['field_name']), cot_build_extrafields('rstructure'.$catt2['field_name'].'', $catt2, $catt['structure_'.$catt2['field_name']]));


		// extra fields universal tags
		$t->assign('CATDESC_EDIT_EXTRAFLD', cot_build_extrafields('rstructure'.$catt2['field_name'].'', $catt2, $catt['structure_'.$catt2['field_name']]));
		$t->assign('CATDESC_EDIT_EXTRAFLD_TITLE', isset($L['structure_'.$catt2['field_name'].'_title']) ? $L['structure_'.$catt2['field_name'].'_title'] : $catt2['field_description']);
		$t->parse('MAIN.EXTRAFLD');
	}
}

$t->assign(array(
	'CATDESC_TITLE' => $L['plu_title'],
	'CATDESC_CATTITLE' => htmlspecialchars($catt["structure_title"]),
	'CATDESC_CATURL' => cot_url('index', "e=page&c=$c"),
	'CATDESC_ACTION' => cot_url('plug', "e=catdesc&c=$c&a=update"),
	'CATDESC_EDIT_TEXT' => cot_textarea('rstructuretext', $catt["structure_text"], 15, 50, '', 'input_textarea_editor'),
	'CATDESC_EDIT_TITLE' => cot_inputbox('text', 'rstructuretitle', $catt['structure_title'], 'size="32" maxlength="255"'),
	'CATDESC_EDIT_DESC' => cot_inputbox('text', 'rstructuredesc', $catt['structure_desc'], 'size="64" maxlength="255"'),
	'CATDESC_EDIT_ICON' => cot_inputbox('text', 'rstructureicon', $catt['structure_icon'], 'size="64" maxlength="128"'),
	'CATDESC_EDIT_LOCKED' => cot_checkbox($catt['structure_locked'], 'rstructurelocked'),
	'CATDESC_EDIT_COUNT' => $catt['structure_count'],
	'CATDESC_EDIT_RIGHTS_URL' => cot_url('admin', 'm=rightsbyitem&ic='.$n.'&io='.$structure_code),
	'CATDESC_EDIT_OPTIONS_URL' => cot_url('admin', 'm=structure&n='.$n.'&id='.$structure_id.'&'.cot_xg()),
	'CATDESC_EDIT_CONFIG_URL' => cot_url('admin', 'm=config&n=edit&o=module&p='.$n.'&sub='.$structure_code),
	'CATDESC_EDIT_AVATARFILE' => $catt['structure_avatar'],
	'CATDESC_EDIT_AVATAR' => cot_inputbox('hidden', 'rstructureavatar', $catt['structure_avatar'])
	.cot_inputbox('file', 'structureavatar', '', 'class="file" size="56"'),
	'CATDESC_EDIT_AVATARDELETE' => cot_radiobox(0, 'rstructureavatardelete', array(1, 0), array($L['Yes'], $L['No']))
));
?>