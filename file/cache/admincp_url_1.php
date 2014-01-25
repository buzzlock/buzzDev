<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = array (
  'admincp.dashboard' => 'admincp',
  'admincp.cms' => 
  array (
    'admincp.menus' => 
    array (
      'admincp.manage_menus' => 'admincp.menu',
      'admincp.add_new_menu' => 'admincp.menu.add',
    ),
    'admincp.blocks' => 
    array (
      'admincp.manage_blocks' => 'admincp.block',
      'admincp.add_new_block' => 'admincp.block.add',
    ),
    'admincp.pages' => 
    array (
      'admincp.manage_pages' => 'admincp.page',
      'admincp.add_new_page' => 'admincp.page.add',
    ),
  ),
  'admincp.users' => 
  array (
    'admincp.browse_members' => 'admincp.user.browse',
    'admincp.user_group_manager' => 
    array (
      'admincp.manage_user_groups' => 'admincp.user.group',
      'admincp.create_user_group' => 'admincp.user.group.add',
      'admincp.add_user_group_setting' => 'admincp.user.group.setting',
    ),
    'admincp.user_cancellation_options' => 
    array (
      'admincp.user_cancellation_options_add' => 'admincp.user.cancellations.add',
      'admincp.user_cancellation_options_manage' => 'admincp.user.cancellations.manage',
      'admincp.user_cancellations_feedback' => 'admincp.user.cancellations.feedback',
    ),
    'user.promotions' => 
    array (
      'user.manage_promotions' => 'admincp.user.promotion',
      'user.add_promotion' => 'admincp.user.promotion.add',
    ),
    'admincp.inactive_members' => 'admincp.user.inactivereminder',
    'custom.admincp_custom_fields' => 
    array (
      'custom.admin_menu_add_custom_field' => 'admincp.custom.add',
      'custom.admin_menu_manage_custom_fields' => 'admincp.custom',
      'custom.admin_menu_add_custom_group' => 'admincp.custom.group.add',
      'custom.admin_menu_manage_relationships' => 'admincp.custom.relationships',
    ),
  ),
  'admincp.extensions' => 
  array (
    'admincp.module' => 
    array (
      'admincp.manage_modules' => 'admincp.module',
      'admincp.create_new_module' => 'admincp.module.add',
      'admincp.add_component' => 'admincp.component.add',
      'admincp.manage_components' => 'admincp.component',
    ),
    'admincp.language' => 
    array (
      'admincp.manage_language_packs' => 'admincp.language',
      'admincp.phrase_manager' => 'admincp.language.phrase',
      'admincp.add_phrase' => 'admincp.language.phrase.add',
      'language.create_language_pack' => 'admincp.language.add',
      'language.import_language_pack' => 'admincp.language.import',
      'language.email_phrases' => 'admincp.language.email',
    ),
    'admincp.products' => 
    array (
      'admincp.manage_products' => 'admincp.product',
      'admincp.create_new_product' => 'admincp.product.add',
      'admincp.import_export' => 'admincp.product.file',
    ),
    'admincp.plugin' => 
    array (
      'admincp.manage_plugins' => 'admincp.plugin',
      'admincp.create_new_plugin' => 'admincp.plugin.add',
    ),
    'admincp.theme' => 
    array (
      'admincp.manage_themes' => 'admincp.theme',
      'theme.admincp_menu_create_theme' => 'admincp.theme.add',
      'theme.admincp_menu_create_style' => 'admincp.theme.style.add',
      'theme.create_a_new_template' => 'admincp.theme.template.add',
      'theme.admincp_create_css_file' => 'admincp.theme.style.css.add',
      'theme.admincp_menu_import_themes' => 'admincp.theme.import',
      'theme.admincp_menu_import_styles' => 'admincp.theme.style.import',
    ),
    'apps.admincp_menu_apps' => 
    array (
      'apps.categories' => 'admincp.apps.categories',
      'apps.install_app' => 'admincp.apps.import',
      'apps.export_apps' => 'admincp.apps.export',
    ),
    'emoticon.emoticons' => 
    array (
      'admincp.menu_tools_emoticon_package' => 'admincp.emoticon.package',
      'admincp.menu_tools_emoticon_package_add' => 'admincp.emoticon.package.add',
      'admincp.menu_tools_emoticon_add' => 'admincp.emoticon.add',
      'emoticon.import_export_emoticon' => 'admincp.emoticon.file',
    ),
  ),
  'admincp.settings' => 
  array (
    'admincp.system_settings_menu' => 
    array (
      'admincp.manage_settings' => 'admincp.setting',
      'admincp.add_new_setting' => 'admincp.setting.add',
      'admincp.add_new_setting_group' => 'admincp.setting.group.add',
      'admincp.find_missing_settings' => 'admincp.setting.missing',
    ),
    'admincp.payment_gateways_menu' => 'admincp.api.gateway',
  ),
  'admincp.tools' => 
  array (
    'admincp.general' => 
    array (
      'core.site_statistics' => 'admincp.core.stat',
      'core.admincp_menu_system_overview' => 'admincp.core.system',
      'admincp.ip_address' => 'admincp.core.ip',
      'admincp.admincp_privacy' => 'admincp.privacy',
    ),
    'admincp.menu_site_stats' => 
    array (
      'admincp.menu_manage_stats' => 'admincp.stat',
      'admincp.menu_create_new_stat' => 'admincp.stat.add',
    ),
    'admincp.maintenance' => 
    array (
      'admincp.menu_cache_manager' => 'admincp.maintain.cache',
      'admincp.admincp_menu_reparser' => 'admincp.maintain.reparser',
      'admincp.remove_duplicates' => 'admincp.maintain.duplicate',
      'admincp.counters' => 'admincp.maintain.counter',
    ),
    'ban.ban_filters' => 
    array (
      'ban.ban_filter_username' => 'admincp.ban.username',
      'ban.ban_filter_email' => 'admincp.ban.email',
      'ban.ban_filter_display_name' => 'admincp.ban.display',
      'ban.ban_filter_ip' => 'admincp.ban.ip',
      'ban.ban_filter_word' => 'admincp.ban.word',
    ),
    'admincp.mail_messages' => 
    array (
      'admincp.view_messages' => 'admincp.mail.private',
    ),
    'core.admincp_menu_country' => 
    array (
      'core.admincp_menu_country_manager' => 'admincp.core.country',
      'core.admincp_menu_country_add' => 'admincp.core.country.add',
      'core.admincp_menu_country_child_add' => 'admincp.core.country.child.add',
      'core.admincp_menu_country_import' => 'admincp.core.country.import',
    ),
    'core.admincp_menu_online' => 
    array (
      'core.admincp_menu_online_members' => 'admincp.user.browse.view_online',
      'core.admincp_menu_online_guests' => 'admincp.core.online-guest',
    ),
    'admincp.sql' => 
    array (
      'admincp.sql_maintenance' => 'admincp.sql',
      'admincp.sql_backup' => 'admincp.sql.backup',
      'admincp.alter_title_fields' => 'admincp.sql.title',
    ),
    'core.currency' => 
    array (
      'core.currency_manager' => 'admincp.core.currency',
      'core.add_currency' => 'admincp.core.currency.add',
    ),
    'admincp.seo' => 
    array (
      'admincp.custom_elements' => 'admincp.seo.meta',
      'admincp.nofollow_urls' => 'admincp.seo.nofollow',
      'admincp.rewrite_url' => 'admincp.seo.rewrite',
    ),
  ),
); ?>