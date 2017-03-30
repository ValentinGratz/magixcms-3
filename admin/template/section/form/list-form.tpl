{if !isset($class_form)}{$class_form = "col-xs-12 col-md-6"}{/if}
{if !isset($class_table)}{$class_table = "col-xs-12 col-md-6"}{/if}
<div class="row">
    <form id="add_{$sub}" action="{$smarty.server.SCRIPT_NAME}?action=add&tabs={$sub}&edit={$id}" data-sub="{$sub}" method="post" class="validate_form add_to_list {$class_form}">
        {include file="{$controller}/form/{$sub}.tpl"}
    </form>
    <div class="{$class_table}">
        <div class="table-responsive">
            <table class="table table-condensed{if isset($customClass)} {$customClass}{/if}">
                <tbody id="{$sub}List" class="direct-edit-table">
                {if !empty($data)}
                    {foreach $data as $row}
                        {include file="{$controller}/loop/{$sub}.tpl" first=$row@first}
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>