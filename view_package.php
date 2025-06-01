<?php 
if(isset($_GET['id'])){
    $packages = $conn->query("SELECT * FROM `packages` where md5(id) = '{$_GET['id']}'");
    if($packages->num_rows > 0){
        foreach($packages->fetch_assoc() as $k => $v){
            $$k = $v;
        }
    }

    $files = array();
    if(is_dir(base_app.'uploads/package_'.$id)){
        $ofile = scandir(base_app.'uploads/package_'.$id);
        foreach($ofile as $img){
            if(in_array($img,array('.','..')))
            continue;
            $files[] = validate_image('uploads/package_'.$id.'/'.$img);
        }
    }
}
?>
<section class="page-section">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="3000">
                    <div class="carousel-inner h-100">
                        <?php foreach($files as $k => $img): ?>
                        <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
                            <img class="d-block w-100  h-100" src="<?php echo $img ?>" alt="">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <div class="w-100">
                    <hr class="border-warning">
                    <div class="w-100 d-flex justify-content-between">
                        <span class="rounded-0 btn-flat btn-sm btn-primary d-flex align-items-center  justify-content-between"><i class="fa fa-tag"></i> <span class="ml-1"><?php echo number_format($cost) ?></span></span>
                        <button class="btn btn-flat btn-warning" type="button" id="book">Book Now</button>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <h3><?php echo $title ?></h3>
                <hr class="border-warning">
                <small class='text-muted'>Location: <?php echo $tour_location ?></small>
                <h4>Details</h4>
                <div><?php echo stripslashes(html_entity_decode($description)) ?></div>
                
                </div>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('#book').click(function(){
            if("<?php echo $_settings->userdata('id') ?>" > 0)
                uni_modal("Book Info","book_form.php?package_id=<?php echo $id ?>");
            else
                uni_modal("","login.php","large");
        })
    })
</script>