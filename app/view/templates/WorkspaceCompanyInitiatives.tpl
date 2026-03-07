Posts contacted for <strong>{$initiative_name}</strong><br/><br/>{if $posts}
<table class="sortable" id="sortable_company_initiatives" style="width:99%">
  <thead>
  <tr>
    <th>Post contacted</th>
    <th>Status</th>
    <th>Period</th>
    <th>Prop</th>
    <th nowrap="nowrap">Last Eff</th>
    <th nowrap="nowrap">Next Call</th>
    <th>Meetings</th>
  </tr>
  </thead>
  <tbody>{foreach name="result_loop" from=$posts item=result}
    <tr id="post_row_{$result.post_id}"{if $result.post_id == $post_id} class="current"{/if}>
    <td>
      <a href="#" onclick="javascript:loadPost({$result.post_id}, {$result.initiative_id}, {$result.post_initiative_id}); makeSelected('post_list_by_post', {$result.post_id});"><strong>{$result.title}
          &nbsp;{$result.first_name}&nbsp;{$result.surname}</strong><br/>{$result.job_title}</a></td>
    <td>{$result.status} ({$result.status_id})</td>
    <td>{$result.next_comm_date_period}</td>
    <td>{* Note: need to inlcude the follwing span so that sortable.js can sort correctly on the propensity column*}
      <span style="display: none">{$result.propensity}</span>
      <img src="{$APP_URL}app/view/images/propensity_{$result.propensity}.gif" style="vertical-align:middle" alt="Propensity {$result.propensity}" title="Propensity {$result.propensity}"/>
    </td>
    <td nowrap="nowrap">{$result.last_effective_communication_date|date_format:"%d %b %y"}</td>
    <td nowrap="nowrap">{$result.next_communication_date|date_format:"%d %b %y"}</td>
    <td style="text-align: center; vertical-align: top">{if $result.meeting_count > 0}
        <span style="color: red; font-weight: bold">{$result.meeting_count}</span>{else}&nbsp;{/if}</td></tr>
  {/foreach}</tbody>
  <tfoot></tfoot></table>{else}<p><em>No posts contacted</em></p>{/if}
	