<div class="modal fade" id="delete-modal" tabindex="-1">
    <div class="modal-dialog">
        <section class="modal-content">
            <header class="modal-header">
                <p class="modal-title">Confirm deletion</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </header>
            <div class="modal-body">
                <p>Are you sure you want to premanently remove this listing?</p>
            </div>
            <footer class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="onRemove();" data-bs-dismiss="modal">Delete</button>
            </footer>
        </section>
    </div>
</div>