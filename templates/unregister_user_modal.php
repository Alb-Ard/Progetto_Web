
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
<section class="modal fade" id="delete-modal" tabindex="-1">
  <section class="modal-dialog">
    <section class="modal-content">
      <header class="modal-header">
        <h5 class="modal-title">Confirm deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </header>
      <section class="modal-body">
        <p>Are you sure you want to delete your account? This will permanently remove all your books from the store.</p>
      </section>
      <footer class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="onUnregister();" data-bs-dismiss="modal">Delete</button>
      </footer>
    </section>
  </section>
</section>