<li class="pb-3">
    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" style="text-transform: capitalize;" href="#">
        <?php echo htmlspecialchars($cat_subcat[0]['cat_name']); ?>
        <i class="pull-right fa fa-fw fa-chevron-circle-down mt-1"></i>
    </a>
    <ul id="collapseThree" class="collapse list-unstyled pl-3">
        <?php 
        foreach ($cat_subcat[1]['subcats'] as $subcategory) {
            if (isset($subcategory['sub_cat_name'])) {
                echo '<li><a class="text-decoration-none" style="text-transform: capitalize;" href=?cat='. $subcategory['sub_cat_id'] .'>' . htmlspecialchars($subcategory['sub_cat_name']) . '</a></li>';
            }
        }
        ?>
    </ul>
</li>