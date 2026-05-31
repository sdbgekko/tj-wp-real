<?php
/**
 * TJ's Italian Cafe — WP-CLI Content Import
 * Run via: wp eval-file content-import-cli.php --allow-root
 */

// Check if already set up
if (get_option('tj_cafe_setup_complete')) {
    WP_CLI::log('TJ setup already complete. Skipping.');
    return;
}

WP_CLI::log('Starting TJ Italian Cafe content setup...');

// ---------------------------------------------------------------
// Site Settings
// ---------------------------------------------------------------
update_option('blogname', "TJ's Italian Cafe");
update_option('blogdescription', 'Indian Rocks Beach Italian Restaurant — Since 1989');
update_option('timezone_string', 'America/New_York');
update_option('permalink_structure', '/%postname%/');
update_option('default_comment_status', 'closed');
update_option('default_ping_status', 'closed');
WP_CLI::log('Site settings configured.');

// Flush rewrites
flush_rewrite_rules(true);

// ---------------------------------------------------------------
// Pages
// ---------------------------------------------------------------
$pages = [
    [
        'slug'    => 'home',
        'title'   => 'Home',
        'template' => 'home',
        'content' => '<!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:paragraph --><p>Welcome to TJ\'s Italian Cafe.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
    ],
    ['slug' => 'about-us',      'title' => "About TJ's Italian Cafe",     'template' => '', 'content' => '<!-- wp:paragraph --><p>Serving Indian Rocks Beach for over 38 years. 1515 Gulf Boulevard, Indian Rocks Beach, FL 33785. Call 727-596-1515.</p><!-- /wp:paragraph -->'],
    ['slug' => 'menu',          'title' => "TJ's Italian Cafe Menu",       'template' => '', 'content' => '<!-- wp:paragraph --><p>View our current menu. Call 727-596-1515 for takeout orders.</p><!-- /wp:paragraph -->'],
    ['slug' => 'wine',          'title' => 'Wine Collection',              'template' => '', 'content' => '<!-- wp:paragraph --><p>Chef TJ\'s hand-picked wine list. View our Wine & Cocktail list PDF.</p><!-- /wp:paragraph -->'],
    ['slug' => 'brunch',        'title' => 'Brunch',                       'template' => '', 'content' => '<!-- wp:paragraph --><p>Saturday & Sunday Brunch: 10am – 2pm. Indian Rocks Beach\'s finest brunch on the Gulf.</p><!-- /wp:paragraph -->'],
    ['slug' => 'gallery',       'title' => 'Gallery',                      'template' => '', 'content' => '<!-- wp:paragraph --><p>Photos from TJ\'s Italian Cafe.</p><!-- /wp:paragraph -->'],
    ['slug' => 'local-events',  'title' => 'Local Events',                 'template' => '', 'content' => '<!-- wp:paragraph --><p>TJ\'s is the heart of Indian Rocks Beach community celebrations. Call 727-596-1515 to book your event.</p><!-- /wp:paragraph -->'],
    ['slug' => 'maps-directions','title' => 'Maps & Directions',           'template' => '', 'content' => '<!-- wp:paragraph --><p>1515 Gulf Boulevard, Indian Rocks Beach, FL 33785. 1 mile South of Belleair Bridge. Call 727-596-1515.</p><!-- /wp:paragraph -->'],
    ['slug' => 'contact-us',    'title' => "Contact TJ's Italian Cafe",    'template' => '', 'content' => '<!-- wp:paragraph --><p>1515 Gulf Blvd | Indian Rocks Beach, FL 33785 | Ph: 727-596-1515. Mon–Wed: 3pm–10pm. Thu–Sun: 12pm–10pm.</p><!-- /wp:paragraph -->'],
];

$home_id = 0;
foreach ($pages as $p) {
    $existing = get_page_by_path($p['slug']);
    $args = [
        'post_title'    => $p['title'],
        'post_content'  => $p['content'],
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => $p['slug'],
    ];
    if ($p['template']) {
        $args['page_template'] = $p['template'] . '.html';
    }
    if ($existing) {
        $args['ID'] = $existing->ID;
        $id = wp_update_post($args);
    } else {
        $id = wp_insert_post($args);
    }

    if ($p['slug'] === 'home') {
        $home_id = $id;
    }
    WP_CLI::log("Page: {$p['title']} => ID $id");
}

// Set front page
if ($home_id) {
    update_option('page_on_front', $home_id);
    update_option('show_on_front', 'page');
    WP_CLI::log("Front page set to ID: $home_id");
}

// ---------------------------------------------------------------
// Nav Menu
// ---------------------------------------------------------------
$menu_name = 'Primary Navigation';
$menu_exists = wp_get_nav_menu_object($menu_name);
if ($menu_exists) {
    $menu_id = $menu_exists->term_id;
    // Clear it
    $items = wp_get_nav_menu_items($menu_id);
    if ($items) {
        foreach ($items as $item) {
            wp_delete_post($item->ID, true);
        }
    }
} else {
    $menu_id = wp_create_nav_menu($menu_name);
}

$nav_items = [
    ['Home',       home_url('/')],
    ['About Us',   home_url('/about-us/')],
    ['Menu',       home_url('/menu/')],
    ['Wine',       home_url('/wine/')],
    ['Brunch',     home_url('/brunch/')],
    ['Gallery',    home_url('/gallery/')],
    ['Events',     home_url('/local-events/')],
    ['Directions', home_url('/maps-directions/')],
    ['Contact',    home_url('/contact-us/')],
];

foreach ($nav_items as $i => $item) {
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'    => $item[0],
        'menu-item-url'      => $item[1],
        'menu-item-status'   => 'publish',
        'menu-item-type'     => 'custom',
        'menu-item-position' => $i + 1,
    ]);
}

// Assign to locations
set_theme_mod('nav_menu_locations', ['primary' => $menu_id, 'footer' => $menu_id]);
WP_CLI::log("Nav menu configured: {$menu_id}");

// Mark setup complete
update_option('tj_cafe_setup_complete', true);
WP_CLI::success('TJ Italian Cafe setup complete!');
