<?php 
    // Register Button Styles
    // UPDATE - core buttons have been disabled in favor of custom ones. However if re-enabled these styles will be available by default
    register_block_style(
        "core/button",
        [
            "name" => "button-primary",
            "label" => "Primary Solid",
            "is_default" => false
        ]
    );
    register_block_style(
        "core/button",
        [
            "name" => "button-primary-hollow",
            "label" => "Primary Hollow",
            "is_default" => false
        ]
    );
