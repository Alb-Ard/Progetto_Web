<form class="col-10 offset-1 col-md-6 offset-md-3 p-0 row text-center" method="post">
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="type">Type:
        <input class="form-control mt-1" type="text" id="type" name="type" placeholder="Insert type" value="<?php echo $card->type; ?>" required/>
    </label>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="author">Author:
        <input class="form-control mt-1" type="text" id="number" name="number" placeholder="Insert number" value="<?php echo $card->number; ?>" required/>
    </label>
    <label class="col-12 justify-content-center form-label px-2 mb-4" for="state">Date:
        <textarea class="form-control mt-1" type="text" id="date" name="date" placeholder="Insert date" required><?php echo $card->date; ?></textarea>
    </label>
    <input class="col-12 justify-content-center btn button-primary m-0" type="button" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
</form>