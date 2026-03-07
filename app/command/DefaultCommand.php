<?php
require_once( "app/command/Command.php" );

class app_command_DefaultCommand extends app_command_Command {
    function doExecute( app_controller_Request $request ) {
        header('Location: index.php?cmd=Home');
    }
}

?>
