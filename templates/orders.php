<script type="text/javascript">
    const orderTexts = {
        "WAITING": "Preparing delivery",
        "SENT": "In delivery",
        "RECEIVED": "Delivered",
        "CANCELED": "Canceled"
    };

    let currentDeletingOrderBook;

    $(document).ready(() => {
        updateOrders();
    });

    function onDeleteRequest(book) {
        currentDeletingOrder = book;
        new bootstrap.Modal($("#delete-modal")).show();
    }

    function getOrderStateGraphic(currentState) {
        const states = ["WAITING", "SENT", "RECEIVED"];
        const currentIdx = states.indexOf(currentState);
        let statesGraphic = "";
        for (let stateIdx in states) {
            const state = states[stateIdx];
            const colorClass = `order-state-${stateIdx <= currentIdx ? "completed" : "incompleted"}`;
            if (stateIdx != 0) {
                statesGraphic += `<div class="rounded-pill mx-1 my-auto h-25 ${colorClass} p-1 col"></div>`;
            }
            statesGraphic += `<div class="rounded-pill order-state ${colorClass} col-auto"></div>`;
        }
        const labels = ["Preparing shipment", "Delivering", "Delivered"];
        const labelClasses = ["start", "center", "end"]
        return `<section>
                    <header>
                        <h4 class="text-center text-md-${labelClasses[currentIdx]} p-0 mx-0 h5">${labels[currentIdx]}</h4>
                    </header>
                    <div class="m-0 row justify-content-center">
                        ${statesGraphic}
                    </div>
                </section>`;
    }

    function updateOrders() {
        $.post("./apis/orders_api.php", { "action": "get_purchased" }, (result) => {
            if (result) {
                const ordersList = $("#orders-list");
                const orders = JSON.parse(result);
                ordersList.children().remove();
                if (orders.length == 0) {
                    ordersList.before($(`<p id="empty-label" class="text-center">No orders found.</p>`));
                    return;
                }
                for(let idx in orders) { 
                    const order = orders[idx];
                    const orderItem = $(`<li class="card card-no-hover shadow m-3 w-100" id="book-${order["id"]}">
                                            <header class="card-header">
                                                <div class="row">
                                                    <h3 class="card-title col-lg">${order["title"]}</h3>
                                                    <p class="col-12 col-lg-auto my-auto">${getReadableTimestamp(order["date"])}</p>
                                                </div>
                                            </header>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-12 col-sm-3">
                                                        <img class="img-fluid mx-auto" src="${order["image"]}" alt="${order["title"]} cover image"/>
                                                    </div>
                                                    <section class="col-12 col-sm-9 col-lg-3 text-center text-sm-start my-3">
                                                        <header class="mb-2">
                                                            <h4>Info</h4>
                                                        </header>
                                                        <div>
                                                            <p class="m-0 p-0">Price: ${order["price"]}€</p>
                                                            <p class="m-0 mb-1 p-0">Purchased from: ${order["owner"]}</p>
                                                        </div>
                                                    </section>
                                                    <div class="col-12 col-lg-6 mt-3 my-md-auto">
                                                        ${getOrderStateGraphic(order["advancement"])}
                                                    </div>
                                                </div>
                                                ${order["advancement"] != "RECEIVED" ? `<button class="btn btn-danger align-bottom w-100" onclick="onDeleteRequest(${order["id"]})">Cancel order</button>` : ""}
                                            </div>
                                        </li>`);
                    ordersList.append(orderItem);
                }
                const urlParts = window.location.href.split("#");
                if (urlParts.length > 1) {
                    const book = urlParts[1];
                    const bookParts = book.split("-");
                    if (bookParts.length > 1 && bookParts[0] == "book") {
                        $(`#${book}`).fadeOut("fast").fadeIn("fast");
                    }
                }
            } else {
                $("#error-internal").show();
            }
        });
    }
</script>

<aside>
    <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
</aside>

<section>
    <header>
        <h2 class="text-center">Orders</h2>
    </header>
    <ul id="orders-list" class="d-flex flex-wrap m-3 p-0 justify-content-center">
    </ul>
</section>