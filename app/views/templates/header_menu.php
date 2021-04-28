<?php
namespace Core\App\Template;
class header_menu extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        $submenu_icon = '';
        if(MENU['config']['submenu']['fa_icon_enabled']) {
            $submenu_icon = '<i class="' . MENU['config']['submenu']['fa_icon'] . '" aria-hidden="true"></i>';
        }
        $menu = '<nav class="menu"><ul>';
        foreach(MENU['header'] as $menuitem) {
            if(isset($menuitem['submenu'])) {
                $menu .= '<li class="hassubmenu"><a href="' . $menuitem['link'] . '">' . LANG[$menuitem['lang']] . '&nbsp;' . $submenu_icon . '</a><ul class="submenu">';
                foreach($menuitem['submenu'] as $submenuitem) {
                    $fa_icon = '';
                    if($submenuitem['fa_icon']) {
                        $fa_icon = '<i class="' . $submenuitem['fa_icon'] . '" aria-hidden="true"></i>';
                    }
                    $menu .= '<li><a href="' . $submenuitem['link'] . '">'. $fa_icon . '&nbsp;' . LANG[$submenuitem['lang']] . '</a></li>';
                }
                $menu .= '</ul></li>';
            }
            else {
                $fa_icon = '';
                if($menuitem['fa_icon']) {
                    $fa_icon = '<i class="' . $menuitem['fa_icon'] . '" aria-hidden="true"></i>';
                }
                $menu .= '<li><a href="' . $menuitem['link'] . '">' . $fa_icon . '&nbsp;' .LANG[$menuitem['lang']] . '</a></li>';
            }
        }
        $menu .= '</ul></nav>';
        self::$template = $menu;
        return self::$template;
    }
}

?>