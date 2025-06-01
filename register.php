<style>
    #uni_modal .modal-content>.modal-footer,#uni_modal .modal-content>.modal-header{
        display:none;
    }
</style>
<div class="container-fluid">
    <h3 class="float-left">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </h3>
    <h3 class="text-center">Create New Account</h3>
    <hr class='border-primary'>
        <form action="" id="registration">
            <div class="form-group">
                <label for="" class="control-label">Firstname</label>
                <input type="text" class="form-control form-control-sm form" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Lastname</label>
                <input type="text" class="form-control form-control-sm form" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Username</label>
                <input type="text" class="form-control form-control-sm form" name="username" required>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Password</label>
                <input type="password" class="form-control form-control-sm form" name="password" required>
            </div>
            <div class="form-group d-flex justify-content-end">
                <button class="btn btn-primary btn-flat">Register</button>
            </div>
        </form>
</div>
<script>
    $(function(){
        // Cache selector form registrasi karena digunakan beberapa kali
        var $registrationForm = $('#registration');

        $registrationForm.submit(function(e){
            e.preventDefault();
            start_loader(); // Memulai loader

            // Hapus pesan error lama yang spesifik untuk form ini
            $registrationForm.find('.err-msg').remove();

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=register",
                method: "POST",
                data: $(this).serialize(), // $(this) di sini mengacu pada form yang disubmit ($registrationForm)
                dataType: "json",
                error: function(jqXHR, textStatus, errorThrown){
                    console.error("AJAX Error:", textStatus, errorThrown);
                    console.error("Response Text:", jqXHR.responseText);
                    alert_toast("An error occurred while connecting to the server.", "error");
                    end_loader(); // Menghentikan loader jika AJAX error
                },
                success: function(resp){
                    // DEBUG: Tampilkan respon mentah dari server di console browser
                    console.log("Server Response:", resp);
                    
                    // Panggil end_loader() sekali di awal blok success,
                    // karena request AJAX itu sendiri sudah berhasil sampai ke server dan mendapat respon.
                    // Jika ada error logika dari server (misal resp.status 'failed'), loader tetap harus berhenti.
                    end_loader();

                    if(typeof resp === 'object' && resp !== null && resp.status === 'success'){
                        // Notifikasi sukses
                        alert_toast("Account successfully registered!", 'success');
                        
                        // Kosongkan field form setelah berhasil menggunakan cached selector
                        $registrationForm[0].reset();
                        
                        // Menutup modal registrasi secara otomatis
                        var $currentModal = $registrationForm.closest('.modal');
                        if ($currentModal.length && typeof $currentModal.modal === 'function') {
                            $currentModal.modal('hide');
                        } else {
                            console.warn("Registration modal could not be closed automatically.");
                        }
                        // Tidak ada pengalihan ke form login, pengguna akan melakukannya manual

                    } else if (resp && resp.status === 'failed' && resp.msg) {
                        // Jika server mengembalikan status 'failed' dengan pesan
                        var _err_el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg);
                        $registrationForm.prepend(_err_el); // Prepend ke cached form
                    } else {
                        // Jika respon tidak sesuai format yang diharapkan atau error lain dari server
                        console.error("Unexpected server response structure:", resp);
                        alert_toast("An unexpected error occurred. Please check console.", 'error');
                    }
                }
            });
        });
    });
</script>