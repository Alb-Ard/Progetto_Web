<script type="text/javascript">
    $(document).ready(() => {
        updateOrders();
    });

    function onChangeState(book, order, fromState) {
        const data = {
            "action": "advance",
            "book_id": book,
            "order_id": order,
            "state": fromState,
        };
        $.post("./apis/orders_api.php", data, (result) => {
            if(result) {
                updateOrders();
            }
        });
    }

    function updateOrders() {
        $.post("./apis/orders_api.php", { "action": "get" }, (result) => {
            if (result) {
                const ordersList = $("#orders-list");
                const orders = JSON.parse(result);
                ordersList.children().remove();
                for(let idx in orders) { 
                    const order = orders[idx];
                    const orderItem = $(`<li class="card shadow m-3" id="${order["id"]}">
                                            <header class="card-header">
                                                <h3 class="card-title">${order["title"]}</h3>
                                            </header>
                                            <p class="card-text mx-3 mt-3">Price: ${order["price"]}€</p>
                                            <p class="card-text mx-3">Purchased by: ${order["user_id"]}</p>
                                            <p class="card-text mx-3">Current state: ${order["advancement"]}</p>
                                            <button class="btn button-secondary mx-3 mb-3" onclick="onChangeState(${order["id"]}, ${order["order_id"]}, '${order["advancement"]}');" ${order["advancement"] == "RECEIVED" ? " disabled" : ""}>Advance state</button>
                                        </li>`);
                    ordersList.append(orderItem);
                }
            }
        });
    }
</script>

<section class="row">
    <p class="alert alert-danger login-alert" id="error-internal" role="alert">Something went wrong! Please try again.</p>
</section>

<section>
    <ul id="orders-list" class="d-flex flex-wrap m-3 p-0 justify-content-center">
    </ul>
</section>