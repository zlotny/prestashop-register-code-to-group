<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Group code assignation' mod='registercodetogroup'}</h3>
    <h4>Add new code</h4><hr>
	<p>
        <form method="POST" action="{$form_action}">
            <fieldset>
                <div class="form-group">
                    <label class="col-form-label" for="inputDefault">{l s='Code Identifier' mod='registercodetogroup'}</label>
                    <input type="text" class="form-control" placeholder="{l s='Code Identifier' mod='registercodetogroup'}" name="code_id">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="inputDefault">{l s='Code' mod='registercodetogroup'}</label>
                    <input type="text" class="form-control" placeholder="{l s='Code' mod='registercodetogroup'}" name="code">
                </div>
                <div class="form-group">
                    <label for="exampleSelect2">{l s='Groups assigned' mod='registercodetogroup'}</label>
                    <select multiple="" class="form-control" id="exampleSelect2" name="groups_assigned[]" >
                    {foreach from=$groups key=key item=row}
                        <option value="{$key}">{$row.name}</option>
                    {/foreach}
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" name="submitBtn" value="Add new code"></input>
            </fieldset>
        </form>
    </p>
    <h4>Existing codes</h4><hr>
    <p>
		<table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">{l s='Code Identifier' mod='registercodetogroup'}</th>
                <th scope="col">{l s='Code' mod='registercodetogroup'}</th>
                <th scope="col">{l s='Groups assigned' mod='registercodetogroup'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach key=key from=$data item=row}
                    <tr class="table-active">
                        <th scope="row">{$key}</th>
                        <td>{$row.code}</td>
                        <td>{', '|implode:$row.groups_names}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
	</p>
</div>