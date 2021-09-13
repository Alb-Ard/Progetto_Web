<form class="container-md text-center" method="post">
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3">Title:
        <input class="form-control" type="text" id="title" name="title" placeholder="Insert title" value="<?php echo $book->title; ?>" required="true"/>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3">Author:
        <input class="form-control" type="text" id="author" name="author" placeholder="Insert author" value="<?php echo $book->author; ?>" required="true"/>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3">Category:
        <select class="form-select" aria-label="category" id="category" name="category">
            <option value="NULL"<?php if($book->category == "NULL") echo " selected"; ?>>Other</option>
            <?php
            
            $categories = $db_conn->get_categories()->get_categories();
            foreach($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>"<?php if($book->category == $category["id"]) echo " selected"; ?>><?php echo $category["name"]; ?></option>
            <?php }

            ?>
        </select>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3">State:
        <input class="form-control" type="text" id="state" name="state" placeholder="Insert state" value="<?php echo $book->state; ?>" required="true"/>
    </label>
    <label class="row col-12 col-md-6 offset-md-3 justify-content-center form-label mb-3">Price:
        <div class="input-group p-0">
            <span class="input-group-text">€</span>
            <input class="form-control" type="number" id="price" min="0.00" step="0.01" required="true" value="<?php echo $book->price; ?>" value="0"/>
        </div>
    </label>
    <input class="row col-12 col-md-6 offset-md-3 justify-content-center btn button-primary m-0" type="button" id="edit-button" onclick="onEditBook();" value="Edit">
        <div class="spinner-border spinner-border-sm d-none" id="edit-button-spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </input>
</form>