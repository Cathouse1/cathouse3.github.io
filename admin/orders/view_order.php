<?php if(isset($_GET['view'])): 
require_once('../../config.php');
endif;?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(!isset($_GET['id'])){
    $_settings->set_flashdata('error','No order ID Provided.');
    redirect('admin/?page=orders');
}
$order = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client FROM `orders` o inner join clients c on c.id = o.client_id where o.id = '{$_GET['id']}' ");
if($order->num_rows > 0){
    foreach($order->fetch_assoc() as $k => $v){
        $$k = $v;
    }
}else{
    $_settings->set_flashdata('error','Order ID provided is Unknown');
    redirect('admin/?page=orders');
}
?>
<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="conitaner-fluid">
            <p><b>Nama : <?php echo $client ?></b></p>
            <p><b>Alamat Pengiriman : <?php echo $delivery_address ?></b></p>
            <table class="table-striped table table-bordered">
                <colgroup>
                    <col width="30%">
                    <col width="30%">
                    <col width="30%">
         
                </colgroup>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Hewan (Jenis Kelamin)</th>
                        <th>Umur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $olist = $conn->query("SELECT o.*,p.product_name FROM order_list o inner join products p on o.product_id = p.id where o.order_id = '{$id}' ");
                        while($row = $olist->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['quantity'] ?></td>
                        <td><?php echo $row['product_name'] ." ({$row['size']}) " ?></td>
                        <td class="text-right"><?php echo ($row['price']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="row">
           
            <div class="col-6 row row-cols-2">
                <div class="col-3">Status Adopsi:</div>
                <div class="col-9">
                <?php 
                    switch($status){
                        case '0':
                            echo '<span class="badge badge-light text-dark">Tertunda</span>';
	                    break;
                        case '1':
                            echo '<span class="badge badge-primary">Disiapkan</span>';
	                    break;
                        case '2':
                            echo '<span class="badge badge-warning">Keluar Untuk Pengiriman</span>';
	                    break;
                        case '3':
                            echo '<span class="badge badge-success">Terkirim</span>';
	                    break;
                        default:
                            echo '<span class="badge badge-danger">Dibatalkan</span>';
	                    break;
                    }
                ?>
                </div>
                <?php if(!isset($_GET['view'])): ?>
                <div class="col-3"></div>
                <div class="col">
                    <button type="button" id="update_status" class="btn btn-sm btn-flat btn-primary">Ubah Status</button>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
</div>
<?php if(isset($_GET['view'])): ?>
<style>
    #uni_modal>.modal-dialog>.modal-content>.modal-footer{
        display:none;
    }
</style>
<?php endif; ?>
<script>
    $(function(){
        $('#update_status').click(function(){
            uni_modal("Ubah Status", "./orders/update_status.php?oid=<?php echo $id ?>&status=<?php echo $status ?>")
        })
    })
</script>