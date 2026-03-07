<td>{$target->getYearMonth()}</td>
<td style="text-align: center">
	<span id="span_calls_{$target->getId()}">{$target->getCalls()}</span>
	<input type="text" value="{$target->getCalls()}" style="display: none; text-align: center" id="{$target->getId()}-calls" name="{$target->getId()}-calls" />
</td>
<td style="text-align: center">
	<span id="span_effectives_{$target->getId()}">{$target->getEffectives()}</span>
	<input type="text" value="{$target->getEffectives()}" style="display: none; text-align: center" id="{$target->getId()}-effectives" name="{$target->getId()}-effectives" />
</td>
<td style="text-align: center">
	<span id="span_meets_set_{$target->getId()}">{$target->getMeetingsSet()}</span>
	<input type="text" value="{$target->getMeetingsSet()}" style="display: none; text-align: center" id="{$target->getId()}-meets_set" name="{$target->getId()}-meets_set" />
</td>
<td style="text-align: center">
	<span id="span_meets_attended_{$target->getId()}">{$target->getMeetingsAttended()}</span>
	<input type="text" value="{$target->getMeetingsAttended()}" style="display: none; text-align: center" id="{$target->getId()}-meets_attended" name="{$target->getId()}-meets_attended" />
</td>
<td style="text-align: center">
	<span id="span_opportunities_{$target->getId()}">{$target->getOpportunities()}</span>
	<input type="text" value="{$target->getOpportunities()}" style="display: none; text-align: center" id="{$target->getId()}-opportunities" name="{$target->getId()}_opportunities" />
</td>
<td style="text-align: center">
	<span id="span_wins_{$target->getId()}">{$target->getWins()}</span>
	<input type="text" value="{$target->getWins()}" style="display: none; text-align: center" id="{$target->getId()}-wins" name="{$target->getId()}-wins" />
</td>
	<td style="text-align: center; vertical-align: middle">
	<input type="button" id="btn_edit_{$target->getId()}" value="Edit Line" onclick="javascript:editLine('{$target->getId()}');" />
</td>