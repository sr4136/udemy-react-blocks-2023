<?php

/**
 * Plugin Name: First Block
 * Plugin URI: #
 * Description: Udemy React Blocks
 * Aughor: SteveR
 * Author URI: steverudolfi.com
 */

function blocks_course_firstblock_init() {
    register_block_type_from_metadata(__DIR__);
}
add_action("init", "blocks_course_firstblock_init");
