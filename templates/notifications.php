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
                    const notificationItem = $(`<li id="notification-${notification["id"]}" class="card mb-3 shadow notification-item">
                                                    <header class="card-header">
                                                        ${getReadableTimestamp(notification["created_timestamp"])}
                                                        <button class="btn btn-close float-end" onclick='onRemoveNotification(${notification["id"]})'></button>
                                                    </header>
                                                    <p class="card-content m-0 p-3">Your order from ${notification["from_user"]} is now in state ${notification["order_state"]}</p>
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

</script>
<section>
    <header class="row col text-center">
        <h2>Notifications</h2>
    </header>
    <ol id="notifications-list" class="p-3">
    </ol>
</section>