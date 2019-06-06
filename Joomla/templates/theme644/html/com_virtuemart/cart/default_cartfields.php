<?php
// Status Of Delimiter
$closeDelimiter = false;
$openTable = true;
$hiddenFields = '';

if(!empty($this->userFieldsCart['fields'])) {

	// Output: Userfields
	foreach($this->userFieldsCart['fields'] as $field) {
	?>
	<fieldset class="vm-fieldset-<?php echo str_replace('_','-',$field['name']) ?>">
		<div  class="control-group">
		<div class="control-label"><label for="<?php echo $field['name']; ?>" class="cart <?php echo str_replace('_','-',$field['name']) ?>" ><?php echo $field['title']; ?></label></div>

		<?php
		if ($field['hidden'] == true) {
			// We collect all hidden fields
			// and output them at the end
			$hiddenFields .= $field['formcode'] . "\n";
		} else { ?>
				<div class="controls"><?php echo $field['formcode']; ?></div>
			</div>
	<?php } ?>

	</fieldset>

	<?php
	}
	// Output: Hidden Fields
	echo $hiddenFields;
}
?>