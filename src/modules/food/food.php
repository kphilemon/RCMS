<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/FoodModel.php';
include '../src/models/FoodOrderModel.php';


if (isset($_SESSION['user_id'])) {

    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;
    $not_found = false;
    $date_selected = false;


    try {
        $food_order_model = new FoodOrderModel($db->getConnection());
        $food_model = new FoodModel($db->getConnection());
    } catch (PDOException $exception) {
        $server_err = true;
    }

    $matches = [];
    preg_match('/^\/food\/(\d{4}-\d{2}-\d{2})\/?$/i', $_SERVER['REQUEST_URI'], $matches);
    if (!$server_err && !empty($matches[1])) {
        $date = date_create_from_format('Y-m-d', $matches[1]);
        if ($date) { // date in URL is well-formatted
            $date_selected = true;
            $date_string = $date->format('Y-m-d');


            // If it is a post request, means user is submitting order
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
                try {
                    foreach ($_POST as $key => $value) {
                        $food_order_model->insert($date_string, $_SESSION['user_id'], $key, $value);
                        $food_order_model->removeZeroQuantity( $_SESSION['user_id']);
                    }
                } catch (PDOException $exception) {
                    echo $exception->getMessage();
                    $server_err = true;
                }
            }

            try {
                // query menu and the order user has made for the day
                $menu = $food_model->getAllFoodByDate($date_string);
                $order = $food_order_model->getAllOrderByUserIdAndDate($_SESSION['user_id'], $date_string);

            } catch (PDOException $exception) {
                $server_err = true;
            }

        } else {
            $not_found = true;
        }
    }

    try {
        $all_orders = $food_order_model->getAllOrderByUserId($_SESSION['user_id']);
    } catch (PDOException $exception) {
        $server_err = true;
    }

    $db->closeConnection();
}
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (!$server_err) : ?>
            <?php if (!$not_found): ?>

                <div class="card p-4 mb-4">
                    <h5 class="text-primary m-0">Welcome to KK<?= $_SESSION['user_college_id'] ?>'s Dinner Planner!</h5>
                    <p class="text-secondary m-0">Click on the dates in the calendar to place or check your dinner orders.</p>
                </div>

                <div class="row">
                    <div class=" col-lg-8">
                        <div class="p-4 card">
                            <div id="calendar"></div>
                        </div>
                    </div>

                    <div class=" col-lg-4">
                        <div class="p-4 card ">
                            <?php if ($date_selected): ?>
                                <h5 class="card-title mb-4"><?= $date->format('j F Y') ?></h5>
                                <?php if (!empty($order)): ?>
                                    <?php foreach ($order as $item): ?>
                                        <p class="mb-2"><?= $item['name'] . ' X ' . $item['quantity'] ?></p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="my-4 text-center">It seems like you do not have any orders on this
                                        day.</p>
                                <?php endif; ?>


                                <!-- only allow edit order or place order if it is greater than today-->
                                <?php if ($date_string > date('Y-m-d')): ?>
                                    <button type="button" class="btn btn-primary mt-4" data-toggle="modal"
                                            data-target="#modal-place-order"><?= (empty($order)) ? 'Place Order Now' : 'Edit Order' ?>
                                    </button>
                                <?php endif; ?>

                            <?php else: ?>
                                <p class="my-5 text-center">Click on any dates to see your order here</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <?php if ($date_selected && $date_string > date('Y-m-d')): ?>
                    <div class="modal fade" id="modal-place-order" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

                            <?php if (!empty($menu)): ?>
                                <form class="modal-content" action="" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary">Menu for <?= $date->format('j F Y') ?></h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <?php foreach ($menu as $food): ?>
                                            <div class="row no-gutters mb-3">
                                                <div class="col">
                                                    <img class="food-img-left"
                                                         src="<?= '/assets/img/food/' . $food['img'] ?>">
                                                </div>
                                                <div class="col-5 align-self-center">
                                                    <h5 class="text-secondary"><?= $food['name'] ?></h5>
                                                    <h6 class="text-secondary"><?= 'RM ' . $food['price'] ?></h6>
                                                </div>
                                                <div class="col align-self-center">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn btn-outline-primary food-counter-btn btn-number"
                                                                    type="button" data-type="minus"
                                                                    data-field="<?= $food['id'] ?>">-
                                                            </button>
                                                        </div>
                                                        <input type="text" name="<?= $food['id'] ?>"
                                                               class="form-control food-counter-border input-number"
                                                               value="<?php
                                                               $found = false;
                                                               foreach ($order as $item) {
                                                                   if (!empty($item['id']) && $item['id'] == $food['id']) {
                                                                       echo $item['quantity'];
                                                                       $found = true;
                                                                       break;
                                                                   }
                                                               }
                                                               if (!$found) {
                                                                   echo 0;
                                                               }
                                                               ?>" min="0" max="5">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-primary food-counter-btn btn-number"
                                                                    type="button"
                                                                    data-type="plus" data-field="<?= $food['id'] ?>">+
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-primary">Confirm Selection</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary">Food pre-ordering unavailable</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body text-secondary">
                                        <p>Sorry, there's currently no food menu for <?= $date->format('j F Y') ?>. Please try again tomorrow.</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">
                                            Got it
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: include '../src/templates/404.php'; endif; ?>
        <?php else: include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/food.js"></script>
<script src='/assets/js/vendor/fullcalendar-core.js'></script>
<script src='/assets/js/vendor/fullcalendar-daygrid.js'></script>
<script src='/assets/js/vendor/fullcalendar-interaction.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendarEl = document.getElementById('calendar');

        let calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid'],
            header: {
                left: 'today',
                center: 'title',
                right: 'prev,next'
            },
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            selectable: true,
            eventLimit: true, // allow "more" link when too many events
            events: [
                <?php
                foreach ($all_orders as $order) {
                    echo '{
                    title: \'' . $order['name'] . ' X ' . $order['quantity'] . '\',
                    start: \'' . $order['order_date'] . '\'
                },';
                }
                ?>
            ],
            dateClick: function (info) {
                window.location.href = '/food/' + info.dateStr;
            }
        });

        calendar.render();
    });

</script>

<?php include '../src/templates/footer.php' ?>
