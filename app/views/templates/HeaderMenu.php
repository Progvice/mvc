<?php
use Core\App\Template;
class HeaderMenu extends Template {
    static protected $template;
    public function load($values) {
        $submenu_icon = '';
        if(MENU['config']['submenu']['fa_icon_enabled']) {
            $submenu_icon = '<i class="' . MENU['config']['submenu']['fa_icon'] . '" aria-hidden="true"></i>';
        }
        $menu = '<nav><ul class="menu">';
        foreach(MENU['header'] as $menuitem) {
            if(isset($menuitem['disabled'])) continue;
            if(isset($menuitem['submenu'])) {
                $menu .= '<li><a href="' . $menuitem['link'] . '"><i class="' . $menuitem['fa_icon'] . '"></i>&nbsp;' . LANG[$menuitem['lang']] . '&nbsp;' . $submenu_icon . '<i class="fas fa-caret-down"></i></a><ul class="submenu">';
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
                $link_class = '';
                if(isset($menuitem['link_class'])) {
                    $link_class = ' class="' . $menuitem['link_class'] . '" ';
                }
                if($menuitem['fa_icon']) {
                    $fa_icon = '<i class="' . $menuitem['fa_icon'] . '" aria-hidden="true"></i>';
                }
                $menu .= '<li><a href="' . $menuitem['link'] . '"' . $link_class . '>' . $fa_icon . '&nbsp;' .LANG[$menuitem['lang']] . '</a></li>';
            }
        }
        if(isset($_SESSION['login'])) {
            $menu .= '<li><a href="/profiili" class="header_button"><i class="fas fa-sign-in-alt" aria-hidden="true"></i>&nbsp;' . LANG['profile'] . '</a></li>';
        }
        else {
            $menu .= '<li><a href="/login" class="header_button"><i class="fas fa-sign-in-alt" aria-hidden="true"></i>&nbsp;' . LANG['login'] .'</a></li>';
        }
        $menu .= '</ul></nav>';
        self::$template = $menu;
        return self::$template;
    }
}

?>