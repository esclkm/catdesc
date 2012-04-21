<!-- BEGIN: MAIN -->
<h2><span>{PHP.L.Category}: <a href="{CATDESC_CATURL}">{CATDESC_CATTITLE}</a></span></h2>

<form name="catdescform" id="catdescform" action="{CATDESC_ACTION}" method="POST"  enctype="multipart/form-data">
	<table class="cells">
		<tr>
			<td>{PHP.L.Title}:</td>
			<td>{CATDESC_EDIT_TITLE}</td>
		</tr>
		<tr>
			<td>{PHP.L.Description}:</td>
			<td>{CATDESC_EDIT_DESC}</td>
		</tr>
		<tr>
			<td>{PHP.L.Icon}:</td>
			<td>{CATDESC_EDIT_ICON}</td>
		</tr>
		<tr>
			<td>{PHP.L.Locked}:</td>
			<td>{CATDESC_EDIT_LOCKED}</td>
		</tr>
		<tr>
			<td>{PHP.L.catdesc_avatar}:</td>
			<td>
				{CATDESC_EDIT_AVATARFILE}<br />
				{CATDESC_EDIT_AVATAR}<br />
				{PHP.L.Delete} {CATDESC_EDIT_AVATARDELETE}
			</td>
		</tr>
		<tr>
			<td colspan="2">{PHP.L.Text} 1:{CATDESC_EDIT_KEYWORDS}</td>
		</tr>
		<tr>
			<td colspan="2">{PHP.L.Text} 2:{CATDESC_EDIT_TEXT}</td>
		</tr>
		<!-- BEGIN: EXTRAFLD -->
		<tr>
			<td>{CATDESC_EDIT_EXTRAFLD_TITLE}:</td>
			<td>{CATDESC_EDIT_EXTRAFLD}</td>
		</tr>
		<!-- END: EXTRAFLD -->
		<tr>
			<td colspan="2" class="valid"><button type="submit">{PHP.L.Submit}</button></td>
		</tr>
	</table>
	<p class="paging">
		<a href="{CATDESC_EDIT_CONFIG_URL}" class="button">{PHP.L.Configuration}</a>
		<a href="{CATDESC_EDIT_RIGHTS_URL}">{PHP.L.Rights}</a>
		<a href="{ADMIN_STRUCTURE_OPTIONS_URL}">{PHP.L.Options}</a>
	</p>
</form>

<!-- END: MAIN -->