<h1>Setup Uncode Child Theme (Optional)</h1>

<input type='hidden' name='step' value='<?php echo ($step + 1); ?>'>

<div class="form-group">
  <label for="child_theme_title">Child Theme Title</label>
  <input type="text" class="form-control" name="child_theme_title" id="child_theme_title" value="">

  <label for="child_theme_uri">Child Theme URI</label>
  <input type="text" class="form-control" name="child_theme_uri" id="child_theme_uri" value="">

<label for="child_theme_description">Description</label>
  <input type="text" class="form-control" name="child_theme_description" id="child_theme_description" value="">

  <label for="child_theme_author">Author</label>
  <input type="text" class="form-control" name="child_theme_author" id="child_theme_author">

  <label for="child_theme_version">Version</label>
  <input type="text" class="form-control" name="child_theme_version" id="child_theme_version">

  <label for="child_theme_template">Template</label>
  <input type="text" class="form-control" name="child_theme_template" id="child_theme_template" value="<?php echo get_template(); ?>">

</div>

<div class="text-right">
    <button class="btn btn-default" name='skip' type='submit'>Skip</button>
    <button class="btn btn-primary" type='submit'>Next</button>
</div>
