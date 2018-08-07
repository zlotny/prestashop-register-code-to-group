<!-- Module registercodetogroup -->
<div class="form-group">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox" id="reg-code-checkbox" onclick="onRegCodeCheck()">
    {l s='I have a registration code' mod='registercodetogroup'}
  </label>
</div>
<div class="form-group" id="reg-code-form" style="display: none;">
  <label class="col-form-label" for="inputRegistrationCode">{l s='Type in the registration code' mod='registercodetogroup'}</label>
  <input name="group_code" type="text" class="form-control" placeholder="{l s='Registration code' mod='registercodetogroup'}" id="inputDefault">
</div>
<script>

function onRegCodeCheck(){
  if($("#reg-code-checkbox").is(':checked')){
    $("#reg-code-form").show();
  }else{
    $("#reg-code-form").hide();
  }
}
</script>
<!-- Module registercodetogroup -->