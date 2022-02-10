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
                    const text = getNotificationText(notification);
                    if (text == null) {
                        continue;
                    }
                    const isSeller = notification["user"] == notification["owner"];
                    const link = isSeller ? `./seller_orders.php#book-${notification["book_id"]}` : `./orders.php#book-${notification["book_id"]}`;
                    const itemText = notification["order_state"] == "CANCELED" && notification["from_user"];
                    const notificationItem = $(`<li id="notification-${notification["id"]}" class="card mb-3 shadow notification-item">
                                                    <header class="card-header">
                                                        ${getReadableTimestamp(notification["created_timestamp"])}
                                                        <button class="btn btn-close float-end" onclick='onRemoveNotification(${notification["id"]})'></button>
                                                    </header>
                                                    <p class="card-text mx-3 mt-3 p-0">${getNotificationText(notification)}</p>
                                                    <a class="stretched-link float-end mx-3 mb-3 p-0" href="${link}">Go to order</a>
                                                </li>`);
                    if (!notification["seen"]) {
                        notificationItem.addClass("border-dark");
                    }
                    notificationsList.append(notificationItem);
                }
            }
        });
    }

    function getNotificationText(notification) {
        const orderState = notification["order_state"];
        const book = notification["title"];
        const isSeller = notification["user"] == notification["owner"];
        if (isSeller) {
            const user = notification["from_user"];
            switch (orderState) {
                case "WAITING":
                    return `You have a new order from ${user} for the book "${book}".`;
                case "CANCELED":
                    return `Your order from ${user} for the book "${book}" has been cancelled.`;
                default:
                    return null;
            }
        } else {
            switch (orderState) {
                case "SENT":
                    return `Your order for the book "${book}" is being delivered.`;
                case "RECEIVED":
                    return `Your order for the book "${book}" has been delivered successfully.`;
                case "CANCELED":
                    return `Your order for the book "${book}" has been cancelled.`;
                default:
                    return null;
            }
        }
    }

</script>
<section>
    <header class="row col text-center">
        <h2>Notifications</h2>
    </header>
    <ol id="notifications-list" class="p-3">
    </ol>
</section>