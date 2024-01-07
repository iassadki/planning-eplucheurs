<section id="main-section">
        <?php
          // Define $info before using it
          // Define $error before using it
        if(isset($page)) {
            
            require($page.".php");
            
        }
        else{
            require("login.php");
        }
        ?>
</section>