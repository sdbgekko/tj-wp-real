<?php
/**
 * TJ's Italian Cafe — One-time Content Import Script
 *
 * Access: https://your-domain.com/content-import.php?key=TJsCafe2026Setup
 * Runs once, creates pages, sets up theme, then disables itself.
 *
 * SECURITY: Delete this file after first run, OR the key check is sufficient
 * since the key is not in any public repo.
 */

define('ABSPATH_GUARD', true);

// Security key
$secret_key = 'TJsCafe2026Setup';
if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    http_response_code(403);
    die('Access denied.');
}

// Load WordPress
$wp_load = __DIR__ . '/wp-load.php';
if (!file_exists($wp_load)) {
    die('WordPress not found at: ' . $wp_load);
}
require_once($wp_load);

// Verify WP loaded
if (!function_exists('wp_insert_post')) {
    die('WordPress failed to load.');
}

$log = [];
$errors = [];

function log_msg($msg) {
    global $log;
    $log[] = date('H:i:s') . ' — ' . $msg;
}

function log_err($msg) {
    global $errors;
    $errors[] = date('H:i:s') . ' ERROR: ' . $msg;
}

// ---------------------------------------------------------------
// 1. Set Active Theme
// ---------------------------------------------------------------
$current_theme = wp_get_theme();
log_msg('Current theme: ' . $current_theme->get('Name'));

$tj_theme = wp_get_theme('tj-italian-cafe-clone');
if ($tj_theme->exists()) {
    switch_theme('tj-italian-cafe-clone');
    log_msg('Switched to TJ Italian Cafe Clone theme.');
} else {
    log_err('TJ Italian Cafe Clone theme not found! Check theme directory.');
}

// ---------------------------------------------------------------
// 1b. DIRECT HOME PAGE FIX: Update home page content using raw $wpdb based on page_on_front option
// This bypasses all WP validation issues
// ---------------------------------------------------------------
global $wpdb;
$front_page_id = (int)get_option('page_on_front');
if ($front_page_id > 0) {
    // Clear _wp_page_template to 'default'
    $existing_tpl = $wpdb->get_var($wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_wp_page_template'",
        $front_page_id
    ));
    $wpdb->delete($wpdb->postmeta, ['post_id' => $front_page_id, 'meta_key' => '_wp_page_template'], ['%d', '%s']);
    $wpdb->insert($wpdb->postmeta, ['post_id' => $front_page_id, 'meta_key' => '_wp_page_template', 'meta_value' => 'default'], ['%d', '%s', '%s']);
    // Update post_content with full hero HTML
    $home_hero = <<<'HERO'
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
<p style="font-size:1rem;line-height:1.8;color:#444;margin-bottom:40px;">At the helm is Executive Chef Thomas J. Smith. Every dish is crafted from scratch — hand-rolled meatballs, brick-oven pizzas, fresh seafood and handmade pasta, all using the finest ingredients.</p>
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
<div><h3 style="font-family:'Yesteryear',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Hours</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:2;"><strong style="color:#fff;">Mon–Thu:</strong> 3pm–10pm<br/><strong style="color:#fff;">Fri–Sun:</strong> 12pm–10pm<br/><strong style="color:#fff;">Brunch Sat–Sun:</strong> 10am–2pm</p></div>
<div><h3 style="font-family:'Yesteryear',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Reservations</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:1.8;"><a href="tel:+17275961515" style="color:#d6ad8a;font-size:1.2rem;font-weight:700;">727-596-1515</a></p></div>
</div>
</div>
<!-- /wp:group -->
HERO;
    $updated = $wpdb->update(
        $wpdb->posts,
        ['post_content' => $home_hero, 'post_modified' => current_time('mysql'), 'post_modified_gmt' => current_time('mysql', true)],
        ['ID' => $front_page_id],
        ['%s', '%s', '%s'],
        ['%d']
    );
    if ($updated !== false) {
        wp_cache_delete($front_page_id, 'posts');
        clean_post_cache($front_page_id);
        log_msg("DIRECT DB: Updated front page ID {$front_page_id} content (hero). Template was '$existing_tpl' → cleared to 'default'. Rows: $updated.");
    } else {
        log_err("DIRECT DB: Failed to update front page ID {$front_page_id}: " . $wpdb->last_error);
    }
} else {
    log_msg("No page_on_front set yet — will be set after pages loop.");
}

// ---------------------------------------------------------------
// 1c. Clear ALL FSE template + template_part DB overrides
// WordPress FSE stores customized templates as wp_template/wp_template_part posts in the DB
// with slugs like "theme-slug//template-name". These OVERRIDE theme files.
// Delete ALL of them so the theme's file-based templates are used fresh.
// ---------------------------------------------------------------
global $wpdb;
$fse_template_ids = $wpdb->get_col(
    "SELECT ID FROM {$wpdb->posts} WHERE post_type IN ('wp_template','wp_template_part') AND post_status != 'auto-draft'"
);
$deleted_count = 0;
foreach ($fse_template_ids as $tpl_id) {
    $tpl_name = $wpdb->get_var("SELECT post_name FROM {$wpdb->posts} WHERE ID = {$tpl_id}");
    $wpdb->delete($wpdb->posts, ['ID' => $tpl_id], ['%d']);
    $wpdb->delete($wpdb->postmeta, ['post_id' => $tpl_id], ['%d']);
    $deleted_count++;
    log_msg("Deleted FSE template DB override: {$tpl_name} (ID: {$tpl_id})");
}
if ($deleted_count === 0) {
    log_msg("No FSE template DB overrides found (theme files will be used directly).");
}
wp_cache_flush();

// ---------------------------------------------------------------
// 2. Site Settings
// ---------------------------------------------------------------
update_option('blogname', "TJ's Italian Cafe");
update_option('blogdescription', 'Indian Rocks Beach Italian Restaurant — Since 1989');
update_option('admin_email', 'tj@tjsitaliancafe.com');
update_option('timezone_string', 'America/New_York');
update_option('date_format', 'F j, Y');
update_option('time_format', 'g:i a');
update_option('permalink_structure', '/%postname%/');
log_msg('Site settings updated.');

// ---------------------------------------------------------------
// 3. Pages Definition
// ---------------------------------------------------------------
$home_content = '<!-- wp:cover {"url":"https://tjsitaliancafe.com/wp-content/uploads/2018/09/alcoholic-beverage-beverage-blur-1123260.jpg","dimRatio":45,"overlayColor":"dark-brown","minHeight":100,"minHeightUnit":"vh","align":"full","className":"tj-hero"} -->
<div class="wp-block-cover alignfull tj-hero" style="min-height:100vh;">
<span aria-hidden="true" class="wp-block-cover__background has-dark-brown-background-color has-background-dim" style="background-color:#1a0d06;opacity:0.45"></span>
<img class="wp-block-cover__image-background" alt="TJ\'s Italian Cafe wine and dining" src="https://tjsitaliancafe.com/wp-content/uploads/2018/09/alcoholic-beverage-beverage-blur-1123260.jpg" data-object-fit="cover"/>
<div class="wp-block-cover__inner-container">
<p class="tj-hero__located" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,4vw,4.5rem);font-weight:100;text-transform:uppercase;letter-spacing:4px;color:#ffffff;margin:0;line-height:1.1;text-align:center;text-shadow:0 2px 8px rgba(0,0,0,0.5);">LOCATED IN BEAUTIFUL</p>
<p class="tj-hero__script" style="font-family:\'Yesteryear\',cursive;font-size:clamp(2.5rem,6vw,5rem);font-weight:400;color:#ffffff;margin:0;line-height:1.4;text-align:center;text-shadow:0 2px 8px rgba(0,0,0,0.5);">Indian Rocks Beach, Florida</p>
</div>
</div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"color":{"background":"#fdf8f3"},"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"960px"}} -->
<div class="wp-block-group alignfull" style="background-color:#fdf8f3;padding:80px 24px;">
<h2 style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.5rem,3vw,2.2rem);font-weight:400;letter-spacing:2px;color:#3a2011;margin-bottom:40px;text-align:center;">The essence of TJ\'s Italian Café as the ultimate dining destination on Indian Rocks Beach — Since 1989</h2>
<p style="font-size:1rem;line-height:1.8;color:#444;margin-bottom:20px;">The sun dips below the horizon, painting the Gulf of America in fiery hues of orange and pink, but on the barrier island of Indian Rocks Beach, Florida, the real heat is just igniting. Since 1989, TJ\'s Italian Café has reigned supreme as <em>the</em> who\'s-who spot, a culinary crown jewel where locals strut and visitors clamor to be seen.</p>
<p style="font-size:1rem;line-height:1.8;color:#444;margin-bottom:20px;">At the helm is Executive Chef Thomas J. Smith, a maestro with a spatula, commanding a brigade of nearly a dozen chefs. Every dish is crafted from scratch — from hand-rolled meatballs to brick-oven pizzas, fresh seafood risotto and handmade pasta, all using the finest ingredients.</p>
<p style="font-size:1rem;line-height:1.8;color:#444;margin-bottom:40px;">The dessert tray rolls out like a grand finale: towering tiramisu, molten chocolate lava cakes, and cannoli so crisp and creamy you\'ll swear they were kissed by an Italian nonna. This is <em>the</em> ultimate dining experience — a bold, unapologetic celebration of life\'s finest pleasures.</p>
<!-- VIEW MENU / WINE / EVENTS 3-col -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:32px;max-width:960px;margin:0 auto;">
<div style="text-align:center;"><a href="/menu/"><img src="https://tjsitaliancafe.com/wp-content/uploads/2017/12/viewmenu-300x300.png" alt="View Menu" style="max-width:180px;width:100%;margin:0 auto;display:block;"/></a><p style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:3px;color:#3a2011;margin-top:16px;"><a href="/menu/" style="color:#3a2011;">VIEW OUR MENU</a></p></div>
<div style="text-align:center;"><a href="/wine/"><img src="https://tjsitaliancafe.com/wp-content/uploads/2020/02/Untitled-design-2-300x300.png" alt="View Wines" style="max-width:180px;width:100%;margin:0 auto;display:block;"/></a><p style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:3px;color:#3a2011;margin-top:16px;"><a href="/wine/" style="color:#3a2011;">VIEW OUR WINES &amp; COCKTAIL BAR</a></p></div>
<div style="text-align:center;"><a href="/local-events/"><img src="https://tjsitaliancafe.com/wp-content/uploads/2017/12/events-300x300.png" alt="Local Events" style="max-width:180px;width:100%;margin:0 auto;display:block;"/></a><p style="font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:3px;color:#3a2011;margin-top:16px;"><a href="/local-events/" style="color:#3a2011;">VIEW LOCAL EVENTS</a></p></div>
</div>
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"color":{"background":"#3a2011"},"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull" style="background-color:#3a2011;padding:80px 24px;">
<h2 style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#fff;text-align:center;margin-bottom:48px;">Our Customer Testimonials</h2>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:32px;max-width:1050px;margin:0 auto;">
<div style="background:rgba(255,255,255,0.06);border:1px solid rgba(214,173,138,0.2);padding:32px 24px;"><p style="color:#c9a84c;margin-bottom:12px;">&#9733;&#9733;&#9733;&#9733;&#9733;</p><p style="font-style:italic;font-size:1rem;color:rgba(255,255,255,0.9);line-height:1.7;margin-bottom:16px;">"The service was attentive and the food absolutely delicious. I really loved the huge Meatballs and the pasta was perfectly cooked. We will certainly add this lovely place to our favourites list."</p><p style="color:#d6ad8a;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;">Bigideas1983 — TripAdvisor</p></div>
<div style="background:rgba(255,255,255,0.06);border:1px solid rgba(214,173,138,0.2);padding:32px 24px;"><p style="color:#c9a84c;margin-bottom:12px;">&#9733;&#9733;&#9733;&#9733;&#9733;</p><p style="font-style:italic;font-size:1rem;color:rgba(255,255,255,0.9);line-height:1.7;margin-bottom:16px;">"Great Food, Excellent Service!! Always a pleasure dining here. The atmosphere is wonderful and Chef TJ never disappoints. This is our go-to spot on Indian Rocks Beach!"</p><p style="color:#d6ad8a;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;">Glenn — Google Review</p></div>
<div style="background:rgba(255,255,255,0.06);border:1px solid rgba(214,173,138,0.2);padding:32px 24px;"><p style="color:#c9a84c;margin-bottom:12px;">&#9733;&#9733;&#9733;&#9733;&#9733;</p><p style="font-style:italic;font-size:1rem;color:rgba(255,255,255,0.9);line-height:1.7;margin-bottom:16px;">"Always Great! We\'ve been coming to TJ\'s for years. It\'s the kind of place where they remember your name and your favorite table. Truly a landmark of Indian Rocks Beach."</p><p style="color:#d6ad8a;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;">Vicki — TripAdvisor</p></div>
</div>
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"color":{"background":"#3e2212"},"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull" style="background-color:#3e2212;padding:60px 24px;">
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:40px;max-width:1050px;margin:0 auto;text-align:center;">
<div><h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Location</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:1.8;">1515 Gulf Boulevard<br/>Indian Rocks Beach, FL 33785<br/>1 mile South of Belleair Bridge<br/><a href="tel:+17275961515" style="color:#d6ad8a;">727-596-1515</a></p></div>
<div><h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Hours</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:2;"><strong style="color:#fff;">Mon–Thu:</strong> 3pm – 10pm<br/><strong style="color:#fff;">Fri–Sun:</strong> 12pm – 10pm<br/><strong style="color:#fff;">Brunch Sat–Sun:</strong> 10am – 2pm<br/><strong style="color:#fff;">Takeout Daily:</strong> 3pm – Close</p></div>
<div><h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#d6ad8a;margin-bottom:12px;">Reservations</h3><p style="color:rgba(255,255,255,0.85);font-size:0.9rem;line-height:1.8;">Call for your next Special Celebration.<br/><a href="tel:+17275961515" style="color:#d6ad8a;font-size:1.2rem;font-weight:700;">727-596-1515</a></p></div>
</div>
</div>
<!-- /wp:group -->';

$pages = [
    [
        'slug'    => '',   // home
        'title'   => 'Home',
        'template' => '', // empty = default page template; page-hero is hidden via CSS for .home
        'content' => $home_content,
    ],
    [
        'slug'    => 'about-us',
        'title'   => "About TJ's Italian Cafe",
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:paragraph {"style":{"color":{"text":"#BC0C06"},"typography":{"fontSize":"0.7rem","fontWeight":"700","textTransform":"uppercase","letterSpacing":"4px"}}} -->
<p style="color:#BC0C06;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:4px;">Family Owned Since 1989</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2.2rem,4vw,3.5rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2.2rem,4vw,3.5rem);color:#3a2011;">Serving Our Guests for Over 38 Years!</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.1rem;line-height:1.8;">No longer the best kept secret on the Gulf Beaches....TJ&#8217;s Italian Cafe of 38-years, has become the favorite dining spot of locals and visitors alike. Locals take advantage of visiting TJ&#8217;s often during the offseason.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.05rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.05rem;line-height:1.8;">Beachy and casual, yet elegant and tasteful, you can dine inside or enjoy a cool gulf breeze from the palm-laden deck outdoors. Owners Thomas John Smith, (also known as Chef TJ) and Kim Grady-Smith are warm and inviting hosts, and Chef TJ lives up to his reputation as one of the best chefs on the Gulf Beaches, personally overseeing every dish that leaves his kitchen.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.05rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.05rem;line-height:1.8;">38 years ago, the restaurant started as TJ&#8217;s Gourmet Pizza. Today, the specialty pizzas include Spinach Ranch Pie, topped with spinach, mushrooms, tomatoes, imported cheeses, and homemade ranch sauce&#8230;or the Shrimp and Asparagus Pie, smothered with huge juicy shrimp, garlic, and olive oil and a blend of imported cheeses.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.05rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.05rem;line-height:1.8;">TJ&#8217;s serves lunch and dinner daily with a menu including brick oven sandwiches, caf&#233; burgers, gourmet pizzas, and hearty subs. Says Chef TJ, &#8220;If you come to dine with us for lunch, you&#8217;ll definitely return for dinner.&#8221;</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.05rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.05rem;line-height:1.8;">Chef TJ offers a superb wine list, hand-picked himself with some help from his locals. You will find your favorite pairing from champagne, imported and domestic wines and beer will satisfy even the most insatiable palate.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"color":{"background":"#fdf8f3"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"},"margin":{"top":"40px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="background-color:#fdf8f3;padding:40px;margin-top:40px;">
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"1.8rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:1.8rem;color:#3a2011;">Hours of Operation</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong>Monday &#8211; Wednesday:</strong> 3:00pm &#8211; 10:00pm<br/><strong>Thursday &#8211; Sunday:</strong> 12:00pm &#8211; 10:00pm<br/><strong>Brunch Saturday &#8211; Sunday:</strong> 10:00am &#8211; 2:00pm<br/><strong>Takeout Daily:</strong> 3:00pm &#8211; Close</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>Address:</strong> 1515 Gulf Boulevard, Indian Rocks Beach, FL 33785<br/><strong>Phone:</strong> <a href="tel:+17275961515">727-596-1515</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'menu',
        'title'   => "TJ's Italian Cafe Menu",
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2rem,4vw,3rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2rem,4vw,3rem);color:#3a2011;">Our Menu</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Chef TJ&#8217;s menu features the finest Italian cuisine on the Gulf Coast — from brick oven specialties to handmade pastas, fresh Gulf seafood, and classic Italian favorites.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem"}}} -->
<p style="font-size:1.1rem;"><strong>View our current menu below:</strong></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="width:100%;text-align:center;margin:32px 0;">
  <iframe src="https://tjsitaliancafe.com/wp-content/uploads/2026/04/TJs-Menu-April-2026.pdf" width="100%" height="900px" style="border:1px solid #d6ad8a;" title="TJs Italian Cafe Menu"></iframe>
  <p style="margin-top:12px;"><a href="https://tjsitaliancafe.com/wp-content/uploads/2026/04/TJs-Menu-April-2026.pdf" target="_blank" style="color:#BC0C06;font-weight:700;text-transform:uppercase;letter-spacing:2px;font-size:0.85rem;">Download Menu PDF</a></p>
</div>
<!-- /wp:html -->

<!-- wp:group {"style":{"color":{"background":"#fdf8f3"},"spacing":{"padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"top":"40px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="background-color:#fdf8f3;padding:32px;margin-top:40px;text-align:center;">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem"}}} -->
<p style="font-size:1rem;"><strong>Takeout Orders Available Daily</strong><br/>Call us at <a href="tel:+17275961515" style="color:#BC0C06;font-weight:700;">727-596-1515</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'wine',
        'title'   => 'Wine Collection',
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2rem,4vw,3rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2rem,4vw,3rem);color:#3a2011;">Wine Collection &amp; Cocktail Bar</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.1rem;line-height:1.8;">Chef TJ offers a superb wine list, hand-picked himself with some help from his locals. From champagne to imported and domestic wines, our list has the perfect pairing for every dish.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>The bar at TJ&#8217;s features perfectly muddled Manhattans, velvety martinis, and top-shelf cocktails — each one crafted to complement the Gulf sunset and Frank Sinatra&#8217;s timeless croon that fills our dining room.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="width:100%;text-align:center;margin:32px 0;">
  <p style="font-size:1rem;margin-bottom:16px;">View our Wine &amp; Cocktail list:</p>
  <iframe src="https://tjsitaliancafe.com/wp-content/uploads/2024/09/TJs-Wine-2024.pdf" width="100%" height="900px" style="border:1px solid #d6ad8a;" title="TJs Wine List"></iframe>
  <p style="margin-top:12px;"><a href="https://tjsitaliancafe.com/wp-content/uploads/2024/09/TJs-Wine-2024.pdf" target="_blank" style="color:#BC0C06;font-weight:700;text-transform:uppercase;letter-spacing:2px;font-size:0.85rem;">Download Wine List PDF</a></p>
</div>
<!-- /wp:html -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'contact-us',
        'title'   => "Contact TJ's Italian Cafe",
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:columns {"style":{"spacing":{"blockGap":"60px"}}} -->
<div class="wp-block-columns" style="gap:60px;">

<!-- wp:column -->
<div class="wp-block-column">

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"2rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:2rem;color:#3a2011;">Call for Reservations</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Call for reservations for your next Special Celebration.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem","fontWeight":"700"},"color":{"text":"#BC0C06"}}} -->
<p style="font-size:1.5rem;font-weight:700;"><a href="tel:+17275961515" style="color:#BC0C06;">727-596-1515</a></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"2rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:2rem;color:#3a2011;margin-top:40px;">Hours of Operation</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Monday &#8211; Wednesday:</strong> 3:00pm &#8211; 10:00pm<br/><strong>Thursday &#8211; Sunday:</strong> 12:00pm &#8211; 10:00pm<br/><strong>Brunch Saturday &#8211; Sunday:</strong> 10:00am &#8211; 2:00pm<br/><strong>Takeout Daily:</strong> 3:00pm &#8211; Close</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"2rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:2rem;color:#3a2011;margin-top:40px;">Address</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>1515 Gulf Boulevard<br/>Indian Rocks Beach, FL 33785<br/>1 mile South of Belleair Bridge</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Follow us: <a href="https://www.facebook.com/TJsItalianCafe" target="_blank">Facebook</a> &amp; <a href="https://www.instagram.com/TJsItalianCafe" target="_blank">Instagram</a> @TJsItalianCafe</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"2rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:2rem;color:#3a2011;margin-top:40px;">Employment Opportunities</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Please call <a href="tel:+17275961515">727-596-1515</a> or contact us through Facebook or Instagram @TJsItalianCafe.</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">

<!-- wp:html -->
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3529.2!2d-82.8417!3d27.9118!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2ede2e1234567%3A0x1234567890abcdef!2sTJ&#39;s%20Italian%20Cafe!5e0!3m2!1sen!2sus!4v1234567890" width="100%" height="400" style="border:0;border:1px solid #d6ad8a;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="TJs Italian Cafe Map"></iframe>
<!-- /wp:html -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'gallery',
        'title'   => 'Gallery',
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2rem,4vw,3rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2rem,4vw,3rem);color:#3a2011;text-align:center;margin-bottom:48px;">Gallery</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":3,"linkTo":"none","sizeSlug":"large","style":{"spacing":{"blockGap":{"top":"4px","left":"4px"}}}} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2018/03/restaurant-love-romantic-dinner.jpg" alt="TJs Italian Cafe romantic dinner"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2025/01/group-of-middle-aged-friends-celebrating-in-bar-to-2024-10-19-09-36-54-utc-1.jpg" alt="Friends celebrating at TJs"/></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2025/03/capturing-friendship-moments-on-smartphone-2025-02-24-19-00-32-utc.jpg" alt="Friends at TJs Italian Cafe"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'local-events',
        'title'   => 'Local Events',
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2rem,4vw,3rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2rem,4vw,3rem);color:#3a2011;">Local Events</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>TJ&#8217;s Italian Cafe is the heart of Indian Rocks Beach&#8217;s community celebrations. From Father&#8217;s Day to graduation parties, we&#8217;re the go-to spot for life&#8217;s memorable moments.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"1.8rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:1.8rem;color:#3a2011;margin-top:48px;">Celebrating 36 Years!</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>TJ&#8217;s Italian Cafe has been serving the Indian Rocks Beach community since 1989 — making us one of the longest-running restaurants on the Gulf Coast.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"1.8rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:1.8rem;color:#3a2011;margin-top:48px;">Private Dining &amp; Special Events</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Planning a special celebration? Call us at <a href="tel:+17275961515" style="color:#BC0C06;font-weight:700;">727-596-1515</a> to reserve for birthdays, anniversaries, graduations, and holiday gatherings.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Follow us on Facebook and Instagram <strong>@TJsItalianCafe</strong> for the latest events and specials.</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'maps-directions',
        'title'   => 'Maps & Directions',
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2rem,4vw,3rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2rem,4vw,3rem);color:#3a2011;">TJ\'s Italian Cafe Location and Directions</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"1.5rem"},"color":{"text":"#BC0C06"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:1.5rem;color:#BC0C06;">1515 Gulf Boulevard, Indian Rocks Beach, FL 33785</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Located 1 mile South of the Belleair Bridge on Gulf Boulevard in Indian Rocks Beach, Florida.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Phone:</strong> <a href="tel:+17275961515" style="color:#BC0C06;">727-596-1515</a></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="margin:40px 0;">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3529.2!2d-82.8417!3d27.9118!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2ede2e1234567%3A0x1234567890abcdef!2sTJ&#39;s%20Italian%20Cafe%2C%201515%20Gulf%20Blvd%2C%20Indian%20Rocks%20Beach%2C%20FL%2033785!5e0!3m2!1sen!2sus!4v1234567890" width="100%" height="450" style="border:0;border:1px solid #d6ad8a;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="TJs Italian Cafe Map Directions"></iframe>
</div>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"1.8rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:1.8rem;color:#3a2011;margin-top:40px;">Hours</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Monday &#8211; Wednesday:</strong> 3:00pm &#8211; 10:00pm<br/><strong>Thursday &#8211; Sunday:</strong> 12:00pm &#8211; 10:00pm<br/><strong>Brunch Saturday &#8211; Sunday:</strong> 10:00am &#8211; 2:00pm<br/><strong>Takeout Daily:</strong> 3:00pm &#8211; Close</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->',
    ],
    [
        'slug'    => 'brunch',
        'title'   => 'Brunch',
        'template' => '',
        'content' => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding-top:80px;padding-bottom:80px;">

<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"clamp(2rem,4vw,3rem)"},"color":{"text":"#3a2011"}}} -->
<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:clamp(2rem,4vw,3rem);color:#3a2011;">Saturday &amp; Sunday Brunch</h2>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","align":"wide"} -->
<figure class="wp-block-image size-full alignwide"><img src="https://tjsitaliancafe.com/wp-content/uploads/2025/03/capturing-friendship-moments-on-smartphone-2025-02-24-19-00-32-utc.jpg" alt="TJs Italian Cafe Brunch" style="width:100%;aspect-ratio:16/6;object-fit:cover;"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem","lineHeight":"1.8"}}} -->
<p style="font-size:1.1rem;line-height:1.8;margin-top:40px;">Join us every Saturday and Sunday for brunch on the Gulf! Chef TJ brings the same Italian mastery to the morning table — start your weekend the right way at Indian Rocks Beach&#8217;s premier dining destination.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"color":{"background":"#fdf8f3"},"spacing":{"padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"},"margin":{"top":"40px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="background-color:#fdf8f3;padding:32px;margin-top:40px;text-align:center;">
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var(--wp--preset--font-family--display)","fontSize":"1.8rem"},"color":{"text":"#3a2011"}}} -->
<h3 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--display);font-size:1.8rem;color:#3a2011;">Brunch Hours</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.2rem","fontWeight":"700"}}} -->
<p style="font-size:1.2rem;font-weight:700;">Saturday &amp; Sunday: 10:00am &#8211; 2:00pm</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Reservations: <a href="tel:+17275961515" style="color:#BC0C06;font-weight:700;">727-596-1515</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->',
    ],
];

// ---------------------------------------------------------------
// 4. Create / Update Pages
// ---------------------------------------------------------------
foreach ($pages as $page_data) {
    $slug  = $page_data['slug'];
    $title = $page_data['title'];

    // Check if page exists
    $existing = null;
    if ($slug === '') {
        // home page — find by 'front-page' or sample-page
        $existing = get_page_by_path('home');
        if (!$existing) {
            // look for any page with home template or first page
            $pages_query = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => 20]);
            foreach ($pages_query as $p) {
                if (strtolower($p->post_title) === 'home' || strtolower($p->post_title) === 'sample page') {
                    $existing = $p;
                    break;
                }
            }
        }
    } else {
        $existing = get_page_by_path($slug);
    }

    $post_args = [
        'post_title'   => $title,
        'post_content' => $page_data['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_name'    => $slug ?: 'home',
    ];
    // Only set page_template if explicitly specified (not empty) to avoid "Invalid page template" errors
    // For FSE themes: front-page.html is used via template hierarchy, no page_template needed
    if (!empty($page_data['template'])) {
        $post_args['page_template'] = $page_data['template'] . '.html';
    }

    if ($existing) {
        $page_id = $existing->ID;
        if ($slug === '') {
            // Home page: wp_update_post rejects update if the page has a stale non-existent template in postmeta.
            // BYPASS: clear the template postmeta via $wpdb FIRST, then use $wpdb directly to update content.
            global $wpdb;
            // Clear stale _wp_page_template (force to 'default')
            $wpdb->delete($wpdb->postmeta, ['post_id' => $page_id, 'meta_key' => '_wp_page_template'], ['%d', '%s']);
            $wpdb->insert($wpdb->postmeta, ['post_id' => $page_id, 'meta_key' => '_wp_page_template', 'meta_value' => 'default'], ['%d', '%s', '%s']);
            // Update post content and title directly via $wpdb
            $updated = $wpdb->update(
                $wpdb->posts,
                [
                    'post_title'   => $title,
                    'post_content' => $page_data['content'],
                    'post_status'  => 'publish',
                    'post_modified' => current_time('mysql'),
                    'post_modified_gmt' => current_time('mysql', true),
                ],
                ['ID' => $page_id],
                ['%s', '%s', '%s', '%s', '%s'],
                ['%d']
            );
            if ($updated !== false) {
                wp_cache_delete($page_id, 'posts');
                clean_post_cache($page_id);
                log_msg("Updated home page via direct DB (ID: $page_id). Template cleared to 'default'.");
            } else {
                log_err("Direct DB update failed for home page ID $page_id: " . $wpdb->last_error);
            }
        } else {
            $post_args['ID'] = $page_id;
            $result = wp_update_post($post_args, true);
            if (is_wp_error($result)) {
                log_err("Failed to update page '{$title}': " . $result->get_error_message());
            } else {
                log_msg("Updated page: {$title} (ID: {$result})");
            }
        }
    } else {
        $result = wp_insert_post($post_args, true);
        if (is_wp_error($result)) {
            log_err("Failed to create page '{$title}': " . $result->get_error_message());
            continue;
        }
        $page_id = $result;
        log_msg("Created page: {$title} (ID: {$page_id})");
    }

    // Set home page
    if ($slug === '') {
        update_option('page_on_front', $page_id);
        update_option('show_on_front', 'page');
        // CRITICAL: Use $wpdb to bypass WP template validation and directly clear the page_template postmeta
        // WP FSE uses front-page.html via template hierarchy when _wp_page_template is 'default'
        global $wpdb;
        $existing_tpl = $wpdb->get_var($wpdb->prepare(
            "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_wp_page_template'",
            $page_id
        ));
        if ($existing_tpl) {
            $wpdb->update(
                $wpdb->postmeta,
                ['meta_value' => 'default'],
                ['post_id' => $page_id, 'meta_key' => '_wp_page_template'],
                ['%s'],
                ['%d', '%s']
            );
            log_msg("Cleared page_template override from '{$existing_tpl}' to 'default' for page ID {$page_id}.");
        } else {
            $wpdb->insert(
                $wpdb->postmeta,
                ['post_id' => $page_id, 'meta_key' => '_wp_page_template', 'meta_value' => 'default'],
                ['%d', '%s', '%s']
            );
            log_msg("Set page_template to 'default' for page ID {$page_id}.");
        }
        log_msg("Set page ID {$page_id} as front page. FSE will use front-page.html via template hierarchy.");
    }
}

// ---------------------------------------------------------------
// 5. Set up Navigation Menu
// ---------------------------------------------------------------
$menu_name = 'Primary Navigation';
$menu_id   = 0;

// Check if menu exists
$menus = get_terms(['taxonomy' => 'nav_menu', 'hide_empty' => false]);
foreach ($menus as $m) {
    if ($m->name === $menu_name) {
        $menu_id = $m->term_id;
        break;
    }
}

if (!$menu_id) {
    $menu_id = wp_create_nav_menu($menu_name);
    log_msg("Created nav menu: {$menu_name} (ID: {$menu_id})");
} else {
    log_msg("Found existing nav menu: {$menu_name} (ID: {$menu_id})");
}

// Clear existing items
$existing_items = wp_get_nav_menu_items($menu_id);
if ($existing_items) {
    foreach ($existing_items as $item) {
        wp_delete_post($item->ID, true);
    }
}

// Add menu items
$menu_items = [
    ['title' => 'Home',        'url' => home_url('/'),               'order' => 1],
    ['title' => 'About Us',    'url' => home_url('/about-us/'),      'order' => 2],
    ['title' => 'Menu',        'url' => home_url('/menu/'),          'order' => 3],
    ['title' => 'Wine',        'url' => home_url('/wine/'),          'order' => 4],
    ['title' => 'Brunch',      'url' => home_url('/brunch/'),        'order' => 5],
    ['title' => 'Gallery',     'url' => home_url('/gallery/'),       'order' => 6],
    ['title' => 'Events',      'url' => home_url('/local-events/'),  'order' => 7],
    ['title' => 'Directions',  'url' => home_url('/maps-directions/'), 'order' => 8],
    ['title' => 'Contact',     'url' => home_url('/contact-us/'),    'order' => 9],
];

foreach ($menu_items as $item) {
    $result = wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'   => $item['title'],
        'menu-item-url'     => $item['url'],
        'menu-item-status'  => 'publish',
        'menu-item-type'    => 'custom',
        'menu-item-position' => $item['order'],
    ]);
    if (is_wp_error($result)) {
        log_err("Nav menu item '{$item['title']}': " . $result->get_error_message());
    } else {
        log_msg("Added nav item: {$item['title']}");
    }
}

// Assign menu to primary location
set_theme_mod('nav_menu_locations', ['primary' => $menu_id, 'footer' => $menu_id]);
log_msg('Nav menu assigned to primary + footer locations.');

// ---------------------------------------------------------------
// 6. WP Block navigation setup
// ---------------------------------------------------------------
// Create a navigation block post for the FSE theme
$nav_post = get_posts([
    'post_type'      => 'wp_navigation',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
]);

$nav_content = '<!-- wp:navigation-link {"label":"Home","url":"' . home_url('/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"About Us","url":"' . home_url('/about-us/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Menu","url":"' . home_url('/menu/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Wine","url":"' . home_url('/wine/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Brunch","url":"' . home_url('/brunch/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Gallery","url":"' . home_url('/gallery/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Events","url":"' . home_url('/local-events/') . '","kind":"custom","isTopLevelLink":true} /-->
<!-- wp:navigation-link {"label":"Contact","url":"' . home_url('/contact-us/') . '","kind":"custom","isTopLevelLink":true} /-->';

if ($nav_post) {
    wp_update_post([
        'ID'           => $nav_post[0]->ID,
        'post_content' => $nav_content,
        'post_title'   => 'Primary Navigation',
        'post_status'  => 'publish',
    ]);
    log_msg('Updated FSE navigation post.');
} else {
    $nav_id = wp_insert_post([
        'post_type'    => 'wp_navigation',
        'post_title'   => 'Primary Navigation',
        'post_content' => $nav_content,
        'post_status'  => 'publish',
    ]);
    log_msg("Created FSE navigation post: ID {$nav_id}");
}

// ---------------------------------------------------------------
// 7. Flush Rewrite Rules
// ---------------------------------------------------------------
flush_rewrite_rules(true);
log_msg('Flushed rewrite rules.');

// ---------------------------------------------------------------
// 8. Disable default WordPress comment features
// ---------------------------------------------------------------
update_option('default_comment_status', 'closed');
update_option('default_ping_status', 'closed');
log_msg('Comments disabled.');

// ---------------------------------------------------------------
// 9. Output Results
// ---------------------------------------------------------------
?>
<!DOCTYPE html>
<html>
<head>
<title>TJ's Italian Cafe — Import Results</title>
<style>
body { font-family: monospace; background: #1a0d06; color: #d6ad8a; padding: 32px; }
h1 { color: #BC0C06; font-size: 1.5rem; margin-bottom: 24px; }
h2 { color: #d6ad8a; font-size: 1rem; margin: 24px 0 8px; text-transform: uppercase; letter-spacing: 2px; }
.log { background: rgba(255,255,255,0.05); padding: 16px; border-left: 3px solid #3a2011; margin-bottom: 4px; font-size: 0.85rem; }
.error { border-left-color: #BC0C06; color: #ff6b6b; }
.success { border-left-color: #4caf50; }
a { color: #BC0C06; }
.done-box { background: rgba(76,175,80,0.1); border: 1px solid #4caf50; padding: 24px; margin-top: 32px; border-radius: 4px; }
</style>
</head>
<body>
<h1>TJ's Italian Cafe — Content Import</h1>

<h2>Log</h2>
<?php foreach ($log as $entry): ?>
<div class="log success"><?php echo htmlspecialchars($entry); ?></div>
<?php endforeach; ?>

<?php if ($errors): ?>
<h2>Errors</h2>
<?php foreach ($errors as $err): ?>
<div class="log error"><?php echo htmlspecialchars($err); ?></div>
<?php endforeach; ?>
<?php endif; ?>

<div class="done-box">
<strong>Import complete.</strong><br/>
Logs: <?php echo count($log); ?> items, <?php echo count($errors); ?> errors.<br/><br/>
<a href="<?php echo home_url('/'); ?>" style="color:#BC0C06;font-weight:bold;">View Site →</a>
&nbsp;|&nbsp;
<a href="<?php echo admin_url(); ?>" style="color:#BC0C06;">WP Admin →</a><br/><br/>
<em>Delete this file after setup is confirmed: <code>/var/www/html/content-import.php</code></em>
</div>
</body>
</html>
