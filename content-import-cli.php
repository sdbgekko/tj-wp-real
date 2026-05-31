<?php
/**
 * TJ's Italian Cafe — WP-CLI Content Import
 * Run via: wp eval-file content-import-cli.php --allow-root
 * v2: full rich page content, reset via delete_option
 */

// Always re-run (delete stale flag first)
delete_option('tj_cafe_setup_complete');
WP_CLI::log('TJ setup: starting fresh...');

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
// Pages — full content blocks
// ---------------------------------------------------------------

$about_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"960px"}} -->
<div class="wp-block-group" style="padding:60px 24px;">
<!-- wp:columns {"style":{"spacing":{"blockGap":"48px"}}} -->
<div class="wp-block-columns" style="gap:48px;align-items:flex-start;">
<!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%;">
<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:24px;">About TJ\'s Italian Cafe</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p style="font-size:1rem;line-height:1.8;color:#444;">When visitors arrive, the tables are full and there is a line out the door. TJ\'s, an Indian Rocks Beach Italian Restaurant, features hearty Italian-American cuisine with a tropical flair to select dishes. Chef TJ\'s dishes are carefully crafted, and prepared the classic Italian way — everything is made from scratch. Whether it\'s the juicy hand-rolled 4-ounce meatballs, the brick oven-baked bread and pizzas, or the homemade sauces and soups, Chef TJ\'s menu is comprised of classic Italian dishes that will blow your taste buds away! Now with an extensive Beer, Wine and Cocktail list.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p style="font-size:1rem;line-height:1.8;color:#444;">Beachy and casual, yet elegant and tasteful, you can dine inside or enjoy a cool gulf breeze from the palm-laden deck outdoors. Owners Thomas John Smith (also known as Chef TJ) and Kim Grady-Smith are warm and inviting hosts, and Chef TJ lives up to his reputation as one of the best chefs on the Gulf Beaches, personally overseeing every dish that leaves his kitchen.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p style="font-size:1rem;line-height:1.8;color:#444;">Since Chef TJ has expanded the menu, creating classic Italian dishes and tropical local seafood, one of the local favorites is the eggplant rollatini — stuffed and rolled with spinach, onion, and ricotta, smothered in an unbelievable homemade red sauce then baked to perfection. Another favorite is the Gamberoni Basilico, peppercorn seared shrimp nestled in the creamiest pesto you\'ve ever tasted.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p style="font-size:1rem;line-height:1.8;color:#444;">The most popular entrée on the menu is the Aged Filet Mignonette — Black Angus beef, pan-seared with Chef TJ\'s secret recipe and baked to seal in the juicy goodness, leaving meat so tender you can cut it with a butter knife, topped off with a juicy lobster tail.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p style="font-size:1rem;line-height:1.8;color:#444;">Chef TJ offers a superb wine list, hand-picked himself with some help from his locals. You will find your favorite pairing from champagne, imported and domestic wines, and beer will satisfy even the most insatiable palate.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%;">
<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large">
<img src="https://tjsitaliancafe.com/wp-content/uploads/2017/12/tjfamily.jpg" alt="TJ\'s Italian Cafe Family — Chef TJ Smith and Kim Grady-Smith" style="width:100%;height:auto;"/>
</figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"spacing":{"margin":{"top":"24px"}}}} -->
<figure class="wp-block-image size-large" style="margin-top:24px;">
<img src="https://tjsitaliancafe.com/wp-content/uploads/2018/03/restaurant-love-romantic-dinner.jpg" alt="Romantic dinner at TJ\'s Italian Cafe" style="width:100%;height:auto;"/>
</figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->';

$menu_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"40px"}}},"layout":{"type":"constrained","contentSize":"960px"}} -->
<div class="wp-block-group" style="padding:60px 24px 40px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:8px;">TJ\'s Italian Cafe Menu</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1rem"},"color":{"text":"#615b53"},"spacing":{"margin":{"bottom":"40px"}}}} -->
<p class="has-text-align-center" style="font-size:1rem;color:#615b53;margin-bottom:40px;">Seafood · Pasta · Pizza · Since 1989 · Call 727-596-1515 for takeout orders</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"32px"}}}} -->
<p class="has-text-align-center" style="margin-bottom:32px;"><a href="https://tjsitaliancafe.com/wp-content/uploads/2026/04/TJs-Menu-April-2026.pdf" target="_blank" style="display:inline-block;background-color:#BC0C06;color:#fff;padding:14px 32px;font-weight:700;text-transform:uppercase;letter-spacing:2px;font-size:0.85rem;text-decoration:none;">Download / View Current Menu (PDF)</a></p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<div style="width:100%;height:80vh;min-height:500px;border:1px solid #ddd;">
<iframe src="https://tjsitaliancafe.com/wp-content/uploads/2026/04/TJs-Menu-April-2026.pdf" width="100%" height="100%" style="border:none;" title="TJ\'s Italian Cafe Menu April 2026"></iframe>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->';

$wine_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"40px"}}},"layout":{"type":"constrained","contentSize":"960px"}} -->
<div class="wp-block-group" style="padding:60px 24px 40px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:8px;">Wines &amp; Cocktails</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1rem"},"color":{"text":"#615b53"},"spacing":{"margin":{"bottom":"40px"}}}} -->
<p class="has-text-align-center" style="font-size:1rem;color:#615b53;margin-bottom:40px;">Chef TJ\'s hand-picked wine list spans champagne, imported and domestic wines, plus a full cocktail bar worthy of the Gulf sunset.</p>
<!-- /wp:paragraph -->
<!-- wp:image {"align":"center","sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large" style="margin-bottom:32px;">
<img src="https://tjsitaliancafe.com/wp-content/uploads/2018/09/alcoholic-beverage-beverage-blur-1123260.jpg" alt="Wines and cocktails at TJ\'s Italian Cafe" style="max-width:800px;width:100%;height:auto;"/>
</figure>
<!-- /wp:image -->
<!-- wp:paragraph {"textAlign":"center"} -->
<p class="has-text-align-center"><a href="https://tjsitaliancafe.com/wp-content/uploads/2026/04/TJs-Menu-April-2026.pdf" target="_blank" style="display:inline-block;background-color:#BC0C06;color:#fff;padding:14px 32px;font-weight:700;text-transform:uppercase;letter-spacing:2px;font-size:0.85rem;text-decoration:none;">View Wine &amp; Cocktail Menu (PDF)</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->';

$brunch_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding:60px 24px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:8px;">Saturday &amp; Sunday Brunch</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontFamily":"\'Yesteryear\', cursive","fontSize":"2rem"},"color":{"text":"#BC0C06"}}} -->
<p class="has-text-align-center" style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#BC0C06;margin-bottom:24px;">10:00am – 2:00pm</p>
<!-- /wp:paragraph -->
<!-- wp:image {"align":"center","sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large" style="margin-bottom:32px;">
<img src="https://tjsitaliancafe.com/wp-content/uploads/2025/01/group-of-middle-aged-friends-celebrating-in-bar-to-2024-10-19-09-36-54-utc-1.jpg" alt="Brunch at TJ\'s Italian Cafe Indian Rocks Beach" style="max-width:800px;width:100%;height:auto;"/>
</figure>
<!-- /wp:image -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1.1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p class="has-text-align-center" style="font-size:1.1rem;line-height:1.8;color:#444;">Indian Rocks Beach\'s finest brunch on the Gulf. Enjoy a relaxed Saturday or Sunday morning at TJ\'s with our full brunch menu — from eggs benedict and French toast to our signature Italian breakfast dishes, all crafted fresh in Chef TJ\'s kitchen.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textAlign":"center","style":{"spacing":{"margin":{"top":"32px"}}}} -->
<p class="has-text-align-center" style="margin-top:32px;"><a href="https://tjsitaliancafe.com/wp-content/uploads/2026/04/TJs-Menu-April-2026.pdf" target="_blank" style="display:inline-block;background-color:#BC0C06;color:#fff;padding:14px 32px;font-weight:700;text-transform:uppercase;letter-spacing:2px;font-size:0.85rem;text-decoration:none;">View Brunch Menu (PDF)</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->';

$gallery_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group" style="padding:60px 24px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:40px;">Gallery</h2>
<!-- /wp:heading -->
<!-- wp:gallery {"columns":3,"linkTo":"none","sizeSlug":"large"} -->
<figure class="wp-block-gallery has-nested-images columns-3">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2019/01/image_03.png" alt="TJ\'s Italian Cafe Indian Rocks Beach" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2019/01/image_04.png" alt="TJ\'s Italian Cafe dining" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2019/01/image_06.png" alt="TJ\'s Italian Cafe food" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2019/01/image_07.png" alt="TJ\'s Italian Cafe atmosphere" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2018/09/alcoholic-beverage-beverage-blur-1123260.jpg" alt="Cocktails at TJ\'s" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2025/01/group-of-middle-aged-friends-celebrating-in-bar-to-2024-10-19-09-36-54-utc-1.jpg" alt="Friends celebrating at TJ\'s" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2018/09/cheese-cooking-crust-842519.jpg" alt="TJ\'s brick oven pizza" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2017/12/tjfamily.jpg" alt="TJ and Kim at their restaurant" /></figure>
<!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://tjsitaliancafe.com/wp-content/uploads/2018/03/restaurant-love-romantic-dinner.jpg" alt="Romantic dinner at TJ\'s" /></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->
</div>
<!-- /wp:group -->';

$events_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding:60px 24px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:24px;">Local Events</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1.1rem","lineHeight":"1.8"},"color":{"text":"#444"},"spacing":{"margin":{"bottom":"40px"}}}} -->
<p class="has-text-align-center" style="font-size:1.1rem;line-height:1.8;color:#444;margin-bottom:40px;">TJ\'s is the heart of Indian Rocks Beach community celebrations. From Father\'s Day to graduation parties, holiday dinners to milestone events — TJ\'s Italian Cafe has been the gathering place since 1989. Call 727-596-1515 to book your event.</p>
<!-- /wp:paragraph -->
<!-- wp:image {"align":"center","sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large" style="margin-bottom:32px;">
<img src="https://tjsitaliancafe.com/wp-content/uploads/2025/01/group-of-middle-aged-friends-celebrating-in-bar-to-2024-10-19-09-36-54-utc-1.jpg" alt="Events at TJ\'s Italian Cafe Indian Rocks Beach" style="max-width:800px;width:100%;height:auto;"/>
</figure>
<!-- /wp:image -->
<!-- wp:group {"style":{"color":{"background":"#fdf8f3"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}}},"layout":{"type":"constrained","contentSize":"700px"}} -->
<div class="wp-block-group" style="background-color:#fdf8f3;padding:40px;text-align:center;margin-top:40px;">
<!-- wp:heading {"level":3,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"1.6rem","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h3 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:1.6rem;font-weight:400;color:#3a2011;margin-bottom:16px;">Book Your Event</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#444"}}} -->
<p class="has-text-align-center" style="font-size:1rem;line-height:1.8;color:#444;">Call us at <strong><a href="tel:+17275961515" style="color:#BC0C06;">727-596-1515</a></strong> to discuss your next celebration, private dining, or community event at TJ\'s Italian Cafe.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->';

$directions_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"960px"}} -->
<div class="wp-block-group" style="padding:60px 24px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:40px;">Maps &amp; Directions</h2>
<!-- /wp:heading -->
<!-- wp:columns {"style":{"spacing":{"blockGap":"48px"}}} -->
<div class="wp-block-columns" style="gap:48px;">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"\'Yesteryear\', cursive","fontSize":"2rem","fontWeight":"400"},"color":{"text":"#BC0C06"}}} -->
<h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;font-weight:400;color:#BC0C06;margin-bottom:16px;">Location</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem","lineHeight":"2"},"color":{"text":"#3a2011"}}} -->
<p style="font-size:1.1rem;line-height:2;color:#3a2011;"><strong>TJ\'s Italian Cafe</strong><br/>1515 Gulf Boulevard<br/>Indian Rocks Beach, FL 33785<br/>1 mile South of Belleair Bridge</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.2rem","fontWeight":"700"},"color":{"text":"#BC0C06"}}} -->
<p style="font-size:1.2rem;font-weight:700;color:#BC0C06;margin-top:16px;"><a href="tel:+17275961515" style="color:#BC0C06;">Call 727-596-1515</a></p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"\'Yesteryear\', cursive","fontSize":"2rem","fontWeight":"400"},"color":{"text":"#BC0C06"},"spacing":{"margin":{"top":"32px"}}}} -->
<h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;font-weight:400;color:#BC0C06;margin-top:32px;margin-bottom:16px;">Hours</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"2"},"color":{"text":"#444"}}} -->
<p style="font-size:1rem;line-height:2;color:#444;"><strong>Monday – Thursday:</strong> 3pm – 10pm<br/><strong>Friday – Sunday:</strong> 12pm – 10pm<br/><strong>Brunch Sat &amp; Sun:</strong> 10am – 2pm<br/><strong>Takeout Daily:</strong> 3pm – Close</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:html -->
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3525.1!2d-82.8430!3d27.8800!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c2f7c1a2b3d4e5%3A0x1234567890abcdef!2sTJ\'s%20Italian%20Cafe%2C%201515%20Gulf%20Blvd%2C%20Indian%20Rocks%20Beach%2C%20FL%2033785!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" width="100%" height="400" style="border:none;display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="TJ\'s Italian Cafe location on Google Maps"></iframe>
<!-- /wp:html -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->';

$contact_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group" style="padding:60px 24px;">
<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontFamily":"\'Times New Roman\', Times, serif","fontSize":"clamp(1.8rem,3vw,2.5rem)","fontWeight":"400"},"color":{"text":"#3a2011"}}} -->
<h2 class="has-text-align-center" style="font-family:\'Times New Roman\',Times,serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:400;color:#3a2011;margin-bottom:40px;">Contact Us</h2>
<!-- /wp:heading -->
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":"48px"}}} -->
<div class="wp-block-columns alignwide" style="gap:48px;">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"\'Yesteryear\',cursive","fontSize":"2rem"},"color":{"text":"#BC0C06"}}} -->
<h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#BC0C06;margin-bottom:16px;">Address</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1rem","lineHeight":"2"},"color":{"text":"#3a2011"}}} -->
<p class="has-text-align-center" style="font-size:1rem;line-height:2;color:#3a2011;">1515 Gulf Blvd<br/>Indian Rocks Beach, FL 33785<br/>1 mile South of Belleair Bridge</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"\'Yesteryear\',cursive","fontSize":"2rem"},"color":{"text":"#BC0C06"}}} -->
<h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#BC0C06;margin-bottom:16px;">Phone</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"1.4rem","fontWeight":"700"},"color":{"text":"#3a2011"}}} -->
<p class="has-text-align-center" style="font-size:1.4rem;font-weight:700;color:#3a2011;"><a href="tel:+17275961515" style="color:#BC0C06;">727-596-1515</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"\'Yesteryear\',cursive","fontSize":"2rem"},"color":{"text":"#BC0C06"}}} -->
<h3 style="font-family:\'Yesteryear\',cursive;font-size:2rem;color:#BC0C06;margin-bottom:16px;">Hours</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"0.95rem","lineHeight":"2"},"color":{"text":"#444"}}} -->
<p class="has-text-align-center" style="font-size:0.95rem;line-height:2;color:#444;"><strong>Mon–Thu:</strong> 3pm – 10pm<br/><strong>Fri–Sun:</strong> 12pm – 10pm<br/><strong>Brunch Sat–Sun:</strong> 10am – 2pm</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
<!-- wp:paragraph {"textAlign":"center","style":{"spacing":{"margin":{"top":"48px"}}}} -->
<p class="has-text-align-center" style="margin-top:48px;font-size:1rem;color:#444;">Follow us on <a href="https://www.facebook.com/tjsitaliancafe/" target="_blank" style="color:#BC0C06;">Facebook</a> and <a href="https://www.instagram.com/tjsitaliancafeirb/" target="_blank" style="color:#BC0C06;">Instagram @tjsitaliancafeirb</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->';

$pages = [
    [
        'slug'     => 'home',
        'title'    => 'Home',
        'template' => 'front-page',
        'content'  => '<!-- wp:paragraph --><p>Welcome to TJ\'s Italian Cafe — Indian Rocks Beach, FL. Est. 1989.</p><!-- /wp:paragraph -->',
    ],
    ['slug' => 'about-us',       'title' => "About TJ's Italian Cafe",     'template' => '', 'content' => $about_content],
    ['slug' => 'menu',           'title' => "TJ's Italian Cafe Menu",       'template' => '', 'content' => $menu_content],
    ['slug' => 'wine',           'title' => 'Wines & Cocktails',            'template' => '', 'content' => $wine_content],
    ['slug' => 'brunch',         'title' => 'Brunch',                       'template' => '', 'content' => $brunch_content],
    ['slug' => 'gallery',        'title' => 'Gallery',                      'template' => '', 'content' => $gallery_content],
    ['slug' => 'local-events',   'title' => 'Local Events',                 'template' => '', 'content' => $events_content],
    ['slug' => 'maps-directions','title' => 'Maps & Directions',            'template' => '', 'content' => $directions_content],
    ['slug' => 'contact-us',     'title' => "Contact TJ's Italian Cafe",    'template' => '', 'content' => $contact_content],
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
    if (!empty($p['template'])) {
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

set_theme_mod('nav_menu_locations', ['primary' => $menu_id, 'footer' => $menu_id]);
WP_CLI::log("Nav menu configured: {$menu_id}");

// Mark setup complete
update_option('tj_cafe_setup_complete', true);
WP_CLI::success('TJ Italian Cafe setup v2 complete!');
