<?php
/**
 * @file
 * SimpleAds Flash ad.
 * come disabilitare flash in chrome:
  nella barra dell'indirizzo scrivere:
  about:plugins
  dopodiché basta cliccare la dicitura
  Adobe Flash Player disabilita..
  all'inverso abilitarlo..
 */
$width = !empty($settings['ads_width']) ? check_plain($settings['ads_width']) : '120';
$height = !empty($settings['ads_height']) ? check_plain($settings['ads_height']) : '120';
///// $height = $height - 10;

$alternate = NULL;
if (isset($ad['image'])) {
    $alternate = $ad['image'];
}

if (!isset($ad['url'])) {
    $ad['url']='#';
}
/// $ad['node'->title];
///var_export($ad);
/// die();
?>
<?php if ($ad['flash']) : ?>
    <div class="simplead-container flash-ad <?php
    if (isset($css_attributes)): print $css_attributes;
    endif;
    ?>">


        <?php if ($settings['fla'] == 1) : ?>
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="<?php print $width; ?>" height="<?php print $height; ?>" id="ad-<?php print $ad['nid']; ?>">
                <param name="movie" value="<?php print $ad['flash']; ?>" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#ffffff" />
                <param name="wmode" value="transparent" />
                <?php if ($ad['url']) : ?>
                    <?php // Passing URL to redirect ?>
                    <param value="clickTAG=<?php print url($ad['url']); ?>" name="flashvars" />
                <?php endif; ?>
                <embed src="<?php print $ad['flash']; ?>" quality="high" bgcolor="#ffffff" width="<?php print $width; ?>" height="<?php print $height; ?>" name="ad-<?php print $ad['nid']; ?>" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
            </object>
        <?php endif; ?>

        <?php if ($settings['fla'] == 0) : ?>
        <a href="<?php print url($ad['url']); ?>" target="_blank" >
        <img class="image_banner_alternate" title="<?php print $ad['alt']; ?>  <?php print $ad['node']->title; ?>" src="<?php print $alternate; ?>" width="<?php print $width; ?>" height="<?php print $height; ?>" style="width:<?php print $width; ?>px; height:<?php print $height; ?>px;" typeof="foaf:Image" />
        </a>
        <!--  <p>No flash support  width=<?php print $width; ?> height=<?php print $height; ?>. <?php print $alternate; ?></p>--> 
        <?php endif; ?>
        <!--  support flash <?php print $settings['fla'] ?> 
        (<?php print $settings['ads_order'] ?>)  
        (<?php print $ad['url'] ?>)  
        <?php ///// print theme('image', $image_attributes);  ?>
        (<?php print $settings['block_info'] ?>)  
        
        -->
    </div>

<?php endif; ?>