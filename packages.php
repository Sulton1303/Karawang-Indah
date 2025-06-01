<?php
if (!defined('base_app')) define('base_app', __DIR__ . '/');
if (!function_exists('validate_image')) {
    function validate_image($file_path) {
        return (file_exists($file_path) && !is_dir($file_path)) ? $file_path : 'path/to/default/image.jpg';
    }
}
?>
<section class="page-section bg-dark" id="home">
    <div class="container">
        <h2 class="text-center">Tour Packages</h2>
    <div class="d-flex w-100 justify-content-center">
        <hr class="border-warning" style="border:3px solid" width="15%">
    </div>
    <div class="w-100">
        <?php
        // Ambil semua paket
        $packages_query = $conn->query("SELECT * FROM `packages` ORDER BY rand()");
        if ($packages_query) {
            while($row = $packages_query->fetch_assoc()):
                $cover='';
                if(is_dir(base_app . 'uploads/package_' . $row['id'])){
                    $img = scandir(base_app . 'uploads/package_' . $row['id']);
                    $k = array_search('.', $img);
                    if($k !== false) unset($img[$k]);
                    $k = array_search('..', $img);
                    if($k !== false) unset($img[$k]);
                    $cover = isset($img[2]) ? 'uploads/package_' . $row['id'] . '/' . $img[2] : "";
                }
                $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));

        ?>
            <div class="card d-flex w-100 rounded-0 mb-3 package-item">
                <img class="card-img-top" src="<?php echo validate_image($cover) ?>" alt="<?php echo htmlspecialchars($row['title']) ?>" height="200rem" style="object-fit:cover">
                <div class="card-body">
                    <h5 class="card-title truncate-1"><?php echo htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text truncate"><?php echo htmlspecialchars($row['description']) ?></p>
                    <div class="w-100 d-flex justify-content-between">
                        <span class="rounded-0 btn btn-flat btn-sm btn-primary"><i class="fa fa-tag"></i> <?php echo number_format($row['cost']) ?></span>
                        <a href="./?page=view_package&id=<?php echo md5($row['id']) ?>" class="btn btn-sm btn-flat btn-warning">View Package <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
        } else {
            echo "<p class='text-center text-white'>Gagal mengambil data paket wisata.</p>";
        }
        ?>
    </div>
    </div>
</section>