<link href="{$_BASE_URL}css/debug.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$_BASE_URL}js/debug.js"></script>
<div class="debug">
    <center><h3>Debug</h3></center>
    <table>
        <tr>
            <th> num </th>
            <th> file </th>
            <th> line </th>
            <th> content </th>
            <th> backtrace </th>
        </tr>
        {foreach from=$debug item=v key=k}
            <tr>
                <th>{$k+1}</th>
                <td title="{$v.full_file}">{$v.file}</td>
                <td>{$v.line}</td>
                <td>
                    {if $v.content|is_array || $v.content|is_object}
                         <pre>
                            {$v.content|@print_r}
                        </pre>
                    {else}
                        {$v.content}
                    {/if}
                </td>
                <td>
                    {foreach from=$v.backtrace item=trace}
                        <div>
                            {if $trace.class}
                                <span title="{$trace.full_class}">{$trace.class}</span>
                            {else}
                                <span title="{$trace.full_file}">{$trace.file}</span>
                            {/if}
                            <strong>{$trace.function}</strong>
                            (
                            {foreach name=arg from=$trace.args item=arg}
                                {$arg}{if !$smarty.foreach.arg.last}, {/if}
                            {/foreach}
                            ) @ {$trace.line}
                        </div>
                    {/foreach}
                    
                </td>
            </tr>
        {/foreach}
    </table>
</div>