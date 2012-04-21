<?php

/*
 * [BEGIN_COT_EXT]
 * Hooks=page.list.tags
 * Tags=page.list.tpl:{LIST_TEXT},{LIST_TEXTEDIT}
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL.');
require_once(cot_langfile('catdesc'));

$catd_res = $db->query("SELECT structure_text FROM $db_structure WHERE structure_code = '$c' AND structure_area='page'");

if ($catd = $catd_res->fetch())
{
	$t->assign(array(
		'LIST_TEXT' => cot_parse($catd['structure_text'], true),
		'LIST_TEXTEDIT' => cot_auth('page', $c, 'A') ? '<a href="'.cot_url('plug', 'e=catdesc&c='.$c).'">'.$L['catdesc_edit'].'</a>' : ''
	));
}

if (!empty($structure['page'][$c]['seealso']))
{
	$seealso = trim(str_replace(array(' , ', ', ', ' ,'), ',', $structure['page'][$c]['seealso']));
	$seealso = explode(',', $seealso);
	$see_i = 0;
	foreach ($seealso as $seek => $seev)
	{
		if (isset($structure['page'][$seev]))
		{
			$see_i++;
			$t->assign(array(
				'LIST_ROWCAT_URL' => cot_url('page', array('c' => $seev)),
				'LIST_ROWCAT_TITLE' => $structure['page'][$seev]['title'],
				'LIST_ROWCAT_DESC' => $structure['page'][$seev]['desc'],
				'LIST_ROWCAT_ICON' => $structure['page'][$seev]['icon'],
				'LIST_ROWCAT_COUNT' => $sub_count,
				'LIST_ROWCAT_ODDEVEN' => cot_build_oddeven($see_i),
				'LIST_ROWCAT_NUM' => $see_i
			));

			// Extra fields for structure
			foreach ($cot_extrafields[$db_structure] as $row_c)
			{
				$uname = strtoupper($row_c['field_name']);
				$t->assign('LIST_ROWCAT_'.$uname.'_TITLE', isset($L['structure_'.$row_c['field_name'].'_title']) ? $L['structure_'.$row_c['field_name'].'_title'] : $row_c['field_description']);
				$t->assign('LIST_ROWCAT_'.$uname, cot_build_extrafields_data('structure', $row_c, $structure['page'][$seev][$row_c['field_name']]));
			}

			$t->parse('MAIN.SEEALSO.SEEROW');
		}
	}
	if ($see_i > 0)
	{
		$t->parse('MAIN.SEEALSO');
	}
}
?>