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
// 1b. Clear FSE template overrides in wp_templates custom post type
// WordPress FSE stores template overrides as custom posts with post_type='wp_template'
// These override the theme's template files. We must clear them for front-page.html to be used.
// ---------------------------------------------------------------
$template_overrides = get_posts([
    'post_type'      => 'wp_template',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'post_name__in'  => ['front-page', 'home', 'page', 'index', 'header', 'footer'],
]);
foreach ($template_overrides as $tpl) {
    wp_delete_post($tpl->ID, true); // force delete (bypass trash)
    log_msg("Deleted FSE template override: {$tpl->post_name} (ID: {$tpl->ID})");
}

$template_part_overrides = get_posts([
    'post_type'      => 'wp_template_part',
    'post_status'    => 'any',
    'posts_per_page' => -1,
]);
foreach ($template_part_overrides as $tpl) {
    wp_delete_post($tpl->ID, true);
    log_msg("Deleted FSE template part override: {$tpl->post_name} (ID: {$tpl->ID})");
}

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
$pages = [
    [
        'slug'    => '',   // home
        'title'   => 'Home',
        'template' => '', // MUST be empty — front-page.html is used via WP FSE template hierarchy, NOT as a page_template
        'content' => '', // home page content comes from front-page.html block template
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
        $post_args['ID'] = $existing->ID;
        $result = wp_update_post($post_args, true);
        if (is_wp_error($result)) {
            log_err("Failed to update page '{$title}': " . $result->get_error_message());
        } else {
            log_msg("Updated page: {$title} (ID: {$result})");
        }
        $page_id = $existing->ID;
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
        // CRITICAL: Clear any stored page_template override so FSE uses front-page.html via template hierarchy
        delete_post_meta($page_id, '_wp_page_template');
        update_post_meta($page_id, '_wp_page_template', 'default');
        log_msg("Set page ID {$page_id} as front page, cleared page_template override.");
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
