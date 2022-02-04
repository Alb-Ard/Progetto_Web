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
                    const itemText = notification["order_state"] == "CANCELED" && notification["from_user"]
                    const notificationItem = $(`<li id="notification-${notification["id"]}" class="card mb-3 shadow notification-item">
                                                    <header class="card-header">
                                                        ${getReadableTimestamp(notification["created_timestamp"])}
                                                        <button class="btn btn-close float-end" onclick='onRemoveNotification(${notification["id"]})'></button>
                                                    </header>
                                                    <p class="card-text mx-3 mt-3 p-0">${getNotificationText(notification)}</p>
                                                    <a class="stretched-link float-end mx-3 mb-3 p-0" href="./orders.php#book-${notification["book_id"]}">Go to order</a>
                                                </li>`);
                    if (!notification["seen"]) {
                        notificationItem.addClass("border-dark");
                    }
                    notificationsList.append(notificationItem);
                }
            }
        });
    }

    function getReadableTimestamp(timestamp) {
        const standardTimestamp = timestamp.replace(' ', 'T') + "Z";
        const date = new Date(standardTimestamp);
        const currentDate = new Date(Date.now());
            
        let result = "";
        if (currentDate.getFullYear() != date.getFullYear()) {
            result += `${date.toDateString()} at"`;
        } else if (currentDate.getMonth() != date.getMonth()) {
            result += `${date.getMonth()} ${date.getDate()} at`;
        } else {
            const dayDifference = currentDate.getDate() - date.getDate();
            switch (dayDifference) {
                case 0:
                    result += "Today at";
                    break;
                case 1:
                    result += "Yesterday at";
                    break;
                default:
                    result += `${dayDifference} days ago at`;
                    break;
            }
        }

        const timeParts = timestamp.split(' ')[1].split(':');
        result += ` ${timeParts[0]}:${timeParts[1]}`;
        return result;
    }

    function getNotificationText(notification) {
        const orderState = notification["order_state"];
        const book = notification["title"];
        switch (orderState) {
            case "WAITING":
                return `Your order for the book ${book} in being prepared`;
            case "SENT":
                return `Your order for the book ${book} is being delivered`;
            case "RECEIVED":
                return `Your order for the book ${book} has been delivered successfully`;
            case "CANCELED":
                return `Your order for the book ${book} has been cancelled`;
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