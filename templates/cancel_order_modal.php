
<script type="text/javascript">
    function onDelete(book) {
        const data = {
            "action": "cancel",
            "book_id": book,
        };

        $.post("./apis/orders_api.php", data, (result) => {
            if (result) {
                $("#book-" + book).fadeOut("fast", () => { $("#book-" + id).remove(); });
            }
        });
    }
</script>
<div class="modal fade" id="delete-modal" tabindex="-1">
    <div class="modal-dialog">
        <section class="modal-content">
            <header class="modal-header">
                <p class="modal-title">Confirm delete</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </header>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
            </div>
            <footer class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="onDelete(currentDeletingOrderBook);" data-bs-dismiss="modal">Delete</button>
            </footer>
        </section>
    </div>
</div>