<script type="text/javascript">
    function validateBookForm() {
        const invalidInputs = $("#book-form input:invalid");
        for (let idx = 0; idx < invalidInputs.length; idx++) {
            const element = $(invalidInputs[idx]);
            element.addClass("border-danger");
        }
        return invalidInputs.length < 1;
    }
</script>
<form class="col-10 offset-1 col-md-6 offset-md-3 p-0 row text-center" method="post" id="book-form" action="#">
    <input type="hidden" name="id" id="id" value="<?php echo $book->id; ?>"/>
    <input type="hidden" name="action" id="action" value="<?php echo $action; ?>"/>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="title">Title *:
        <input class="form-control mt-1" type="text" id="title" name="title" placeholder="Insert title" value="<?php echo $book->title; ?>" required/>
    </label>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="author">Author *:
        <input class="form-control mt-1" type="text" id="author" name="author" placeholder="Insert author" value="<?php echo $book->author; ?>" required/>
    </label>
    <label class="col-12 col-md-6 justify-content-center form-label px-2 mb-4" for="category">Category *:
        <select class="form-select mt-1" aria-label="category" id="category" name="category">
            <?php
            
            $categories = $db_conn->get_categories()->get_categories();
            foreach($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>"<?php if($book->category == $category["id"]) echo " selected"; ?>><?php echo $category["name"]; ?></option>
            <?php }

            ?>
        </select>
    </label>
    <div class="col-12 col-md-6 py-0 px-2 mb-4">
        <label class="form-label mb-1" for="price">Price: *</label>
        <div class="input-group p-0">
            <span class="input-group-text">€</span>
            <input class="form-control" type="number" id="price" name="price" min="0.01" step="0.01" required value="<?php echo $book->price; ?>"/>
        </div>
    </div>
    <label class="col-12 justify-content-center form-label px-2 mb-4" for="image">Book image
        <input class="form-control mt-1" type="file" id="image" name="image" <?php if($image_required) echo "required"; ?>/>
    </label>
    <label class="col-12 justify-content-center form-label px-2 mb-4" for="state">State *:
        <textarea class="form-control mt-1" id="state" name="state" placeholder="Insert state" required><?php echo $book->state; ?></textarea>
    </label>
    <input class="col-12 justify-content-center btn button-primary m-0" type="submit" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
</form>