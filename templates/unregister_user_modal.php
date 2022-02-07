
<script type="text/javascript">
    function onUnregister() {
        const email = '<?php echo $user_info["email"]; ?>';
        $("#error-internal").hide();
        unregister(email, (result) => {
            if (result == Result.Ok)
                window.location.href = "./";
            else
                $("#error-internal").slideDown();
        });
    }
</script>
<div class="modal fade" id="delete-modal" tabindex="-1">
    <div class="modal-dialog">
        <section class="modal-content">
            <header class="modal-header">
                <h2 class="modal-title h3">Confirm deletion</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </header>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This will permanently remove all your books from the store.</p>
            </div>
            <footer class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="onUnregister();" data-bs-dismiss="modal">Delete</button>
            </footer>
        </section>
    </div>
</div>