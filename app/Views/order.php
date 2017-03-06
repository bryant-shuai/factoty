<?php
include __VIEW_DIR__ . "/layout/header.php";

$flag = isset($_GET['sign']) ? $_GET['sign'] : 0;
?>
<script>
    var product_id_list = [];
</script>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h1>订单金额:
                    <span>
                        <?php echo number_format(floatval($para["orderPrice"]), 2); ?>元
                    </span>
                </h1>
            </div>
            <div class="col-md-4">
                <h1>账户余额:
                    <span>
                        <?php
                        $client = $para["client"];
                        $deposit = (isset($client['deposit']) && $client['deposit']) ? $client['deposit'] : 0;
                        echo $deposit;
                        ?>
                        元
                    </span>
                </h1>
            </div>
            <div class="col-md-4">
                <button
                    onClick="onClickCommitAll()"
                    class="form-control btn btn-primary"
                >
                    全部提交
                </button>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style="text-align: center">名称</th>
            <th style="text-align: center">单价</th>
            <th style="text-align: center">需求</th>
            <th style="text-align: center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $products_group = $para["products_group"];
        foreach ($products_group as $groupname => $products) {
            if (count($products) > 0) {
                ?>
                <tr>
                    <td colspan="4" style="color: black; background: #afd9ee; font-weight: bolder; font-size: larger">
                        <?php echo $groupname; ?>
                    </td>
                </tr>

                <?php

                foreach ($products as $product) {
                    $need_amount = $product['need_amount'];
                    ?>
                    <script>
                        product_id_list = product_id_list || [];
                        product_id_list.push(<?php echo $product['id'] ?>)
                    </script>
                    <tr>
                        <td style="vertical-align: middle;width: 10%;">
                            <?php echo $product['name'] ?>
                        </td>
                        <td style="vertical-align: middle;width: 20%;">
                            <?php echo $product['region_price'] . '元<br />(' . $product['unit'] . ')' ?>
                        </td>
                        <td style="vertical-align: middle;">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="请输入需要的数量"
                                id="need_amount_<?php echo $product['id'] ?>"
                                value="<?php echo $need_amount ?>"
                            >
                        </td>
                        <td style="vertical-align:middle;">
                            <input
                                type="button"
                                class="form-control btn-primary"
                                onclick="onClickToSubmit(
                                <?php echo $product['id'] ?>,
                                    document.getElementById('need_amount_<?php echo $product['id'] ?>').value
                                    )"
                                id="btn"
                                value="提交"
                            >
                        </td>
                    </tr>
                    <?php
                }

            }
        }
        ?>
        </tbody>
    </table>
</div>

<?php
include __VIEW_DIR__ . "/layout/footer.php";
?>


