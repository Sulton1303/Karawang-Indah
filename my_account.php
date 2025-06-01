</section> <section class="page-section">
    <div class="container">
    <div class="w-100 justify-content-between d-flex">
        <h4><b>Booked Packages</b></h4>
        <a href="./?page=edit_account" class="btn btn btn-primary btn-flat"><div class="fa fa-user-cog"></div> Manage Account</a>
    </div>
        <hr class="border-warning">
        <table class="table table-stripped text-dark">
            <colgroup>
                <col width="5%">
                <col width="10%"> <col width="25%">
                <col width="25%">
                <col width="15%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th>#</th>
                    <th>DateTime</th>
                    <th>Package</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=1;
                    $qry = $conn->query("SELECT b.*, p.title FROM book_list b INNER JOIN `packages` p ON p.id = b.package_id WHERE b.user_id ='".$_settings->userdata('id')."' ORDER BY date(b.date_created) DESC ");
                    if ($qry) { 
                        while($row = $qry->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td><?php echo htmlspecialchars($row['title']) // Menggunakan htmlspecialchars untuk keamanan ?></td>
                        <td><?php echo date("Y-m-d", strtotime($row['schedule'])) ?></td>
                        <td class="text-center">
                            <?php if($row['status'] == 0): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php elseif($row['status'] == 1): ?>
                                <span class="badge badge-primary">Confirmed</span>
                            <?php elseif($row['status'] == 2): ?>
                                <span class="badge badge-danger">Cancelled</span>
                            <?php elseif($row['status'] == 3): ?>
                                <span class="badge badge-success">Done</span>
                            <?php endif; ?>
                        </td>
                        <td align="center">
                                <button type="button" class="btn btn-flat btn-default border btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item cancel_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Cancel</a>
                                </div>
                        </td>
                    </tr>
                <?php
                        endwhile;
                    } else {
                        // Tambahkan penanganan jika kueri gagal, misalnya:
                        echo '<tr><td colspan="6" class="text-center">Gagal memuat data booking. Error: ' . htmlspecialchars($conn->error) . '</td></tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</section>
<script>
    // Fungsi cancel_book tetap ada
    function cancel_book($id){
        start_loader()
        $.ajax({
            url:_base_url_+"classes/Master.php?f=update_book_status",
            method:"POST",
            data:{id:$id,status:2},
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occured",'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("Book cancelled successfully",'success')
                    setTimeout(function(){
                        location.reload()
                    },2000)
                }else{
                    console.log(resp)
                    alert_toast("an error occured",'error')
                }
                end_loader()
            }
        })
    }
    $(function(){
        $('.cancel_data').click(function(){
            _conf("Are you sure to cancel this booking?","cancel_book",[$(this).data('id')])
        })

        // Inisialisasi DataTable tetap ada
        if ($.fn.dataTable) { // Periksa apakah DataTable sudah dimuat
             $('table').dataTable();
        }
    })
</script>