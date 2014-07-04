<h3>{ts}Automatic Soft Credit Settings{/ts}</h3>
<p>{ts}Please select a relationship type ID on which to enable automatic soft crediting.  If you haven't created the relationship type, please do so by clicking on <a href="/civicrm/admin/reltype?reset=1">Relationship Types</a>.{/ts}</p>
<p>Contributions to Contact A will be automatically soft credited to Contact B.</p>
<fieldset>
    <div class="crm-block crm-content-block">
      <table class="form-layout-compressed">
        <tr class="crm-cividesk-normalize-form-block">
          <td class="label">{ts}Relationship Type ID{/ts}</td>
          <td>{$form.contact_to.html}{$form.contact_apply.html}</td>
        </tr>
        <tr class="crm-cividesk-normalize-form-block">
          <td class="label">
            <div class="form-item" style="text-align:left">
              <input type="submit" class="form-submit" value="Submit">
            </div>
          </td>
        </tr>
      </table>
    </div>
  </fieldset>

{* Example: Display a variable directly *}
<p>The current time is {$currentTime}</p>

{* Example: Display a translated string -- which happens to include a variable *}
<p>{ts 1=$currentTime}(In your native language) The current time is %1.{/ts}</p>
