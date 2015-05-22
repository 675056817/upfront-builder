<?php
	$themes = array();
	$fallback_screenshot = plugins_url(THX_BASENAME . '/imgs/testImage.jpg');
	$current_theme = get_option('stylesheet');

	foreach(wp_get_themes() as $stylesheet=>$theme) {
		if ($theme->get('Template') !== 'upfront') continue;
		$themes[$stylesheet] = $theme;
	}
?>

<header class="builder">
	<h2><?php esc_html_e('Upfront Builder', UpfrontThemeExporter::DOMAIN); ?></h2>
	<p>
		<?php esc_html_e('Build your own, slick, customizable, intuitive Upfront themes.', UpfrontThemeExporter::DOMAIN); ?>
		<br />
		<?php esc_html_e('Creating a WordPress theme has never been easier.', UpfrontThemeExporter::DOMAIN); ?>
	</p>
</header>

<div class="uf-thx-initial clearfix">
	
	<div class="uf-thx-existing_theme uf-thx-pane">
	<?php if (!empty($themes)) { ?>
		<h3><?php esc_html_e('Modify existing theme:', UpfrontThemeExporter::DOMAIN); ?></h3>
		
		<label class="inline"><span class="description"><?php esc_html_e('Select a theme to modify:', UpfrontThemeExporter::DOMAIN); ?></span></label>
		<div class="uf-thx-themes_container clearfix">
		<?php foreach ($themes as $key => $theme) { ?>
			<div class="uf-thx-theme <?php 
			if (!empty($_GET['theme']) && $theme->get_stylesheet() === $_GET['theme']) echo 'selected'; 
		?> <?php
			if ($theme->get_stylesheet() === $current_theme) {
				echo 'current'; 
				if (empty($_GET['theme'])) echo ' selected';
			}
		?>" data-theme="<?php echo esc_attr($theme->get_stylesheet()); ?>">
				<a href="?theme=<?php echo esc_attr($theme->get_stylesheet()); ?>">
					<?php $screenshot = $theme->get_screenshot() ? $theme->get_screenshot() : $fallback_screenshot; ?>
					<img src="<?php echo esc_url($screenshot); ?>" />
					<div class="uf-thx-caption">
						<b><?php echo esc_html($theme->get('Name')); ?></b>
					</div>
				</a>
			</div>
		<?php } ?>
		</div>
		
		<button type="button" class="edit theme">
			<?php esc_html_e('Edit theme', UpfrontThemeExporter::DOMAIN); ?>
		</button>
		<button type="button" class="edit info">
			<?php esc_html_e('Edit theme details', UpfrontThemeExporter::DOMAIN); ?>
		</button>
	<?php } else { ?>
		<label class="inline"><span class="description">
			<?php esc_html_e('No existing themes, please create a new one.', UpfrontThemeExporter::DOMAIN); ?>
		</span></label>
	<?php } ?>
	</div>

	<div class="uf-thx-new_theme uf-thx-pane">
	<?php if (empty($_GET['theme'])) { ?>
		<h3><?php esc_html_e('Build a new theme:', UpfrontThemeExporter::DOMAIN); ?></h3>

		<?php Thx_Template::plugin()->load('theme_form'); ?>

		<button type="button" class="create theme">
			<?php esc_html_e('Start building', UpfrontThemeExporter::DOMAIN); ?>
		</button>
	<?php } else { ?>
		<?php $theme = wp_get_theme($_GET['theme']); ?>
		<h3><?php echo esc_html(sprintf(__('Edit &quot;%s&quot;:', UpfrontThemeExporter::DOMAIN), $theme->get('Name'))); ?></h3>

		<?php 
			Thx_Template::plugin()->load('theme_form', array(
				'name' => $theme->get('Name'),
				'slug' => $theme->get_stylesheet(),
				'author' => $theme->get('Author'),
				'author_uri' => $theme->get('AuthorURI'),
				'description' => $theme->get('Description'),
				'version' => $theme->get('Version'),
				'theme_uri' => $theme->get('ThemeURI'),
				'licence' => $theme->get('Licence'),
				'licence_uri' => $theme->get('LicenceURI'),
				'tags' => $theme->get('Tags'),
				'text_domain' => $theme->get('TextDomain'),
				'screenshot' => ($theme->get_screenshot() ? $theme->get_screenshot() : $fallback_screenshot)
			)); 
		?>

		<button type="button" class="edit info">
			<?php esc_html_e('Edit info', UpfrontThemeExporter::DOMAIN); ?>
		</button>
	<?php } ?>
	</div>

</div>