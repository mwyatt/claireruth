<div class="clearfix">
	<label class="h5 block mb05" for="form_html">Content</label>
	<div id="toolbar" class="toolbar clearfix" style="display: none;">
		<a class="button" data-wysihtml5-command="bold" title="CTRL+B">bold</a>
		<a class="button" data-wysihtml5-command="italic" title="CTRL+I">italic</a>
		<a class="button" data-wysihtml5-command="createLink">insert link</a>
		<a class="button" data-wysihtml5-command="insertImage">insert image</a>
		<a class="button" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1">h1</a>
		<a class="button" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2">h2</a>
		<a class="button" data-wysihtml5-command="insertUnorderedList">insertUnorderedList</a>
		<a class="button" data-wysihtml5-command="insertOrderedList">insertOrderedList</a>
		<a class="button" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red">red</a>
		<a class="button" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green">green</a>
		<a class="button" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue">blue</a>
		<a class="button" data-wysihtml5-command="insertSpeech">speech</a>
		<a class="button" data-wysihtml5-action="change_view">switch to html view</a>
		<div data-wysihtml5-dialog="createLink" style="display: none;">
			<label>
				Link:
				<input data-wysihtml5-dialog-field="href" value="http://">
			</label>
			<a class="button" data-wysihtml5-dialog-action="save">OK</a>
			<a class="button" data-wysihtml5-dialog-action="cancel">Cancel</a>
		</div>
		<div data-wysihtml5-dialog="insertImage" style="display: none;">
			<label>
				Image:
				<input data-wysihtml5-dialog-field="src" value="http://">
			</label>
			<label>
				Align:
				<select data-wysihtml5-dialog-field="className">
					<option value="">default</option>
					<option value="wysiwyg-float-left">left</option>
					<option value="wysiwyg-float-right">right</option>
				</select>
			</label>
			<a class="button" data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a class="button" data-wysihtml5-dialog-action="cancel">Cancel</a>
		</div>
	</div>
	<textarea id="form_html" name="html"><?php echo $content->html ?></textarea>
</div>