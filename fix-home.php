<?php
define('ABSPATH_GUARD', true);
require_once(__DIR__ . '/wp-load.php');
if (!function_exists('wp_insert_post')) die('WP not loaded');

global $wpdb;

// Find the home page
$home_page_id = get_option('page_on_front');
echo "page_on_front: $home_page_id\n";
echo "show_on_front: " . get_option('show_on_front') . "\n";

// Get current template
$tpl = $wpdb->get_var("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = $home_page_id AND meta_key = '_wp_page_template'");
echo "current _wp_page_template: '$tpl'\n";

// Get current post_content length
$content_len = $wpdb->get_var("SELECT LENGTH(post_content) FROM {$wpdb->posts} WHERE ID = $home_page_id");
echo "current post_content length: $content_len\n";

// Clear template to 'default'
$wpdb->delete($wpdb->postmeta, ['post_id' => $home_page_id, 'meta_key' => '_wp_page_template'], ['%d', '%s']);
$inserted = $wpdb->insert($wpdb->postmeta, ['post_id' => $home_page_id, 'meta_key' => '_wp_page_template', 'meta_value' => 'default'], ['%d', '%s', '%s']);
echo "Inserted _wp_page_template='default': " . ($inserted !== false ? "OK" : "FAILED: " . $wpdb->last_error) . "\n";

// Update post content with full hero HTML
$hero_content = <<<'HERO'
<!-- wp:cover {"url":"https://tjsitaliancafe.com/wp-content/uploads/2018/09/alcoholic-beverage-beverage-blur-1123260.jpg","dimRatio":45,"overlayColor":"dark-brown","minHeight":100,"minHeightUnit":"vh","align":"full","className":"tj-hero"} -->
<div class="wp-block-cover alignfull tj-hero" style="min-height:100vh;">
<span aria-hidden="true" class="wp-block-cover__background has-dark-brown-background-color has-background-dim" style="background-color:#1a0d06;opacity:0.45"></span>
<img class="wp-block-cover__image-background" alt="TJ's Italian Cafe wine and dining" src="https://tjsitaliancafe.com/wp-content/uploads/2018/09/alcoholic-beverage-beverage-blur-1123260.jpg" data-object-fit="cover"/>
<div class="wp-block-cover__inner-container">
<p class="tj-hero__located" style="font-family:'Times New Roman',Times,serif;font-size:clamp(1.8rem,4vw,4.5rem);font-weight:100;text-transform:uppercase;letter-spacing:4px;color:#ffffff;margin:0;line-height:1.1;text-align:center;text-shadow:0 2px 8px rgba(0,0,0,0.5);">LOCATED IN BEAUTIFUL</p>
<p class="tj-hero__script" style="font-family:'Yesteryear',cursive;font-size:clamp(2.5rem,6vw,5rem);font-weight:400;color:#ffffff;margin:0;line-height:1.4;text-align:center;text-shadow:0 2px 8px rgba(0,0,0,0.5);">Indian Rocks Beach, Florida</p>
</div>
</div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"color":{"background":"#fdf8f3"},"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"960px"}} -->
<div class="wp-block-group alignfull" style="background-color:#fdf8f3;padding:80px 24px;">
<h2 style="font-family:'Times New Roman',Times,serif;font-size:clamp(1.5rem,3vw,2.2rem);font-weight:400;letter-spacing:2px;color:#3a2011;margin-bottom:40px;text-align:center;">The essence of TJ's Italian Café as the ultimate dining destination on Indian Rocks Beach — Since 1989</h2>
<p style="font-size:1rem;line-height:1.8;color:#444;margin-bottom:20px;">The sun dips below the horizon, painting the Gulf of America in fiery hues of orange and pink, but on the barrier island of Indian Rocks Beach, Florida, the real heat is just igniting. Since 1989, TJ's Italian Café has reigned supreme as <em>the</em> who's-who spot, a culinary crown jewel where locals strut and visitors clamor to be seen.</p>
<p style="font-size:1rem;line-height:1.8;color:#444;margin-bottom:40px;">At the helm is Executive Chef Thomas J. Smith. Every dish is crafted from scratch — from hand-rolled meatballs to brick-oven pizzas, fresh seafood and handmade pasta, all using the finest ingredients. This is <em>the</em> ultimate dining experience.</p>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:32px;max-width:960px;margin:0 auto;">
<div style="text-align:center;"><a href="/menu/"><img src="https://tjsitaliancafe.com/wp-content/uploads/2017/12/viewmenu-300x300.png" alt="View Menu" style="max-width:180px;width:100%;margin:0 auto;display:block;"/></a><p style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:3px;color:#3a2011;margin-top:16px;"><a href="/menu/" style="color:#3a2011;">VIEW OUR MENU</a></p></div>
<div style="text-align:center;"><a href="/wine/"><img src="https://tjsitaliancafe.com/wp-content/uploads/2020/02/Untitled-design-2-300x300.png" alt="View Wines" style="max-width:180px;width:100%;margin:0 auto;display:block;"/></a><p style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:3px;color:#3a2011;margin-top:16px;"><a href="/wine/" style="color:#3a2011;">VIEW OUR WINES &amp; COCKTAIL BAR</a></p></div>
<div style="text-align:center;"><a href="/local-events/"><img src="https://tjsitaliancafe.com/wp-content/uploads/2017/12/events-300x300.png" alt="Local Events" style="max-width:180px;width:100%;margin:0 auto;display:block;"/></a><p style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:3px;color:#3a2011;margin-top:16px;"><a href="/local-events/" style="color:#3a2011;">VIEW LOCAL EVENTS</a></p></div>
</div>
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"color":{"background":"#3e2212"},"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull" style="background-color:#3e2212;padding:60px 24px;">
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:40px;max-width:1050px;margin:0 auto;text-align:center;">
<div><h3 style="font-family:'Yesteryear',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Location</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:1.8;">1515 Gulf Boulevard<br/>Indian Rocks Beach, FL 33785<br/><a href="tel:+17275961515" style="color:#d6ad8a;">727-596-1515</a></p></div>
<div><h3 style="font-family:'Yesteryear',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Hours</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:2;"><strong style="color:#fff;">Mon–Thu:</strong> 3pm – 10pm<br/><strong style="color:#fff;">Fri–Sun:</strong> 12pm – 10pm<br/><strong style="color:#fff;">Brunch Sat–Sun:</strong> 10am – 2pm</p></div>
<div><h3 style="font-family:'Yesteryear',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Reservations</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:1.8;"><a href="tel:+17275961515" style="color:#d6ad8a;font-size:1.2rem;font-weight:700;">727-596-1515</a></p></div>
</div>
</div>
<!-- /wp:group -->
HERO;

$updated = $wpdb->update(
    $wpdb->posts,
    [
        'post_content' => $hero_content,
        'post_modified' => current_time('mysql'),
        'post_modified_gmt' => current_time('mysql', true),
    ],
    ['ID' => $home_page_id],
    ['%s', '%s', '%s'],
    ['%d']
);
echo "Updated post_content: " . ($updated !== false ? "OK (rows: $updated)" : "FAILED: " . $wpdb->last_error) . "\n";
wp_cache_delete($home_page_id, 'posts');
clean_post_cache($home_page_id);
echo "Done!\n";
