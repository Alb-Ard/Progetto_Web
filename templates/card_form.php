<form class="col-10 offset-1 col-md-6 offset-md-3 p-0 row text-center" method="post">
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="type">Type:
        <select class="form-select mt-1" aria-label="type" id="type" name="type">
    <option value="visa">Visa</option>
    <option value="mastercard">Mastercard</option>
    <option value="other">Other</option>
    </select>
    </label>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="number">Number:
        <input class="form-control mt-1" type="tel" id="number" maxlength="19" name="number" placeholder="Insert number" value="" required/>
    </label>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="cvv">CVV:
        <input class="form-control mt-1" type="tel" id="cvv" name="cvv" maxlength="3" placeholder="Insert cvv" value="" required/>
    </label>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="state">Date:
        <select class="form-select mt-1" aria-label="month" id="month" name="month">
        <?php for( $m=1; $m<=12; ++$m ) { 
          $month_label = date('F', mktime(0, 0, 0, $m, 1));
        ?>
          <option value="<?php echo $m; ?>"><?php echo $month_label; ?></option>
        <?php } ?>
      </select> 
      <select class="form-select mt-1" aria-label="year" id="year" name="year">
        <?php 
          $year = date('Y');
          $min = $year;
          $max = $year + 30;
          for( $i=$max; $i>=$min; $i-- ) {
            echo '<option value='.$i.'>'.$i.'</option>';
          }
        ?>
      </select>
    </label>
    <input class="col-12 justify-content-center btn button-primary m-0" type="button" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
</form>