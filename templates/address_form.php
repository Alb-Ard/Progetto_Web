<form class="col-10 offset-1 col-md-6 offset-md-3 p-0 row text-center" method="post">
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="address">Address:
        <input class="form-control mt-1" type="text" id="address" name="address" placeholder="Insert address" value="<?php echo $address->address; ?>" required/>
    </label>
    <input class="col-12 justify-content-center btn button-primary m-0" type="button" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
</form>