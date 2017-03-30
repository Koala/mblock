<?php
/**
 * @author mail[at]joachim-doerr[dot]com Joachim Doerr
 * @package redaxo5
 * @license MIT
 */

if (rex::isBackend() && is_object(rex::getUser())) {

    // check theme css is exists
    MBlockThemeHelper::themeBootCheck($this->getConfig('mblock_theme'));

    // use theme helper class
    if (MBlockThemeHelper::getCssAssets($this->getConfig('mblock_theme'))) {
        // foreach all css files
        foreach (MBlockThemeHelper::getCssAssets($this->getConfig('mblock_theme')) as $css) {
            // add assets css file
            rex_view::addCssFile($this->getAssetsUrl($css));
        }
    }

    // register extensions
    // pre pre post
    rex_extension::register('REX_FORM_GET', function (rex_extension_point $params) {
        /** @var rex_form $form */
        $form = $params->getSubject();
        if ($form instanceof mblock_rex_form)
            MBlockRexFormProcessor::prePostSaveAction($form, $_POST); // execute pre post
    });

    // alfred post post
    rex_extension::register('REX_FORM_SAVED', function (rex_extension_point $params) {
        /** @var rex_form|null $form */
        $form = ($params->hasParam('form')) ? $params->getParam('form') : null;
        if ($form instanceof mblock_rex_form)
            return MBlockRexFormProcessor::postPostSaveAction($params->getSubject(), $form, $_POST); // execute post post
        else
            return $params->getSubject();
    });

    // assets
    rex_view::addJsFile($this->getAssetsUrl('mblock_sortable.min.js?v=' . $this->getVersion()));
    rex_view::addJsFile($this->getAssetsUrl('mblock_smooth_scroll.min.js?v=' . $this->getVersion()));
    rex_view::addJsFile($this->getAssetsUrl('mblock.js?v=' . $this->getVersion()));
    rex_view::addCssFile($this->getAssetsUrl('mblock.css?v=' . $this->getVersion()));

    // reset mblock page count
    $_SESSION['mblock_count'] = 0;
}