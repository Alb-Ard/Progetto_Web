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
                    const orderItem = $(`<li class="col-12 col-md-5 position-relative seller-listing-book mx-0 my-1" id="${order["id"]}">
                                            <article class="row justify-content-around m-0 p-1">
                                                <header class="col-12 col-md-8">
                                                    <h3 class="d-inline-block text-truncate w-100">${order["title"]}</h3>
                                                </header>
                                                <p class="col-12 col-md-4 text-end">Price: ${order["price"]}€</p>
                                                <p class="col-12 text-end">Send to: ${order["user_id"]}</p>
                                                <p class="col-12 col-md-8 text-end">Current state: ${order["advancement"]}</p>
                                                <button class="col-12 col-md-4 btn button-secondary" 
                                                onclick="onChangeState(${order["id"]}, ${order["order_id"]}, '${order["advancement"]}');"
                                                ${order["advancement"] == "RECEIVED" ? " disabled" : ""}>Advance state</button>
                                            </article>
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
<ul id="orders-list" class="row m-0 p-0 justify-content-around">
</ul>