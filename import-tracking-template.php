
<?php
/*
Template Name: import-tracking
*/

get_header();
?>

<div class="import-tracking">
    <form class="traking-form">
        <input type="text" placeholder="Введите номер клиента, например 1789" name="search-value">
        <input type="submit" value="Поиск">
    </form>
    <!-- <p style="padding-left: 27px;font-size: 13.333px;color: #757575;width: 50%;margin: auto;">Например: 1789</p>-->
    <div class="tracking-result">

    </div>
</div>



<?php get_footer(); ?>