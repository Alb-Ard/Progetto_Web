<script type="text/javascript">
    $(document).ready(() => {
        updateNotifications();
    });

    function onRemoveNotification(id) {
        const data = {
            "action": "remove",
            "id": id,
        };
        $.post("./apis/notifications_api.php", data, (result) => {
            if (result) {
                $("#notification-" + id).slideUp(() => { $("#notification-" + id).remove(); });
            }
        });
    }

    function updateNotifications() {
        $.post("./apis/notifications_api.php", { "action": "get" }, (result) => {
            if (result) {
                const data = JSON.parse(result);
                const notificationsList = $("#notifications-list");
                notificationsList.children().remove();
                for (let idx in data) {
                    const notification = data[idx];
                    const notificationItem = $(`<li id="notification-${notification["id"]}" class="list-group-item row${notification["seen"] ? "" : " notification-unseen"}">
                                                    <p class="">${notification["created_timestamp"]}</p>
                                                    <p class="">Your order from ${notification["from_user"]} is now in state ${notification["order_state"]}</p>
                                                    <a class="btn btn-danger" href="#" onclick='onRemoveNotification(${notification["id"]})'>Remove</a>
                                                </li>`);
                    notificationsList.append(notificationItem);
                }
            }
        });
    }
</script>
<section>
    <header class="row col text-center">
        <h2>Notifications</h2>
    </header>
    <section>
        <ol id="notifications-list" class="list-group">
        </ol>
    </section>
</section>