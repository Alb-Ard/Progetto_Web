<form class="container-md text-center" method="post">
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3" for="title">Title:
        <input class="form-control" type="text" id="title" name="title" placeholder="Insert title" value="<?php echo $book->title; ?>" required/>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3" for="author">Author:
        <input class="form-control" type="text" id="author" name="author" placeholder="Insert author" value="<?php echo $book->author; ?>" required/>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3" for="category">Category:
        <select class="form-select" aria-label="category" id="category" name="category">
            <option value="NULL"<?php if($book->category == NULL) echo " selected"; ?>>Other</option>
            <?php
            
            $categories = $db_conn->get_categories()->get_categories();
            foreach($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>"<?php if($book->category == $category["id"]) echo " selected"; ?>><?php echo $category["name"]; ?></option>
            <?php }

            ?>
        </select>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3" for="state">State:
        <input class="form-control" type="text" id="state" name="state" placeholder="Insert state" value="<?php echo $book->state; ?>" required/>
    </label>
    <div class="row col-12 col-md-6 offset-md-3 p-0 mb-3">
        <label class="form-label" for="price">Price:</label>
        <div class="input-group p-0">
            <span class="input-group-text">€</span>
            <input class="form-control" type="number" id="price" min="0.00" step="0.01" required value="<?php echo $book->price; ?>"/>
        </div>
    </div>
    <input class="row col-12 col-md-6 offset-md-3 justify-content-center btn button-primary m-0" type="button" id="confirm-button" onclick="<?php echo $on_confirm_func; ?>" value="<?php echo $on_confirm_value; ?>"/>
</form>