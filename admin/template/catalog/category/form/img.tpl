<div class="row">
    <form id="edit_img_cat" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_cat}" method="post" enctype="multipart/form-data" class="validate_form edit_form_img">
        <div class="col-xs-12">
            <div class="form-group">
                <input type="hidden" name="MAX_FILE_SIZE" value="2048576" />
                <input type="file" id="img_cat" name="img" class="inputfile inputimg" value="" />
                <label for="img_cat">
                    <figure>
                        <span class="fa-stack fa-lg">
                          <span class="fa fa-circle fa-stack-2x"></span>
                          <span class="fa fa-upload fa-stack-1x fa-inverse"></span>
                        </span>
                    </figure>
                    <span id="input-label">Choisissez une image&hellip;</span>
                </label>
                <input type="hidden" id="id_cat" name="id" value="{$page.id_cat}">
                <button class="btn btn-main-theme" type="submit" name="action" value="img">{#save#|ucfirst}</button>
            </div>
        </div>
    </form>
</div>