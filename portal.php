<style>
    header.masthead{
        background-image: url('<?php echo validate_image($_settings->info('cover')) ?>') !important;
    }
    header.masthead .container{
        background:#0000006b;
    }
</style>
<header class="masthead">
    <div class="container">
        <div class="masthead-subheading">Selamat datang di website Karawang Indah!</div>
        <div class="masthead-heading text-uppercase">Lihat Paket Wisata Kami!</div>
        <a class="btn btn-primary btn-xl text-uppercase" href="#home">Lihat Tempat</a>
    </div>
</header>
<section class="page-section bg-dark" id="home">
    <div class="container">
        <h2 class="text-center">Tour Packages</h2>
    <div class="d-flex w-100 justify-content-center">
        <hr class="border-warning" style="border:3px solid" width="15%">
    </div>
    <div class="row">
        <?php
        $packages = $conn->query("SELECT * FROM `packages` order by rand() limit 3");
            while($row = $packages->fetch_assoc() ):
                $cover='';
                if(is_dir(base_app.'uploads/package_'.$row['id'])){
                    $img = scandir(base_app.'uploads/package_'.$row['id']);
                    $k = array_search('.',$img);
                    if($k !== false)
                        unset($img[$k]);
                    $k = array_search('..',$img);
                    if($k !== false)
                        unset($img[$k]);
                    $cover = isset($img[2]) ? 'uploads/package_'.$row['id'].'/'.$img[2] : "";
                }
                $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
        ?>
            <div class="col-md-4 p-4 ">
                <div class="card w-100 rounded-0">
                    <img class="card-img-top" src="<?php echo validate_image($cover) ?>" alt="<?php echo $row['title'] ?>" height="200rem" style="object-fit:cover">
                    <div class="card-body">
                    <h5 class="card-title truncate-1 w-100"><?php echo $row['title'] ?></h5><br>
                    <p class="card-text truncate"><?php echo $row['description'] ?></p>
                    <div class="w-100 d-flex justify-content-end">
                        <a href="./?page=view_package&id=<?php echo md5($row['id']) ?>" class="btn btn-sm btn-flat btn-warning">View Package <i class="fa fa-arrow-right"></i></a>
                    </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="d-flex w-100 justify-content-end">
        <a href="./?page=packages" class="btn btn-flat btn-warning mr-4">Explore Package <i class="fa fa-arrow-right"></i></a>
    </div>
    </div>
</section>
<section class="page-section" id="about">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">About Us</h2>
        </div>
        <div>
            <div class="card w-100">
                <div class="card-body">
                    <?php echo file_get_contents(base_app.'about.html') ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Contact Us</h2>
            <h3 class="section-subheading text-muted">Send us a message for inquiries.</h3>
        </div>
        <form id="contactForm" >
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="name" name="name" type="text" placeholder="Your Name *" required />
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="email" name="email" type="email" placeholder="Your Email *" data-sb-validations="required,email" />
                    </div>
                    <div class="form-group mb-md-0">
                        <input class="form-control" id="subject" name="subject" type="subject" placeholder="Subject *" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea class="form-control" id="message" name="message" placeholder="Your Message *" required></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">Send Message</button>
            </div>
        </form>
    </div>
</section>
<script>
$(function(){
    $('#contactForm').submit(function(e){
        e.preventDefault()
        $.ajax({
            url:_base_url_+"classes/Master.php?f=save_inquiry",
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occured",'error')
                end_loader()
            },
            success:function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("Inquiry sent",'success')
                    $('#contactForm').get(0).reset()
                }else{
                    console.log(resp)
                    alert_toast("an error occured",'error')
                    end_loader()
                }
            }
        })
    })
})
</script>