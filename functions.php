<?php

use dottxado\theartisinner\StorefrontMods;
use dottxado\theartisinner\ThemeDocumentation;
use dottxado\theartisinner\ThemeSetup;
use dottxado\theartisinner\WoocommerceMods;

require 'vendor/autoload.php';

ThemeSetup::get_instance();
ThemeDocumentation::get_instance();

if ( ThemeSetup::check_dependency() ) {
	StorefrontMods::get_instance();
	WoocommerceMods::get_instance();
}
