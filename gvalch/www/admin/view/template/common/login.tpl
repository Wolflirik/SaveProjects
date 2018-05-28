<?php echo $header; ?>
<div class="login-box">
    <div class="line-group">
        <label><?php echo $text_login; ?></label>
        <input name="username" type="text" required>
    </div>
    <div class="line-group">
        <label><?php echo $text_password; ?></label>
        <input name="password" type="password" required>
    </div>
    <button class="button submit"><?php echo $button_submit; ?></button>
</div>
<script>
    $(document).ready(function(){
        $(this).on('click', '.submit', function(){
            $.ajax({
                url: '/admin/auth/login',
                type: 'post',
                data: 'username='+$("input[name=\'username\']").val()+'&password='+$("input[name=\'password\']").val(),
                dataType: 'json',
                success: function(data){
                    if(data['success']){
                        window.location = '/admin';
                    }else{
                        $('.login-box').addClass('error');
                    }
                }
            });
        });

        $(document).on('focus', '.login-box input', function(){
            $(this).parent().parent().removeClass('error');
        })
    })
</script>
<?php echo $footer; ?>