{* This is the 404 template, you can and should customize this to match your 
websites styles and give valuable information to the user about how they ended 
up here and possibly how they can find what they were looking for. *}
{include file="header.tpl"}
{if !empty($error)}
    {include file="_partials/error.tpl" error=$error}
{/if}
<h2 class="fourohfour"> 404 - Not Found </h2>
<div class="fourohfour">
    <p>The file you are attempting to load was not found. Please check the link or try again another time.</p>
    {if $_DEV_MODE === 'dev'}
        <br />
        {if $_CONTROLLER == 'load'}
            <div>
                GazooPHP was trying to auto load a {$_VIEW} file that is specific to certain template: <em>{$_DIR_TEMPLATES}{$requestedController}/{$requestedView}.tpl</em><br />
                It is being called because of a request to <em>/{$_CONTROLLER}/{$_VIEW}/controller{$_PARAM_VALUE_INDICATOR}{$requestedController}{$_PARAM_SEPARATOR}view{$_PARAM_VALUE_INDICATOR}{$requestedView}</em> <br /><br />
                This is likely being called in <em>{$_DIR_TEMPLATES}header.tpl</em>.<br /><br />
                If you aren't using template specific css/js you should remove the &#123*load*&#125 section(s) from <em>{$_DIR_TEMPLATES}header.tpl</em>.
            </div>
        {else}
            <div> 
                GazooPHP was trying to load the following file: <em>{$_DIR_TEMPLATES}{$_CONTROLLER}/{$_VIEW}.tpl</em><br />
                This path to this file can be defined in <em>{$_BASE_PATH}config/path.php</em><br /><br />            
                Default controller and view settings are defined in <em>{$_BASE_PATH}config/route.php</em>
            </div>
        {/if}
    {/if}        
</div>
{if $_DEV_MODE === 'dev'}
    <div class="fourohfour">
        You can view the GazooPHP documentation <a href="http://www.gazoophp.com/">here</a>.
    </div>
{/if}
{include file="footer.tpl"}
