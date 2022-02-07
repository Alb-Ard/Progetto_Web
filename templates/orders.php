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
                    const orderItem = $(`<li class="card shadow m-3" id="book-${order["id"]}">
                                            <header class="card-header">
                                                <h3 class="card-title">${order["title"]}</h3>
                                            </header>
                                            <img class="book-cover m-3" src="${order["image"]}" alt="${order["title"]} cover image"/>
                                            <p class="card-text mx-3">Price: ${order["price"]}€</p>
                                            <p class="card-text mx-3">${orderTexts[order["advancement"]]}</p>
                                            <p class="card-text mx-3">Purchased from: ${order["owner"]}</p>
                                            <button id="book-${order["id"]}-delete-button" class="btn btn-danger mx-3 mb-3" onclick="onDeleteRequest(${order["id"]})">Cancel order</button>
                                        </li>`);
                    ordersList.append(orderItem);

                    if (order["advancement"] == "RECEIVED") {
                        $(`#book-${order["id"]}-delete-button`).addClass("disabled");
                    }
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