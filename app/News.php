<?php

class News {

    public static function HTMLtop() {
?>

        <div class="container">
            <div class="row">
<?php
    }

    public static function HTMLrow($heading, $content) {
?>
                <div class="col-md-4">
                    <h2><?php echo $heading; ?></h2>
                    <?php echo $content; ?>
                </div>
<?php
    }

    public static function HTMLbottom() {
?>
            </div>
        </div>
<?php
	}
}

?>