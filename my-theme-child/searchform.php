<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<form role="search" method="get" class="searchform group" action="<?php echo home_url( '/' ); ?>">
 <label>
 <span class="offscreen"><?php echo _x( '', 'label' ) ?></span>
 <input type="search" class="search-field"
 placeholder="<?php echo esc_attr_x( '', 'placeholder' ) ?>"
 value="<?php echo get_search_query() ?>" name="s"
 title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
 </label>
<button type="Submit" class="search-submit">
</button>

</form>